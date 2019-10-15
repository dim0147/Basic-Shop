<?php 
    class ProductModel extends database{

        function __construct(){
            $this->connect('product');
        }
    }
?>