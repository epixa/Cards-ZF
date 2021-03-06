<?php
/**
 * Epixa - Cards
 */

namespace Game\Controller;

use Epixa\Controller\AbstractController,
    Game\Service\Lobby as LobbyService,
    Game\Form\Lobby as LobbyForm,
    Game\Form\LobbyAuth as LobbyAuthForm,
    Zend_Session_Namespace as SessionNamespace;

/**
 * Game lobby controller
 *
 * @category   Module
 * @package    Game
 * @subpackage Controller
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class LobbyController extends AbstractController
{
    /**
     * @param Zend_Session_Namespace
     */
    protected $gameSession = null;

    /**
     * Initializes the module game session
     */
    public function init()
    {
        if ($this->gameSession === null) {
            $ns = new SessionNamespace('Module_Game');
            if (!is_array($ns->lobbies)) {
                $ns->lobbies = array();
            }

            $this->gameSession = $ns;
        }
    }

    /**
     * Renders a specific lobby
     */
    public function viewAction()
    {
        $request = $this->getRequest();

        $key = $request->getParam('key', null);
        if (!in_array($key, $this->gameSession->lobbies)) {
            $this->_forward('login');
            return;
        }

        $service = new LobbyService();
        $lobby = $service->getByKey($request->getParam('key', null));

        $this->view->lobby = $lobby;
    }

    /**
     * Renders and processes the lobby login form
     */
    public function loginAction()
    {
        $request = $this->getRequest();

        $key = $request->getParam('key', null);
        if (in_array($key, $this->gameSession->lobbies)) {
            $this->_helper->redirector->gotoRouteAndExit(array('key' => $key), 'lobby', true);
        }

        $service = new LobbyService();
        $lobby = $service->getByKey($key);

        $form = new LobbyAuthForm();
        $form->setLobby($lobby);
        $this->view->form = $form;
        $this->view->lobby = $lobby;

        if (!$request->isPost() || !$form->isValid($request->getPost())) {
            return;
        }
        
        $this->gameSession->lobbies[] = $lobby->urlKey;
        
        $this->_helper->redirector->gotoRouteAndExit(array('key' => $lobby->urlKey), 'lobby', true);
    }

    /**
     * Renders and processes the form for creating new game lobbies
     * 
     * @return void
     */
    public function createAction()
    {
        $request = $this->getRequest();

        $form = new LobbyForm();
        $this->view->form = $form;

        if (!$request->isPost() || !$form->isValid($request->getPost())) {
            return;
        }

        $service = new LobbyService();
        $lobby = $service->add($form->getValues());

        $this->gameSession->lobbies[] = $lobby->urlKey;

        $this->_helper->flashMessenger->addMessage('Welcome to your new game lobby.');

        $this->_helper->redirector->gotoRouteAndExit(array('key' => $lobby->urlKey), 'lobby', true);
    }
}