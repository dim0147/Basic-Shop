<?php 
    class ImageModel extends database{

        function __construct(){
            $this->connect('images');
        }

    }

?>