<?php
    $userController = new UserController();
    
    route('/user', function(){
        global $userController;
        $userController->index();
    });

    route('/user/post/login', function(){
        global $userController;
        $userController->postLogin();
    });

    route('/user/post/register', function(){
        global $userController;
        $userController->postRegister();
    })

    
?>