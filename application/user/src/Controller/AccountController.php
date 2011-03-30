<?php
/**
 * Epixa - Cards
 */

namespace User\Controller;

use Epixa\Controller\AbstractController,
    User\Form\BaseUser as BaseUserForm,
    User\Form\ForgotPassword as ForgotPasswordForm,
    User\Form\ChangePassword as ChangePasswordForm,
    User\Service\User as UserService,
    Zend_Auth as Auth,
    Epixa\Exception\DeniedException;

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
    {
        if (!Auth::getInstance()->hasIdentity()) {
            throw new DeniedException('You must be logged in to change your password');
        }

        $request = $this->getRequest();

        $user = Auth::getInstance()->getIdentity();

        $form = new ChangePasswordForm(null, $user);
        $this->view->form = $form;

        if (!$request->isPost() || !$form->isValid($request->getPost())) {
            return;
        }

        $service = new UserService();
        $service->changePassword($user, $form->getValues());

        $this->_helper->flashMessenger->addMessage('Your password has been changed.');

        $this->_helper->redirector->gotoUrlAndExit('/');
    }

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