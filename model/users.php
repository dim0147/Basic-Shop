<?php
    class UserModel extends database{
        function __construct(){
            $this->connect('users');
        }

    }
?>