<?php
    // SHOP
    define('SHOP_IMG', 'https://image.freepik.com/free-vector/happy-shop-logo-template_57516-57.jpg');
    define('SHOP_NAME', 'WEBASSIGNMENT Shop');
    

    //  DATABASE  
    define('SERVER_NAME', 'v.je');
    define('DB_NAME', 'shop');
    define('DB_USER', 'v.je');
    define('DB_PASSWORD', 'v.je');

    //  ENVIRONMENT
    define('DEVELOP_FOLDER', '');
    define('URL_WEBSITE', 'https://v.je');
    define('PATH_IMAGE_UPLOAD',  getcwd() . "/views/public/image");
    define('DISPLAY_ERROR', TRUE);

    //  PAYPAL
    define('CLIENT_ID', 'AXVrN0Bt347zeWzsdILP8EtT9xSOZNcxIrRh5D-MwSnXA7C7aZv-FTev7k1yEGMoEv-qgKVJdDUep8Uk');
    define('CLIENT_SECRET', 'ENDHQErg0xYBiEvfiMCW0MDsotlf-aClToQQ89AnW54aYht7UC5zThb82TWQzVJOPdOBaJ2XGJGcI03H');
    
    $LOAD_CORE = [
        'helper', // new
        'blade_template',
        'session', // new
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