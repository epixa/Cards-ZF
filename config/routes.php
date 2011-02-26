<?php

return array(
    'login' => array(
        'route' => 'login/*',
        'defaults' => array(
            'module' => 'user',
            'controller' => 'auth',
            'action' => 'login'
        ),
    ),
    'logout' => array(
        'route' => 'logout/*',
        'defaults' => array(
            'module' => 'user',
            'controller' => 'auth',
            'action' => 'logout'
        )
    )
);