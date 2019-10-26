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

    route('/admin/edit-product?{id}', function(){
        global $adminController;
        $adminController->editProduct();
    });

    route('/admin/post/edit-product/', function(){
        global $adminController;
        $adminController->postEditProduct();
    });

    route('/admin/post/login', function(){
        global $adminController;;
        $adminController->postLogin();
    });

    route('/admin/post/register', function(){
        global $adminController;;
        $adminController->postRegister();
    });

?>