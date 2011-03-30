<?php
/**
 * Epixa - Cards
 */

namespace User\Service;

use Epixa\Service\AbstractDoctrineService,
    User\Model\Session,
    User\Model\User as UserModel,
    User\Model\Auth as AuthModel,
    User\Model\Profile as ProfileModel,
    Epixa\Exception\NotFoundException,
    InvalidArgumentException,
    LogicException,
    Doctrine\ORM\NoResultException;

/**
 * @category   Module
 * @package    User
 * @subpackage Service
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class User extends AbstractDoctrineService
{
    /**
     * Resets a user's password
     * 
     * @param  array $data
     * @throws InvalidArgumentException
     */
    public function resetPassword(array $data)
    {
        if (!isset($data['login_id'])) {
            throw new InvalidArgumentException('No login_id provided');
        }

        if (!isset($data['email'])) {
            throw new InvalidArgumentException('No email provided');
        }

        $user = $this->getByEmail($data['email']);
        if ($user->auth->loginId !== $data['login_id']) {
            throw new LogicException('The login_id is invalid');
        }

        $password = $this->createRandomPassword($user);

        $user->auth->password = $password;
        $user->auth->isTemporaryPass = true;

        $emailService = new Email();
        $emailService->sendPasswordResetEmail($user, $password);

        $this->getEntityManager()->flush();
    }

    /**
     * Gets a user by id
     *
     * @param  integer $id
     * @return UserModel
     * @throws NotFoundException
     */
    public function get($id)
    {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('User\Model\User');

        $qb = $repo->createQueryBuilder('u');

        $repo->restrictToId($qb, $id);

        try {
            $user = $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new NotFoundException(
                sprintf('User `%s` not found', $id), null, $e
            );
        }

        return $user;
    }

    /**
     * Get a user by email
     * 
     * @param  string $email
     * @return UserModel
     * @throws NotFoundException
     */
    public function getByEmail($email)
    {
        $em = $this->getEntityManager();

        $repo = $em->getRepository('User\Model\User');

        $qb = $repo->createQueryBuilder('u');

        $repo->restrictToEmail($qb, $email);

        try {
            $user = $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new NotFoundException(
                sprintf('User `%s` not found', $email), null, $e
            );
        }

        return $user;
    }

    /**
     * Verifies a user's email address by the given verification key
     * 
     * @param  UserModel $user
     * @param  string $key
     * @throws InvalidArgumentException|LogicException
     */
    public function verifyEmail(UserModel $user, $key)
    {
        $profile = $user->profile;

        if (!$profile->email) {
            throw new InvalidArgumentException('User does not have an email set');
        }

        if ($profile->emailIsVerified()) {
            throw new InvalidArgumentException('Email address is already verified');
        }

        if ($profile->emailVerificationKey !== $key) {
            throw new InvalidArgumentException('Verification keys do not match');
        }

        $em = $this->getEntityManager();
        if (!$em->contains($profile)) {
            throw new LogicException('Profile entity is not managed');
        }

        $profile->emailVerificationKey = null;

        if (!$user->isVerified()) {
            $groupService = new Group();
            $group = $groupService->getByCode(Group::VERIFIED);
            $user->groups = array($group);
        }

        $em->flush();
    }

    /**
     * Registers a new user account with the provided data
     * 
     * @param  array $data
     * @return UserModel
     * @throws InvalidArgumentException If no login id or password are set
     */
    public function register(array $data)
    {
        if (!isset($data['login_id'])) {
            throw new InvalidArgumentException('A login_id is required for new users');
        }
        
        if (!isset($data['password'])) {
            throw new InvalidArgumentException('A password is required for new users');
        }
        
        $groupService = new Group();
        $group = $groupService->getByCode(Group::UNVERIFIED);
        
        $data['alias'] = $data['login_id'];
        $data['groups'] = array($group);
        $data['is_temporary_pass'] = false;
        
        $user = $this->add($data);
        
        if (!$user->profile->emailIsVerified()) {
            $emailService = new Email();
            $emailService->sendRegistrationEmail($user);
        }
        
        return $user;
    }
    
    /**
     * Adds a new user account with the provided data
     * 
     * @param array $data
     * @return UserModel 
     */
    public function add(array $data)
    {
        $user = new UserModel();
        $user->populate($data);
        
        $auth = new AuthModel();
        $auth->populate($data);
        
        $profile = new ProfileModel();
        $profile->populate($data);
        
        $user->setProfile($profile);
        $auth->setUser($user);
        $profile->setUser($user);
        
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->persist($auth);
        $em->persist($profile);
        $em->flush();
        
        return $user;
    }
    
    /**
     * Attempt to login with the given credentials
     *
     * @param  array $credentials
     * @return Session
     * @throws InvalidArgumentException If insufficent crendetials are provided
     * @throws NotFoundException If no user is found with those credentials
     */
    public function login(array $credentials)
    {
        if (!isset($credentials['login_id'])
            || !isset($credentials['password'])) {
            throw new InvalidArgumentException(
                'A login_id and password are required for login'
            );
        }

        $em   = $this->getEntityManager();
        $repo = $em->getRepository('User\Model\Auth');

        $qb = $repo->createQueryBuilder('ua');

        $repo->includeUser($qb);
        $repo->restrictToLoginId($qb, $credentials['login_id']);

        try {
            $auth = $qb->getQuery()->getSingleResult();
            if (!$auth->comparePassword($credentials['password'])) {
                // TODO: Failed login attempts
                throw new NoResultException('Password does not match');
            }
        } catch (NoResultException $e) {
            throw new NotFoundException('Invalid credentials', null, $e);
        }

        return new Session($auth->user);
    }

    /**
     * Creates a random password for the given user
     * 
     * @param  UserModel $user
     * @return string
     */
    public function createRandomPassword(UserModel $user)
    {
        return substr(sha1(uniqid() . microtime() . serialize($user)), 0, 12);
    }
}