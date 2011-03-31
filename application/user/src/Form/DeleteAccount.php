<?php
/**
 * Epixa - Cards
 */

namespace User\Form;

use Epixa\Form\BaseForm,
    User\Model\User as UserModel,
    Zend_Form_Element as FormElement,
    Zend_Validate_Identical as IdenticalValidator,
    Zend_Validate_Callback as CallbackValidator;

/**
 * @category   Module
 * @package    User
 * @subpackage Form
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class DeleteAccount extends BaseForm
{
    /**
     * @var UserModel
     */
    protected $_user;


    /**
     * {@inheritdoc}
     *
     * In addition, the given user model is applied to the form
     *
     * @param  mixed     $options
     * @param  UserModel $user
     * @return void
     */
    public function __construct($options = null, UserModel $user)
    {
        $this->setUser($user);

        parent::__construct($options);
    }

    /**
     * Sets up the change password forms
     *
     * @return void
     */
    public function init()
    {
        $loginForm = new Login();

        $validator = new IdenticalValidator($this->_user->auth->loginId);
        $validator->setMessage('Username not valid', IdenticalValidator::NOT_SAME);
        $validator->setStrict(true);
        $loginId = $loginForm->getElement('login_id');
        $loginId->addValidator($validator);
        $this->addElement($loginId);

        $validator = new CallbackValidator(array($this->_user->auth, 'comparePassword'));
        $validator->setMessage('Password not valid');
        $password = $loginForm->getElement('password');
        $password->addValidator($validator);
        $this->addElement($password);

        $this->addElement('captcha', 'confirmation', array(
            'label' => 'Type the following phrase',
            'required' => true,
            'captcha' => array(
                'captcha' => 'Figlet',
                'wordLen' => 4,
                'timeout' => 300,
            )
        ));

        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Login'
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

    /**
     * Sets the user that is changing their password
     *
     * @param  UserModel $user
     * @return ChangePassword *Fluent interface*
     */
    public function setUser(UserModel $user)
    {
        $this->_user = $user;

        return $this;
    }
}