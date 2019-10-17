<?php
    $routes = [];

    function route($url, Closure $callback){
        global $routes;
        $url = trim(DEVELOP_PATH . $url, '/');
        $routes[$url] = $callback;
        
    }

    function dispathRoute($url){
        global $routes;
        $url = trim($url, '/');
        if(isset($routes[$url]))
            call_user_func($routes[$url]);
    }

?>