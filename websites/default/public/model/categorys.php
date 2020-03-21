<?php 
    class CategoryModel extends database{

        function __construct(){
            $this->connect('categorys');
        }

    }
?>