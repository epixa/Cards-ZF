<?php
/**
 * Epixa - Cards
 */

use Epixa\Application\Bootstrap as BaseBootstrap,
    Epixa\Service\AbstractDoctrineService as DoctrineService,
    Epixa\Service\AbstractEmailService as EmailService,
    Zend_Mail as Mailer,
    Zend_Config as Config,
    Zend_View as View;

/**
 * Bootstrap the application
 *
 * @category  Bootstrap
 * @copyright 2011 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Cards/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class Bootstrap extends BaseBootstrap
{
    /**
     * Sets the default entity manager for doctrine services
     */
    public function _initDoctrineService()
    {
        $em = $this->bootstrap('doctrine')->getResource('doctrine');
        DoctrineService::setDefaultEntityManager($em);
    }

    /**
     * Sets the site url on the view
     */
    public function _initSiteUrl()
    {
        $view = $this->bootstrap('view')->getResource('view');

        $view->siteUrl = $this->getOption('siteUrl');
    }
    
    /**
     * Sets the default mailer for email services
     */
    public function _initEmailService()
    {
        $this->bootstrap('mail');
        $view = $this->bootstrap('view')->getResource('view');

        $mailer = new Mailer('UTF-8');

        $config = array();
        $options = $this->getOption('resources');
        if (isset($options['mail']['defaultFrom']['email'])) {
            $config['email'] = $options['mail']['defaultFrom']['email'];
        }

        if (isset($options['mail']['defaultFrom']['name'])) {
            $config['name'] = $options['mail']['defaultFrom']['name'];
        }

        $config = new Config($config);
        $view->emailConfig = $config;

        EmailService::setDefaultMailer($mailer);
        EmailService::setDefaultView($view);
    }
}