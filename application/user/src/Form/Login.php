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
        $this->addElement('text', 'username', array(
            'required' => true,
            'label' => 'Username'
        ));

        $this->addElement('password', 'password', array(
            'required' => true,
            'label' => 'Password'
        ));

        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Login'
        ));
    }
}