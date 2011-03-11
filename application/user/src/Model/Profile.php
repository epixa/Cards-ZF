<?php
/**
 * Epixa - Cards
 */

namespace User\Model;

use Epixa\Model\AbstractModel;

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
     * Throws exception so id cannot be set directly
     *
     * @param integer $id
     */
    public function setId($id)
    {
        throw new \LogicException('Cannot set id directly');
    }

    /**
     * Set the user
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
     * Set the email
     *
     * @param  null|string $email
     * @return Profile *Fluent interface*
     */
    public function setEmail($email = null)
    {
        $this->email = $email;
        
        if ($email !== null) {
            $this->email = (string)$this->email;
        }
        
        return $this;
    }
}