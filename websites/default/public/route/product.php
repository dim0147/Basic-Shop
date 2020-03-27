<?php
    $productController = new ProductController();
    

    route('/product/detail?{id}', function(){
        global $productController;
        $productController->detail();
    });

    route('/product/search/string?{s}', function(){
        global $productController;
        $productController->searchProductByString();
    });

    route('/product/search/category?{s}', function(){
        global $productController;
        $productController->searchProductByCategory();
    });
    
    route('/product', function(){
        global $productController;
        $productController->index();
    });
    
?>