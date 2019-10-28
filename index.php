<?php

    /* LOAD CONFIG */
    require_once('config/config.php');
    if(!DISPLAY_ERROR)
        ini_set('display_errors', 0);
    /* LOAD CORE */ 
    foreach($LOAD_CORE as $core){
        require_once('core/'. $core . '.php');
    }

    /*  LOAD MODEL */
    foreach($LOAD_MODEL as $model){
        require_once('model/' . $model . '.php');
    }

    /* LOAD CONTROLLER */
    foreach($LOAD_CONTROLLER as $controller){
        require_once('controller/' . $controller . '.php');
    }

    /* LOAD ROUTE */
    foreach($LOAD_ROUTE as $route){
        require_once('route/' . $route . '.php');
    }
    function fatal_handler(){
        $error = error_get_last();
    // fatal error, E_ERROR === 1
    if ($error['type'] === E_ERROR) { 
        echo "Loi r ";
    } 
    }

    register_shutdown_function( "fatal_handler" );
    /* TRIGGERED ROUTE CALLBACK */
    dispathRoute($_SERVER['REQUEST_URI']);
?>