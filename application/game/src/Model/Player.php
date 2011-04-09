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
 * @Entity(repositoryClass="Game\Repository\Player")
 * @Table(name="game_player")
 *
 * @property integer         $id
 * @property User\Model\User $user
 * @property Game\Model\Game $game
 */
class Player extends AbstractModel implements ResourceInterface
{
    /**
     * @Id
     * @Column(type="integer", name="id")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="User\Model\User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ManyToOne(targetEntity="Game\Model\Game", inversedBy="players")
     * @JoinColumn(name="game_id", referencedColumnName="id")
     */
    protected $game;


    /**
     * Constructor
     *
     * Sets the user and game
     *
     * @param Game\Model\User $user
     * @param Game\Model\Game $game
     */
    public function __construct($user, $game)
    {
        $this->setUser($user)
             ->setGame($game);
    }

    /**
     * @throws LogicException Because id cannot be set directly
     */
    public function setId()
    {
        throw new LogicException('Cannot set id directly');
    }

    /**
     * Sets the user
     *
     * @param  User\Model\User $user
     * @return Game *Fluent interface*
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Sets the game
     *
     * @param  Game\Model\Game $game
     * @return Game *Fluent interface*
     */
    public function setGame(Game $game)
    {
        $this->game = $game;

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