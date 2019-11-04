<?php
    class UserController extends Controller{
        
        function __construct(){
                $this->model = new UserModel();
                $this->fileRender = [
                    'index' => 'user.index',
                    'login' => 'user.login'
                ];
        }

        public function index(){
            if($this->model != NULL){
                //  Get Profile user, include orders
                $info = $this->model->getProfile();
                if(!$info){
                    setHTTPCode(500, "Error, cannot find user!");
                    return;
                }
                //  Merge result, title of product merge to listProduct, make id product is a key,
                //  title is value of that key
                //  Example :
                //  [1] => "Game Of Throne" (1 is id of product)
                $user = mergeResult(['title'], ['listProduct'], 'order_id', $info, ['title' => 'prodID']);
                $addInfo = mergeResult(['category_name', 'image'], ['listCategory', 'listImg'], 'prodID', $info);

                foreach($user as $key => $order){
                    foreach($order['listProduct'] as $idProd => $title){ 
                        $user[$key]['listProduct'][$idProd] = array(
                            'title' => $title,
                            'category' => [],
                            'image' => ''
                        );   
                        foreach($addInfo as $value){
                            if($value['prodID'] == $idProd){
                                $element = array(
                                    'title' => $title,
                                    'category' => $value['listCategory'],
                                    'image' => $value['listImg'][0]
                                );
                                $user[$key]['listProduct'][$idProd] = $element;
                                break;
                            }
                        }                
                    }
                }
                 printB($user);
                // $this->render($this->fileRender['index'],
                //  [
                //      'users' => $users, 
                //      'name' => "Jonh", 
                //      'title' => "Hi" 
                // ]);
            }
        }

        public function loginIndex(){
            $this->render($this->fileRender['login'], ['title' => "Login"]);
        }

        public function postLogin(){
            if(!empty($_POST['username']) && !empty($_POST['password']) && empty($_SESSION['user'])){ 
                $username = $_POST['username'];
                $password = $_POST['password'];
                $passwordQuery = $this->model->select(NULL, ["username" => $username,
                                                                "type" => 'user']);
                $result = isset($passwordQuery[getFirstKey($passwordQuery)]['password']);
                echo " dm ";
                printB($result);
                if($result && password_verify($password, $passwordQuery[getFirstKey($passwordQuery)]['password'])){
                    $_SESSION['user'] = $passwordQuery[getFirstKey($passwordQuery)]['user_id'];
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
            if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['name']) && !isset($_SESSION['user'])){
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
                setHTTPCode(500, "Field empty or user already Login!!");
        }

    }
?>