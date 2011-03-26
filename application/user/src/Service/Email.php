<?php
/**
 * Epixa - Cards
 */

namespace User\Service;

use Epixa\Service\AbstractEmailService,
    User\Model\User as UserModel,
    InvalidArgumentException;

/**
 * @category   Module
 * @package    User
 * @subpackage Service
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Email extends AbstractEmailService
{
    /**
     * Sends a registration email to the given user
     * 
     * @param  UserModel $user
     * @throws InvalidArgumentException If email is verified or not set
     */
    public function sendRegistrationEmail(UserModel $user)
    {
        if ($user->profile->emailIsVerified()) {
            throw new InvalidArgumentException("User's email is already verified");
        }

        if (!$user->profile->email) {
            throw new InvalidArgumentException("User has not set an email");
        }

        $this->getView()->user = $user;

        $this->send('registration', $user->profile->email, $user->alias, 'New Registration');
    }
}