<?php 

    $adminController = new AdminController();

    route('/admin/add-product', function(){
        global $adminController;
        $adminController->addProduct();
    });

    route('/admin/post/add-product', function(){
        global $adminController;
        $adminController->upload();
    });

?>