<?php
    define('SERVERNAME', 'localhost');
    define('DB_NAME', 'shop');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DEVELOP_FOLDER', 'MVCProject');
<<<<<<< HEAD
    
=======
	
	
>>>>>>> 24f4ad514435d6c76af2f4fe13a5eb28c4f05569
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