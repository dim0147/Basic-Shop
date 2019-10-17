<?php
    define('SERVERNAME', 'localhost');
    define('DB_NAME', 'shop');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DEVELOP_FOLDER', 'MVCProject');
    
    $LOAD_CORE = [
        'helper',
        'blade_template',
        'controller',
        'database',
        'route'
    ];

    $LOAD_MODEL = [
        'products',
        'users',
        'categorys',
        'categorys_link_products',
        'images'
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