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
    ),
    'register' => array(
        'route' => 'signup/*',
        'defaults' => array(
            'module' => 'user',
            'controller' => 'account',
            'action' => 'register'
        )
    ),
    'verify' => array(
        'route' => 'verify/:id/:key',
        'defaults' => array(
            'module' => 'user',
            'controller' => 'profile',
            'action' => 'verify-email'
        )
    ),
    'lobby' => array(
        'route' => 'lobby/:key',
        'defaults' => array(
            'module' => 'game',
            'controller' => 'lobby',
            'action' => 'view'
        )
    )
);