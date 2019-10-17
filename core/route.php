<?php
    $routes = [];

    function route($url, Closure $callback){
        global $routes;
<<<<<<< HEAD
<<<<<<< HEAD
        $url = trim(DEVELOP_PATH . $url, '/');
=======
        $url = trim(DEVELOP_FOLDER . $url, '/');
>>>>>>> d37d107912cf9e947efc5cdc395478113beb2b7a
=======
        $url = trim(DEVELOP_FOLDER . $url, '/');
>>>>>>> 24f4ad514435d6c76af2f4fe13a5eb28c4f05569
        $routes[$url] = $callback;
        
    }

    function dispathRoute($url){
        global $routes;
        $url = trim($url, '/');
        if(isset($routes[$url]))
            call_user_func($routes[$url]);
    }

?>