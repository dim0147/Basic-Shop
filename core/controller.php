<?php
    class Controller{
        protected $model = NULL;
        protected $PathPageNotFound = '404Page';

        function render($filename, Array $data){
            global $blade;
            echo $blade->run($filename, $data);
        }

        function renderNotFound(){
            global $blade;
            echo $blade->run($PathPageNotFound, [
                'title' => "Opps! Not Found"
            ]);
        }

    }
?>