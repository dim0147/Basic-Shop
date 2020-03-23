<?php
    $routes = [];

    function removeParamURL($url){
        $getQueryP = strpos($url, '?');  //  check if have param
        if($getQueryP)
            $url = substr($url, 0, $getQueryP);    //  remove params
        return $url;
    }

    function route($url, Closure $callback){
        global $routes;
        $urlWeb = DEVELOP_FOLDER . $url;

        $url = trim($urlWeb, '/');  //  Remove / left and right
        $url = removeParamURL($url);
        $routes[$url] = $callback;
    }

    function dispathRoute($url){
        global $routes;
        $url = trim($url, '/');
        $url = removeParamURL($url);
        if(isset($routes[$url]))
            call_user_func($routes[$url]);
        else{
            global $blade;
            $PathPageNotFound = '404Page';
            echo $blade->run($PathPageNotFound, [
                'title' => "Opps! Not Found"
            ]);
        }
    }

?>