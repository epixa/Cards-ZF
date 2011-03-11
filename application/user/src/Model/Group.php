<?php
/**
 * Epixa - Cards
 */

namespace User\Model;

use Epixa\Model\AbstractModel,
    Zend_Acl_Role_Interface as RoleInterface,
    LogicException;

/**
 * @category   Module
 * @package    User
 * @subpackage Model
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 *
 * @Entity(repositoryClass="User\Repository\Group")
 * @Table(name="user_group")
 *
 * @property integer $id
 * @property string  $name
 * @property string  $code
 */
class Group extends AbstractModel implements RoleInterface
{
    /**
     * @Id
     * @Column(type="integer", name="id")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column(type="string", name="name")
     */
    protected $name;
    
    /**
     * @Column(type="string", name="code")
     */
    protected $code;


    /**
     * Constructor
     *
     * Sets the group name and code
     *
     * @param string $name
     * @param string $code
     */
    public function __construct($name, $code)
    {
        $this->setName($name)
             ->setCode($code);
    }

    /**
     * Throws exception so id cannot be set directly
     *
     * @param integer $id
     */
    public function setId($id)
    {
        throw new LogicException('Cannot set id directly');
    }

    /**
     * Set the group name
     *
     * @param  string $name
     * @return Group *Fluent interface*
     */
    public function setName($name)
    {
        $this->name = (string)$name;

        return $this;
    }

    /**
     * Get the group name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set the group code
     *
     * @param  string $code
     * @return Group *Fluent interface*
     */
    public function setCode($code)
    {
        $this->code = (string)$code;

        return $this;
    }

    /**
     * Get the group name
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get the role id for this group
     *
     * @return string
     */
    public function getRoleId()
    {
        if (!$this->id) {
            throw new LogicException('Group is not yet persisted');
        }

        return __CLASS__ . $this->id;
    }
}