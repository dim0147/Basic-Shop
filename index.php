<?php

    /* LOAD CONFIG */
    require_once('config/config.php');


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

    /* TRIGGERED ROUTE CALLBACK */
    dispathRoute($_SERVER['REQUEST_URI']);
?>