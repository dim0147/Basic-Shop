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

    route('/user/register', function(){
        global $userController;
        $userController->registerIndex();
    });

    route('/user/post/register', function(){
        global $userController;
        $userController->postRegister();
    });
    

    route('/user/profile', function(){
        global $userController;
        $userController->showProfile();
    });

    route('/user/change-password', function(){
        global $userController;
        $userController->changePassword();
    });

    route('/user/post/change-password', function(){
        global $userController;
        $userController->postChangePassword();
    });

    
    route('/user/logout', function(){
        global $userController;
        $userController->logOut();
    });
?>