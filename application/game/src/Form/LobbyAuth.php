<?php
/**
 * Epixa - Cards
 */

namespace Game\Form;

use Epixa\Form\BaseForm,
    Game\Model\Lobby as LobbyModel,
    Zend_Validate_Callback as CallbackValidator;

/**
 * @category   Module
 * @package    Game
 * @subpackage Form
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class LobbyAuth extends BaseForm
{
    /**
     * @var null|Game\Model\Lobby
     */
    protected $_lobby = null;


    /**
     * Initialize the auth form
     * 
     * @return void
     */
    public function init()
    {
        $this->addElement('password', 'password', array(
            'required' => true,
            'label' => 'Password'
        ));

        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Submit'
        ));
    }

    /**
     * Sets the current lobby to authenticate against
     * 
     * @param  Game\Model\Lobby $lobby
     * @return LobbyAuth *Fluent interface*
     */
    public function setLobby(LobbyModel $lobby)
    {
        $this->_lobby = $lobby;

        $password = $this->getElement('password');
        if ($password) {
            $validator = new CallbackValidator(array($lobby, 'comparePassword'));
            $validator->setMessage('The lobby password you entered is invalid', CallbackValidator::INVALID_VALUE);
            $password->addValidator($validator, true);
        }
        
        return $this;
    }
}