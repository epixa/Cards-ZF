<?php
/**
 * Epixa - Cards
 */

namespace User\Controller;

use Epixa\Controller\AbstractController,
    User\Form\BaseUser as BaseUserForm,
    User\Form\ForgotPassword as ForgotPasswordForm,
    User\Service\User as UserService;

/**
 * Manage user account functionality
 *
 * @category   Module
 * @package    User
 * @subpackage Controller
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class AccountController extends AbstractController
{
    /**
     * Register a new account
     */
    public function registerAction()
    {
        $request = $this->getRequest();

        $form = new BaseUserForm();
        $this->view->form = $form;

        if (!$request->isPost() || !$form->isValid($request->getPost())) {
            return;
        }

        $service = new UserService();
        $service->register($form->getValues());
        
        $this->_helper->flashMessenger->addMessage('Account created');

        $this->_helper->redirector->gotoUrlAndExit('/login');
    }

    /**
     * Change the password of your existing account
     */
    public function changePasswordAction()
    {}

    /**
     * Gain access to your existing account if you forget the password
     */
    public function forgotPasswordAction()
    {
        $request = $this->getRequest();

        $form = new ForgotPasswordForm();
        $this->view->form = $form;

        if (!$request->isPost() || !$form->isValid($request->getPost())) {
            return;
        }

        $service = new UserService();
        $service->resetPassword($form->getValues());

        $this->_helper->flashMessenger->addMessage('A temporary password has been sent to your email address');

        $this->_helper->redirector->gotoUrlAndExit('/login');
    }

    /**
     * Delete your existing account
     */
    public function deleteAction()
    {}
}