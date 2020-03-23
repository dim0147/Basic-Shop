<?php
    $userController = new UserController();
    
    route('/user', function(){
        global $userController;
        $userController->index();
    });

    route('/user/login', function(){
        global $userController;
        $userController->loginIndex();
    });

    route('/user/post/login', function(){
        global $userController;
        $userController->postLogin();
    });

    route('/user/post/register', function(){
        global $userController;
        $userController->postRegister();
    });

    route('/user/orders', function(){
        global $userController;
        $userController->showOrders();
    });

    
    route('/user/logout', function(){
        global $userController;
        $userController->logOut();
    });
?>