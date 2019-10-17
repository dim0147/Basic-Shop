<?php
    define('SERVERNAME', 'localhost');
    define('DB_NAME', 'shop');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DEVELOP_PATH', 'WEBASSIGNMENT2');
    
    $LOAD_CORE = [
        'blade_template',
        'controller',
        'database',
        'route'
    ];

    $LOAD_MODEL = [
        'product',
        'user'
    ];

    $LOAD_CONTROLLER = [
        'product',
        'user'
    ];

    $LOAD_ROUTE = [
        'product',
        'user'
    ];
?>