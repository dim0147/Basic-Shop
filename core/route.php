<?php
    $routes = [];

    function route($url, Closure $callback){
        global $routes;
        $url = trim(DEVELOP_FOLDER . $url, '/');
        //echo $url; // WEBASSIGNMENT2/productWEBASSIGNMENT2/userawd 
        $routes[$url] = $callback;
    }

    function dispathRoute($url){
        global $routes;
        $url = trim($url, '/');
        // echo $url; //awdWEBASSIGNMENT2/product
        if(isset($routes[$url]))    //isset — Determine if a variable is declared and is different than NULL
            call_user_func($routes[$url]);
    }
    /*
        call_user_func(
    */
?>