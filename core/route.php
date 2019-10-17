<?php
    $routes = [];

    function route($url, Closure $callback){
        global $routes;
<<<<<<< HEAD
        $url = trim(DEVELOP_PATH . $url, '/');
=======
        $url = trim(DEVELOP_FOLDER . $url, '/');
>>>>>>> d37d107912cf9e947efc5cdc395478113beb2b7a
        $routes[$url] = $callback;
        
    }

    function dispathRoute($url){
        global $routes;
        $url = trim($url, '/');
        if(isset($routes[$url]))
            call_user_func($routes[$url]);
    }

?>