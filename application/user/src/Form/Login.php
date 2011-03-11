<?php
/**
 * Epixa - Cards
 */

namespace User\Form;

use Epixa\Form\BaseForm;

/**
 * @category   Module
 * @package    User
 * @subpackage Form
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Login extends BaseForm
{
    public function init()
    {
        $userForm = new BaseUser();
        $loginId = $userForm->getElement('login_id');
        $this->addElement($loginId);
        
        $password = $userForm->getElement('password');
        $this->addElement($password);

        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Login'
        ));
    }
}