<?php
    class UserController extends Controller{
        
        function __construct(){
                $this->model = new UserModel();
                $this->fileRender = [
                    'index' => 'user.index'
                ];
        }

        public function index(){
            if($this->model != NULL){
                $users = $this->model->getAll();
                $this->render($this->fileRender['index'],
                 [
                     'users' => $users, 
                     'name' => "Jonh", 
                     'title' => "Hi" 
                ]);
            }
        }

        
        

    }
?>