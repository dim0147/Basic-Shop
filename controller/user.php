<?php
    class UserController extends Controller{
        
        function __construct(){
                $this->model = new UserModel();
        }

        function index(){
            if($this->model != NULL){
                $users = $this->model->getAll();
                $this->render('user.index', ['users' => $users, 'name' => "Jonh", 'title' => "Hi" ]);
            }
        }
        

    }
?>