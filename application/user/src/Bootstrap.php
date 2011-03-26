<?php
/**
 * Epixa - Cards
 */

namespace User;

use Epixa\Application\Module\Bootstrap as ModuleBootstrap,
    User\Model\Auth as AuthModel,
    Zend_Auth as Authenticator,
    Zend_View as View,
    Epixa\Auth\Storage\Doctrine as DoctrineStorage,
    Epixa\Phpass,
    Epixa\Service\AbstractEmailService as EmailService;

/**
 * Bootstrap the user module
 *
 * @category  Module
 * @package   User
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class Bootstrap extends ModuleBootstrap
{
    /**
     * Initializes the doctrine auth identity storage
     */
    public function _initAuthStorage()
    {
        $bootstrap = $this->getApplication()->bootstrap('doctrine');
        $em = $bootstrap->getResource('doctrine');

        $storage = new DoctrineStorage($em, 'User\Model\Session');
        Authenticator::getInstance()->setStorage($storage)->getIdentity();
    }

    /**
     * Sets up the default phpass object to be used in authentication models
     */
    public function _initPhpass()
    {
        $options = $this->getOptions();

        $iterations = isset($options['phpassIterations']) 
                    ? $options['phpassIterations']
                    : 8;
        $phpass = new Phpass($iterations);
        AuthModel::setDefaultPhpass($phpass);
    }
    
    /**
     * Sets up the email view paths for user module emails
     */
    public function _initEmail()
    {
        $front = $this->bootstrap('frontController')->getResource('frontController');
        $view = EmailService::getDefaultView();
        if ($view instanceof View) {
            $path = dirname($front->getModuleDirectory('user'));
            $view->setScriptPath($path . '/templates/email');
        }
    }
}