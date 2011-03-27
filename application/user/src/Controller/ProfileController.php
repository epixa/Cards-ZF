<?php
/**
 * Epixa - Cards
 */

namespace User\Controller;

use Epixa\Controller\AbstractController,
    User\Service\User as UserService,
    LogicException;

/**
 * Controller for viewing and editing user profiles
 *
 * @category   Module
 * @package    User
 * @subpackage Controller
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class ProfileController extends AbstractController
{
    /**
     * View a specific user's profile
     */
    public function viewAction()
    {}

    /**
     * Edit your user profile
     */
    public function editAction()
    {}

    /**
     * Verifies a given user's email address
     */
    public function verifyEmailAction()
    {
        $request = $this->getRequest();

        $id = $request->getParam('id', null);
        if (!$id) {
            throw new LogicException('No user id specified');
        }

        $key = $request->getParam('key', null);
        if (!$key) {
            throw new LogicException('No email verification key specified');
        }

        $service = new UserService();
        $user = $service->get($id);

        if ($user->profile->emailIsVerified()) {
            $this->_helper->flashMessenger->addMessage('Your email address is already verified');
            return;
        }

        $service->verifyEmail($user, $key);

        $this->_helper->flashMessenger->addMessage('Email verified');
        $this->_helper->redirector->gotoUrlAndExit('/');
    }
}