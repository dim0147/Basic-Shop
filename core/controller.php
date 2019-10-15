<?php
    class Controller{
        protected $model = NULL;
        function render($filename, Array $data){
            global $blade;
            echo $blade->run($filename, $data);
        }

    }
?>