<?php

$config = require 'production.php';

$config['phpSettings']['display_startup_errors'] = true;
$config['phpSettings']['display_errors']         = true;

$config['resources']['doctrine']['connection']['user']     = 'root';
$config['resources']['doctrine']['connection']['password'] = 'root';

$config['resources']['mail']['transport'] = array('type' => 'sendmail');
$config['siteUrl'] = 'http://cards.epixa';

return $config;