<?php
/**
 * Epixa - Cards
 */

namespace Game\Model;

use Epixa\Model\AbstractModel,
    Doctrine\Common\Collections\ArrayCollection,
    Zend_Acl_Resource_Interface as ResourceInterface,
    LogicException,
    DateTime;

/**
 * @category   Module
 * @package    Game
 * @subpackage Model
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 *
 * @Entity(repositoryClass="Game\Repository\Game")
 * @Table(name="game")
 *
 * @property integer          $id
 * @property Game\Model\Type  $type
 * @property Game\Model\Lobby $lobby
 * @property DateTime         $dateCreated
 */
class Game extends AbstractModel implements ResourceInterface
{
    /**
     * @Id
     * @Column(type="integer", name="id")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="Game\Model\Type")
     * @JoinColumn(name="type_id", referencedColumnName="id")
     */
    protected $type;

    /**
     * @OneToOne(targetEntity="Game\Model\Lobby", inversedBy="game")
     * @JoinColumn(name="lobby_id", referencedColumnName="id")
     */
    protected $lobby;

    /**
     * @Column(type="datetime", name="date_created")
     */
    protected $dateCreated;

    /**
     * @OneToMany(targetEntity="Game\Model\Player", mappedBy="game")
     */
    protected $players;


    /**
     * Constructor
     *
     * Sets the game type, lobby, and date created
     *
     * @param Game\Model\Type  $type
     * @param Game\Model\Lobby $lobby
     */
    public function __construct($type, $lobby)
    {
        $this->players = new ArrayCollection();
        
        $this->setDateCreated('now')
             ->setType($type)
             ->setLobby($lobby);
    }

    /**
     * @throws LogicException Because id cannot be set directly
     */
    public function setId()
    {
        throw new LogicException('Cannot set id directly');
    }

    /**
     * Sets the game type
     * 
     * @param  Game\Model\Type $type
     * @return Game *Fluent interface*
     */
    public function setType(Type $type)
    {
        $this->type = $type;
        
        return $this;
    }

    /**
     * Sets the game lobby
     *
     * @param  Game\Model\Lobby $lobby
     * @return Game *Fluent interface*
     */
    public function setLobby(Lobby $lobby)
    {
        $this->lobby = $lobby;
        
        return $this;
    }

    /**
     * Sets the date that this lobby was created
     *
     * @param  string|integer|DateTime $date
     * @return Game *Fluent interface*
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
     * Sets the game players
     *
     * @param  array $players
     * @return Game *Fluent interface*
     */
    public function setPlayers(array $players)
    {
        $this->players->clear();
        foreach ($players as $player) {
            $this->addPlayer($player);
        }

        return $this;
    }

    /**
     * Adds a new game player
     *
     * @param  Game\Model\Player $player
     * @return Game *Fluent interface*
     */
    public function addPlayer(Player $player)
    {
        $this->players->add($player);

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