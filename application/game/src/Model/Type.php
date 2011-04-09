<?php
/**
 * Epixa - Cards
 */

namespace Game\Model;

use Epixa\Model\AbstractModel,
    Zend_Acl_Resource_Interface as ResourceInterface,
    LogicException;

/**
 * @category   Module
 * @package    Game
 * @subpackage Model
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 *
 * @Entity(repositoryClass="Game\Repository\Type")
 * @Table(name="game_type")
 *
 * @property integer $id
 * @property string  $name
 * @property string  $code
 */
class Type extends AbstractModel implements ResourceInterface
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
     * Sets the type name and code
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
     * @throws LogicException Because id cannot be set directly
     */
    public function setId()
    {
        throw new LogicException('Cannot set id directly');
    }

    /**
     * Set the group name
     *
     * @param  string $name
     * @return Type *Fluent interface*
     */
    public function setName($name)
    {
        $this->name = (string)$name;

        return $this;
    }

    /**
     * Set the group code
     *
     * @param  string $code
     * @return Type *Fluent interface*
     */
    public function setCode($code)
    {
        $this->code = (string)$code;

        return $this;
    }

    /**
     * Get the resource identifier for this model
     *
     * @return string
     */
    public function getResourceId()
    {
        return __CLASS__;
    }
}