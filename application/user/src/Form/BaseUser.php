<?php
/**
 * Epixa - Cards
 */

namespace User\Form;

use Epixa\Form\BaseForm,
    Zend_Validate_Identical as IdenticalValidator,
    Zend_Form_Element as FormElement;

/**
 * This form contains all of the base fields for users with their default 
 * requirements.
 * 
 * @category   Module
 * @package    User
 * @subpackage Form
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class BaseUser extends BaseForm
{
    public function init()
    {
        $this->addElement('text', 'login_id', array(
            'required' => true,
            'label' => 'Username',
            'validators' => array(
                array('StringLength', false, array(1, 255))
            )
        ));

        $this->addElement('password', 'password', array(
            'required' => true,
            'label' => 'Password',
            'validators' => array(
                array('StringLength', false, array(6, 255))
            )
        ));
        
        $validator = new IdenticalValidator('password');
        $validator->setMessage('Confirmation password does not match', IdenticalValidator::NOT_SAME);
        $this->addElement('password', 'confirm_password', array(
            'label' => 'Confirm Password',
            'validators' => array(
                $validator
            )
        ));
        
        $this->addElement('text', 'email', array(
            'label' => 'Email',
            'validators' => array(
                'EmailAddress'
            )
        ));

        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Save'
        ));
    }
    
    /**
     * {@inheritdoc}
     * 
     * @param  array $data
     * @param  array $context
     * @return boolean
     */
    public function isValid($data, $context = array())
    {
        if (isset($data['password']) && $data['password'] != '') {
            $confirmPassword = $this->getElement('confirm_password');
            if ($confirmPassword instanceof FormElement) {
                $confirmPassword->setRequired(true);
            }
            
            $password = $this->getElement('password');
            if ($password instanceof FormElement) {
                $password->setRequired(true);
            }
        }
        
        return parent::isValid($data, $context);
    }
}