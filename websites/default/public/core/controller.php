<?php
    class Controller{
        protected $model = NULL;
        protected $PathPageNotFound = '404Page';
        protected $fileRender = NULL; 

        function render($filename, Array $data){
            global $blade;

            print($blade->run($filename, $data)); //http://localhost:8888/WEBASSIGNMENT2/product

            //printB($filename);
        }

        function renderNotFound(){
            global $blade;
            echo $blade->run($this->PathPageNotFound, [
                'title' => "Opps! Not Found"
            ]);
        }

    }
?>