<?php
    $userController = new UserController();
    
    route('/user', function(){
        global $userController;
        $userController->index();
    });
    
?>