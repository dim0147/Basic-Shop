<?php
    class UserController extends Controller{
        
        function __construct(){
                $this->model = new UserModel();
                $this->fileRender = [
                    'index' => 'user.index',
                    'login' => 'user.login'
                ];
        }
            //  Get Profile
        public function index(){
                //  Check user
            if(empty($_SESSION['user'])){
                setHTTPCode(400, "User not found!");
                return;
            }

                //  Get Profile user, include orders
            $user = $this->model->select(['user_id', 'username', 'name'], ['user_id' => $_SESSION['user'],
                'type' => 'user']);
            if(!$user){ //  if error
                setHTTPCode(500, "Error, cannot find user!");
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
            $this->render($this->fileRender['login'], ['title' => "Login"]);
        }

        public function postLogin(){ 
           // session_destroy();
            /*if(!empty($_SESSION['user'])){
                echo "user dang nhap roi dit me may " . $_SESSION['user'];
                session_destroy();
                //  Check if not empty
            }*/
            if(empty($_POST['username']) || empty($_POST['password']) || !empty($_SESSION['user'])){ 
                setHTTPCode(500, "Something wrong, please check again!");
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
                    setHTTPCode(200, "Sign In Success!");
                    return;
                }
                else{   //  Verify password fail
                    setHTTPCode(500, "Username or Password Wrong!");
                    return;
                }
            
        }

        public function postRegister(){
                //  Check if not empty
            if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['name']) || !empty($_SESSION['user'])){
                setHTTPCode(500, "Field empty or user already Login!!");
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
                    return;
                }

                    // Insert new User
                $password = password_hash($password, PASSWORD_DEFAULT); //  hash password before insert
                $column = ['username', 'password', 'name', 'status', 'type'];   //  column to insert
                $values = [[$username, $password, $name, 'Active', 'user']];    //  value to insert
                $this->model->insert($values, $column);
                setHTTPCode(200, "Register success!!");
                return;
        }

        function logOut(){
            $_SESSION['user'] = NULL;
        }
                
    }
?>