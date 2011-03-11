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
        
        return $this->add($data);
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
}