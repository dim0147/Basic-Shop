<?php
    class UserController extends Controller{
        public $cartModel;

        function __construct(){
                $this->model = new UserModel();
                $this->cartModel = new CartModel();
                $this->fileRender = [
                    'index' => 'user.index',
                    'login' => 'user.login',
                    'register' => 'user.register',
                    'profile' => 'user.profile',
                    'change-password' => 'user.change-password'              
                ];
        }
            //  Get Profile
        public function index(){
                //  Check user
            if(empty($_SESSION['user'])){
                setHTTPCode(400, "User not found!");
                redirectBut();
                return;
            }

                //  Get Profile user, include orders
            $user = $this->model->select(['user_id', 'username', 'name'], ['user_id' => $_SESSION['user'],
                'type' => 'user']);
            if(!$user){ //  if error
                setHTTPCode(500, "Error, cannot find user!");
                redirectBut();
                return;
            }

                //  Get Order of user
            $orders = [];
            $infOrder = $this->model->getOrderUser($_SESSION['user']);
                //  If Order exist
            if($infOrder){
                    //  Merge result, title of product merge to listProduct, make id product as a key,
                    //  title is value of that key, identify by order ID
                    //  Example :
                    //  [1] => "Game Of Throne" (1 is id of product)
                $orders = mergeResult(['title'], ['listProduct'], 'order_id', $infOrder, ['title' => 'prodID']);

                    //  Merge category , image product, identify by productID
                $addInfo = mergeResult(['category_name', 'image'], ['listCategory', 'listImg'], 'prodID', $infOrder);

                    //  Loop through all Order
                foreach($orders as $key => $order){

                        //  Loop through list product of single order
                    foreach($order['listProduct'] as $idProd => $title){ 
                        
                            //  Then loop on addition value for product ( category and image )
                        foreach($addInfo as $value){

                                //  If  product id of addition value equal to product id of listProduct from order   
                            if($value['prodID'] == $idProd){
                                    //  create new element include category, image of this product
                                $element = array(
                                    'title' => $title,
                                    'category' => $value['listCategory'],
                                    'image' => $value['listImg'][0]
                                );
                                    //  Assign product id element in listProduct of order equal that element(include title, category, image)
                                $orders[$key]['listProduct'][$idProd] = $element;
                                break;
                            }
                        }                
                    }
                }
            }
                //  Render page  
            $this->render($this->fileRender['index'],
                [
                    'users' => $user,
                    'orders' => $orders, 
                    'title' => "Hi" 
                ]);
        }

        public function loginIndex(){
            if(!empty($_SESSION['user'])){
                setHTTPCode(500, "You login already!");
                redirectBut();
                return;
            }
            $this->render($this->fileRender['login'], ['title' => "Login"]);
        }

        public function postLogin(){ 
            if(empty($_POST['username']) || empty($_POST['password']) || !empty($_SESSION['user'])){ 
                setHTTPCode(500, "Something wrong, please check again!");
                redirectBut();
                return;
            }
                    //  Get username and password
                $username = $_POST['username'];
                $password = $_POST['password'];

                    //  Get user through username
                $user = $this->model->select(NULL, ["username" => $username,
                                                    "type" => 'user']);

                    // Check if user exist and password verify success
                $result = isset($user[getFirstKey($user)]['password']);
                if($result && password_verify($password, $user[getFirstKey($user)]['password'])){
                        //  Assign user session with user id
                    $_SESSION['user'] = $user[getFirstKey($user)]['user_id'];
                    $_SESSION['username'] = $user[getFirstKey($user)]['name'];
                    setHTTPCode(200, "Sign In Success!");
                    redirectBut();
                    return;
                }
                else{   //  Verify password fail
                    setHTTPCode(500, "Username or Password Wrong!");
                    redirectBut('/user/login', 'Click here to login again!');
                    return;
                }
            
        }

        public function registerIndex(){
            if(!empty($_SESSION['user'])){
                setHTTPCode(500, 'You login already!'); 
                redirectBut();
                return;
            }
            $this->render($this->fileRender['register'], ['title' => 'Register']);
        }

        public function postRegister(){
                //  Check if not empty
            if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['name']) || !empty($_SESSION['user'])){
                setHTTPCode(500, "Field empty or user already Login!!");
                return;
            }
                    //  Assign variable
                $username = $_POST['username'];
                $password = $_POST['password'];
                $name = $_POST['name'];

                    //  Check if username is exist
                $checkExist = $this->model->select(NULL,['username' => $username,
                                                        'type' => 'user']); 
                if($checkExist){ //  If yes return   
                    setHTTPCode(500, "Username exist!");
                    redirectBut('/user/register', 'Click here to register again');
                    return;
                }

                    // Insert new User
                $password = password_hash($password, PASSWORD_DEFAULT); //  hash password before insert
                $column = ['username', 'password', 'name', 'status', 'type'];   //  column to insert
                $values = [[$username, $password, $name, 'Active', 'user']];    //  value to insert
                $this->model->insert($values, $column);
                $idUser = $this->model->getLastInsertId();
                $_SESSION['user'] = $idUser;
                $_SESSION['username'] = $_POST['name'];
                setHTTPCode(200, "Register success!! ");
                redirectBut();
                return;
        }

        public function showProfile(){
            if (empty($_SESSION['user'])){
                setHTTPCode(400, 'Please login first!');
                redirectBut('/user/login', 'Click here to login');
                return;
            }
            $orders = $this->cartModel->getSpecificCart($_SESSION['user']);
            $user = $this->model->select(NULL, ['user_id' => $_SESSION['user']]);
            if(empty($user)){
                setHTTPCode("Cannot get profile user, please logout and login again!");
                redirectBut('/user/logout', 'Click here to logout');
                return;
            }
            $user = $user[getFirstKey($user)];
            $this->render($this->fileRender['profile'], ['title' => 'Orders Detail', 'orders' => $orders, 'user' => $user]);
            // printB($orders);
            
        }

        public function changePassword(){
            $this->render($this->fileRender['change-password'], ['title' => 'Change Password']);
        }

        public function postChangePassword(){
            if(empty($_SESSION['user'])){
                setHTTPCode(500, "Please login first!");
                redirectBut('/user/login', 'Click here to login');
                return;
            }

            if(empty($_POST['old-password']) || empty($_POST['new-password']) ||  empty($_POST['confirm-new-password']) || empty($_SESSION['user'])){ 
                setHTTPCode(500, "Something wrong, please check again!");
                redirectBut('/user/change-password', 'Click here to change password');
                return;
            }
            if($_POST['new-password'] != $_POST['confirm-new-password']){
                setHTTPCode(500, "Confirm password wrong!");
                redirectBut('/user/change-password', 'Click here to change password');
                return;
            }

            //  Get user through username
            $user = $this->model->select(NULL, ["user_id" => $_SESSION['user'],
                                                "type" => 'user']);
            if(empty($user)){
                setHTTPCode(500, "Cannot find user, please logout and login again");
                redirectBut('/user/login', 'Click here to login again');
                return;
            }

            $user = $user[getFirstKey($user)];
            if(password_verify($_POST['old-password'], $user['password'])){
                $newPassword = password_hash($_POST['new-password'], PASSWORD_DEFAULT); //  hash password before update
                $this->model->update(['password' => $newPassword], ['user_id' => $_SESSION['user']]);
                setHTTPCode(200, "Change password success!");
                redirectBut();
                return;
            }
            else{
                setHTTPCode(500, "Old password not correct!");
                redirectBut('/user/change-password', 'Click here to change password');
                return;
            }

        }

        function logOut(){
            session_destroy();
            echo "Logout success";
            redirectBut();
        }
                
    }
?>