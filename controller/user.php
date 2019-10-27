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

        public function postLogin(){
            if(!empty($_POST['username']) && !empty($_POST['password']) && !isset($_SESSION['user'])){ 
                $username = $_POST['username'];
                $password = $_POST['password'];
                $passwordQuery = $this->model->select(NULL, ["username" => $username,
                                                                "type" => 'user']);
                $result = isset($passwordQuery[getFirstKey($passwordQuery)]['password']);
                if($result && password_verify($password, $passwordQuery[getFirstKey($passwordQuery)]['password'])){
                    $_SESSION['user'] = $passwordQuery[getFirstKey($passwordQuery)]['name'];
                    setHTTPCode(200, "Sign In Success!");
                    return;
                }
                else{
                    setHTTPCode(500, "Username or Password Wrong!");
                    return;
                }
            }
            setHTTPCode(500, "Something wrong, please check again!");
        }

        public function postRegister(){
            if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['name'] && !isset($_SESSION['user']))){
                //  Assign variable
                $username = $_POST['username'];
                $password = $_POST['password'];
                $name = $_POST['name'];

                //  Check if username is exist
                $checkExist = $this->model->select(NULL,['username' => $username,
                                                        'type' => 'user']); 
                if($checkExist){    //  If yes return
                    setHTTPCode(500, "Username exist!");
                    return;
                }

                // Insert new User
                $password = password_hash($password, PASSWORD_DEFAULT); //  hash password
                $values = createQuery(['DEFAULT', $username, $password, $name, 'Active', 'DEFAULT', 'user']);
                $this->model->insert($values);
                setHTTPCode(200, "Register success!!");
                return;
            }
            else
                setHTTPCode(500, "Field Empty!!");
        }

    }
?>