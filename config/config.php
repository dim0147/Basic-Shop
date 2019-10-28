<?php
    define('SERVERNAME', 'localhost');
    define('DB_NAME', 'shop');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DEVELOP_FOLDER', 'WEBASSIGNMENT2');
    define('URL_WEBSITE', 'http://localhost:8888/WEBASSIGNMENT2');
    define('PATH_IMAGE_UPLOAD',  getcwd() . "/views/public/image");
    
    $LOAD_CORE = [
        'helper', // new
        'blade_template',
        'controller',
        'database',
        'route'
    ];

    $LOAD_MODEL = [
        'products',
        'users',

        'categorys',
        'cart'
    ];

    $LOAD_CONTROLLER = [
        'product',
        'user',
        'cart',
        'admin'
    ];

    $LOAD_ROUTE = [
        'product',
        'user',
        'cart',
        'admin'
    ];
?>