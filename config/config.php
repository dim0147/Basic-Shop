<?php
    define('SERVERNAME', 'localhost');
    define('DB_NAME', 'shop');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DEVELOP_FOLDER', 'WEBASSIGNMENT2');
    
    $LOAD_CORE = [
        'helper',
        'blade_template',
        'session',
        'controller',
        'database',
        'route'
    ];

    $LOAD_MODEL = [
        'products',
        'users',
        'categorys',
        'categorys_link_products',
        'images',
        'cart'
    ];

    $LOAD_CONTROLLER = [
        'product',
        'user',
        'api/cart'
    ];

    $LOAD_ROUTE = [
        'product',
        'user',
        'api'
    ];
?>