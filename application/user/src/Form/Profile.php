<?php
/**
 * Epixa - Cards
 */

namespace User\Form;

/**
 * Passwords are not required when editing a user profile
 * 
 * @category   Module
 * @package    User
 * @subpackage Form
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Profile extends BaseUser
{
    public function init()
    {
        parent::init();
        
        $password = clone $this->getElement('password');
        $password->setName('existing_password')
                 ->setLabel('Existing Password')
                 ->setRequired(true)
                 ->setOrder(-9999);
        $this->addElement($password);
        
        $this->getElement('password')
             ->setLabel('New Password')
             ->setRequired(false);
    }
}