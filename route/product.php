<?php
    $productController = new ProductController();
    
    route('/product', function(){
        global $productController;
        $productController->index();
    });
    
?>