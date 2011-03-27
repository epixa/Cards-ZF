<?php
/**
 * Epixa - Cards
 */

namespace User\Model;

use Epixa\Model\AbstractModel,
    BadMethodCallException;

/**
 * @category   Module
 * @package    User
 * @subpackage Model
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 *
 * @Entity
 * @Table(name="user_profile")
 *
 * @property integer         $id
 * @property User\Model\User $user
 * @property null|string     $email
 * @property string          $emailVerificationKey
 */
class Profile extends AbstractModel
{
    /**
     * @Id
     * @Column(type="integer", name="id")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @OneToOne(targetEntity="User\Model\User", inversedBy="profile")
     */
    protected $user;

    /**
     * @Column(type="string", name="email", nullable=true)
     */
    protected $email = null;

    /**
     * @Column(type="string", name="email_verification_key", nullable=true)
     */
    protected $emailVerificationKey = null;


    /**
     * @throws BadMethodCallException Because you cannot set id directly
     */
    public function setId()
    {
        throw new BadMethodCallException('Cannot set id directly');
    }

    /**
     * Sets the user
     *
     * @param  User $user
     * @return Profile *Fluent interface*
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Sets the email
     *
     * @param  null|string $email
     * @return Profile *Fluent interface*
     */
    public function setEmail($email = null)
    {
        $oldEmail = $this->email;

        $this->email = ($email === null ? $email : (string)$email);

        if ($oldEmail !== $this->email) {
            $key = $this->createEmailVerificationKey();
            $this->setEmailVerificationKey($key);
        }

        return $this;
    }

    /**
     * Sets the email verification key
     * 
     * @param  null|string $key
     * @return Profile *Fluent interface*
     */
    public function setEmailVerificationKey($key = null)
    {
        $this->emailVerificationKey = ($key === null ? $key : (string)$key);

        return $this;
    }

    /**
     * Creates and returns a new email verification key
     * 
     * @return string
     */
    public function createEmailVerificationKey()
    {
        return sha1($this->email . uniqid() . microtime());
    }

    /**
     * Is the email address verified?
     * 
     * @return boolean
     */
    public function emailIsVerified()
    {
        return $this->emailVerificationKey === null;
    }
}