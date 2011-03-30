<?php
/**
 * Epixa - Cards
 */

namespace User\Controller;

use Epixa\Controller\AbstractController,
    User\Form\Login as LoginForm,
    User\Service\User as UserService,
    Epixa\Exception\NotFoundException,
    Zend_Auth as Authenticator;

/**
 * User authentication controller
 *
 * @category   Module
 * @package    User
 * @subpackage Controller
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class AuthController extends AbstractController
{
    /**
     * Log in a user
     */
    public function loginAction()
    {
        $authenticator = Authenticator::getInstance();
        if ($authenticator->hasIdentity()) {
            $this->_helper->redirector->gotoUrlAndExit('/');
        }

        $request = $this->getRequest();

        $form = new LoginForm();
        $this->view->form = $form;

        if (!$request->isPost() || !$form->isValid($request->getPost())) {
            return;
        }

        try {
            $service = new UserService();
            $session = $service->login($form->getValues());
        } catch (NotFoundException $e) {
            $form->addError('Could not find a user with those credentials');
            return;
        }

        $authenticator->getStorage()->write($session);

        if ($authenticator->getIdentity()->auth->isTemporaryPass) {
            $message = 'You are currently using a temporary password, so you should change it now.';
            $params = array('module' => 'user', 'controller' => 'account', 'action' => 'change-password');

            $this->_helper->flashMessenger->addMessage($message);
            $this->_helper->redirector->gotoRouteAndExit($params, 'default', true);
        } else {
            $this->_helper->redirector->gotoUrlAndExit('/');
        }
    }

    /**
     * Logs out the current user.
     */
    public function logoutAction()
    {
        Authenticator::getInstance()->clearIdentity();

        $this->_helper->redirector->gotoUrlAndExit('/');
    }
}