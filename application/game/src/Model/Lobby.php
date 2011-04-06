<?php
/**
 * Epixa - Cards
 */

namespace Game\Model;

use Epixa\Model\AbstractModel,
    Game\Encoder\Base58 as Base58Encoder,
    User\Model\User as UserModel,
    Zend_Acl_Resource_Interface as ResourceInterface,
    DateTime,
    BadMethodCallException,
    InvalidArgumentException;

/**
 * @category   Module
 * @package    Game
 * @subpackage Model
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 *
 * @Entity(repositoryClass="Game\Repository\Lobby")
 * @Table(name="game_lobby")
 *
 * @property-read integer $id
 *
 * @property string          $name
 * @property string          password
 * @property DateTime        $dateCreated
 * @property User\Model\User $createdBy
 * @property string          $urlKey
 */
class Lobby extends AbstractModel implements ResourceInterface
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
     * @Column(type="string", name="password")
     */
    protected $password;

    /**
     * @Column(type="datetime", name="date_created")
     */
    protected $dateCreated;

    /**
     * @ManyToOne(targetEntity="User\Model\User")
     * @JoinColumn(name="created_by_id", referencedColumnName="id")
     */
    protected $createdBy;


    /**
     * @var null|string
     */
    protected $_urlKey = null;


    /**
     * Constructor
     *
     * Initializes the date this model was created
     */
    public function __construct()
    {
        $this->dateCreated = new DateTime('now');
    }

    /**
     * @throws BadMethodCallException Because you cannot set id directly
     */
    public function setId()
    {
        throw new BadMethodCallException('Cannot set id directly');
    }

    /**
     * Sets the lobby name
     *
     * @param  string $name
     * @return Lobby *Fluent interface*
     */
    public function setName($name)
    {
        $this->name = (string)$name;

        return $this;
    }

    /**
     * Sets the lobby password
     *
     * @param  string $password
     * @return Lobby *Fluent interface*
     */
    public function setPassword($password)
    {
        $this->password = (string)$password;

        return $this;
    }

    /**
     * Sets the user that created this lobby
     *
     * @param  string $user
     * @return Lobby *Fluent interface*
     */
    public function setCreatedBy(UserModel $user)
    {
        $this->createdBy = $user;

        return $this;
    }

    /**
     * Sets the date that this lobby was created
     *
     * @param  string|integer|DateTime $date
     * @return Lobby *Fluent interface*
     */
    public function setDateCreated($date)
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        } else if (is_int($date)) {
            $date = new DateTime(sprintf('@%d', $date));
        } else if (!$date instanceof DateTime) {
            throw new InvalidArgumentException(sprintf(
                'Expecting string, integer or DateTime, but got `%s`',
                is_object($date) ? get_class($date) : gettype($date)
            ));
        }

        $this->dateCreated = $date;

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

    /**
     * Gets the unique url key for this lobby
     *
     * @throws LogicException If model id is not set
     * @return string
     */
    public function getUrlKey()
    {
        if (!$this->id) {
            throw new LogicException('Cannot retrieve the url key of an unsaved model');
        }

        if ($this->_urlKey === null) {
            $encoder = new Base58Encoder();
            $this->_urlKey = $encoder->encode($this->id);
        }

        return $this->_urlKey;
    }
}