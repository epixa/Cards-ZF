<?php

return array(
    'phpSettings' => array(
        'display_startup_errors' => false,
        'display_errors' => false,
        'date' => array(
            'timezone' => 'America/New_York'
        )
    ),
    'bootstrap' => array(
        'path' => APPLICATION_PATH . '/Bootstrap.php'
    ),
    'autoloadernamespaces' => array(
        'Doctrine\\'
    ),
    'resources' => array(
        'frontController' => array(
            'moduleDirectory' => APPLICATION_PATH,
            'env' => APPLICATION_ENV,
            'actionHelperPaths' => array(
                'Epixa\\Controller\\Helper\\' => 'Epixa/Controller/Helper'
            )
        ),
        'doctrine' => array(
            //'loggerClass' => 'Doctrine\\DBAL\\Logging\\EchoSQLLogger',
            'proxy' => array(
                'directory' => APPLICATION_ROOT . '/data/proxies'
            ),
            'connection'   => array(
                'driver'   => 'pdo_mysql',
                'host'     => 'localhost',
                'dbname'   => 'cardsdb',
                'user'     => defined('DB_USERNAME') ? DB_USERNAME : null,
                'password' => defined('DB_PASSWORD') ? DB_PASSWORD : null
            )
        ),
        'modules' => array(),
        'router' => array(
            'file' => APPLICATION_ROOT . '/config/routes.php'
        ),
        'mail' => array(
            'transport' => array(
                'type'     => 'smtp',
                'host'     => defined('SMTP_HOSTNAME') ? SMTP_HOSTNAME : null,
                'auth'     => 'login',
                'username' => defined('SMTP_USERNAME') ? SMTP_USERNAME : null,
                'password' => defined('SMTP_PASSWORD') ? SMTP_PASSWORD : null,
                'ssl'      => 'ssl',
                'port'     => '465'
            ),
            'defaultFrom' => array(
                'email' => 'cards@epixa.com',
                'name'  => 'Card games!'
            )
        ),
        'view' => array(),
        'layout' => array(
            'layoutPath' => APPLICATION_ROOT . '/layouts',
            'layout' => 'default'
        )
    ),
    'siteUrl' => defined('SITE_URL') ? SITE_URL : 'http://cards.epixa.com'
);