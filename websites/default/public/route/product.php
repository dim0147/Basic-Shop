<?php
    $productController = new ProductController();
    

    route('/product/detail?{id}', function(){
        global $productController;
        $productController->detail();
    });
    
    route('/product', function(){
        global $productController;
        $productController->index();
    });
    
?>