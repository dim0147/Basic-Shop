<?php 
require __DIR__ . '/../vendor/autoload.php';
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Api\Amount;
use PayPal\Api\Address;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\PayerInfo;
use PayPal\Api\Presentation;
use PayPal\Api\WebProfile;

use Sample\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;

class CartController extends Controller{

        private $prodModel;
        private $userModel;
        private $apiContext;
        function __construct(){
            $this->model = new CartModel();
            $this->prodModel = new ProductModel();
            $this->userModel = new UserModel();
            $this->fileRender = [
                'checkout' => 'cart.checkout'
            ];
            $this->apiContext = new ApiContext(
                new OAuthTokenCredential(
                    CLIENT_ID,     // ClientID
                    CLIENT_SECRET     // ClientSecret
                )
            );
        }

        public function itemInCart($cart, $idItem){ //  Check item exist in cart
            foreach($cart['items'] as $key => $item){   
                if($item['product_id'] == $idItem){
                    return $key;
                }
            }
            return false;
        }

        public function getAdditionInfoProd($listItem){ //  Get addition field for product(id)
                //  Set up condition
            $condition = [
                'products.title' => []
            ];
                //  Loop through list item
            foreach($listItem as $item){
                    //  Push title of item to condition array
                array_push($condition['products.title'], $item['name']);
            }

                //  Get addition info(id) of product
            $info = $this->prodModel->selectMany(['id', 'title'], null, $condition);
                //  Loop through list item
            foreach($listItem as $key => $item){
                    //  Get name of item
                $titleProd = $item['name'];
                    //  Loop through addition info result
                foreach($info as $element){
                        //  If name of item equal name of addition info merge two array
                    if ($titleProd == $element['title'])
                        $listItem[$key] = array_merge_recursive($listItem[$key], $element);
                }
                    //   Don't have ID of the product in bill or more then 1 ID
                if(empty($listItem[$key]['id']) || is_array($listItem[$key]['id'])){
                    return false;
                }
                
            }
            return $listItem;
        }

    
            //  CART
        public function action(){
            if(empty($_SESSION['cart']) || empty($_POST['action'])){
                setHTTPCode(500, "Invalid cart or action");
                return;
            }
            
            switch($_POST['action']){
                case "add":
                    $this->addToCart();
                    break;
                case "decrease":
                    $this->decreaseProd();
                    break;
                case "remove":
                    $this->removeProd(NULL);
                    break;
                case "show":
                    $this->showCart();
                    break;
                default:
                    setHTTPCode(500, "Invalid action");
            }
        }

        public function addToCart(){    //  Action Add
                //  Check empty field
            if (empty($_SESSION['cart']) || empty($_POST['id']) || empty($_POST['quantity'])){
                setHTTPCode(500, "Invalid cart or ID or quantity");
                return;
            }
            
                //  Get cart
            $cart = $_SESSION['cart'];

                //  Check item in cart already or not
            $checkItem = $this->itemInCart($cart, $_POST['id']);

                //  Check if item have already in cart then increase product
            if($checkItem || $checkItem === 0){
                $this->increaseProd($checkItem, $_POST['quantity']); //  Check if item exist on cart
                return;
            }

                //  If not add new product
            $this->addProdToCart();
            return;
        }

        public function addProdToCart(){    //  Add new Product to cart

                //  Get product
            $prod = $this->prodModel->getProductWithId($_POST['id'], ['products.price', 'products.title']);
            if(!$prod){ //  If not found
                setHTTPCode(500, "Product not found!");
                return;
            }
            
                //  Get cart
            $cart = $_SESSION['cart'];

                //  Create new item
            $item = [   
                'product_id' => $_POST['id'],
                'quantity' => $_POST['quantity'],
                'title' => $prod[0]['title'],
                'price' => $prod[0]['price'],
                'priceTotal' => (float)$prod[0]['price'] * (int)$_POST['quantity']
            ];

                //  Push new item to items field
            array_push($cart['items'], $item);  

                //  Edit total
            $cart['totalPrice'] += $item['priceTotal'];
            $cart['totalQty'] += $item['quantity'];

                //  Change session cart
            $_SESSION['cart'] = $cart;

                //  Success
            setHTTPCode(200, "Add product success!");
            return;
        }

        public function increaseProd($key, $qTy){   //  Increase quantity 
                //  Get cart
            $cart = $_SESSION['cart'];

                //  Calculate price to increase, $key is identify which product to increase
            $priceToInc = $cart['items'][$key]['price'] * $qTy;

                //  Edit item in list items
            $cart['items'][$key]['quantity'] += $qTy; 
            $cart['items'][$key]['priceTotal'] += $priceToInc; 

                //  Edit total
            $cart['totalPrice'] += $priceToInc;
            $cart['totalQty'] += $qTy;

                //  Change session cart
            $_SESSION['cart'] = $cart;

            setHTTPCode(200, "Increase Product success!");
            return;
        }

        public function decreaseProd(){ //  Decrease quantity
                //  Check empty field
            if (empty($_SESSION['cart']['items']) || empty($_POST['id']) || empty($_POST['quantity'])){
                setHTTPCode(500, "Invalid cart or ID or quantity");
                return;
            }

                //  Check item exist, return key of product to decrease if true
            $checkItem = $this->itemInCart($_SESSION['cart'], $_POST['id']);   
                 //  If not exist 
            if($checkItem === false){
                setHTTPCode(500, "Product not found!");
                return;
            }
                    //  Get cart
                $cart = $_SESSION['cart'];

                    //   Key of item in items list
                $key = $checkItem;

                    //  Quantity to decrease
                $qTy = (int)$_POST['quantity'];

                    //  Price to decrease
                $priceDec = $cart['items'][$key]['price'] * $qTy;    

                    //  If decrease quantity below or equal 0
                if( $cart['items'][$key]['quantity'] - $qTy <= 0 ){
                    $this->removeProd($key);    //  Remove product
                    return;
                }

                    //  Edit item in list
                $cart['items'][$key]['quantity'] -= $qTy;
                $cart['items'][$key]['priceTotal'] -= $priceDec;

                    // Edit total
                $cart['totalPrice'] -= $priceDec;
                $cart['totalQty'] -= $qTy;

                    // Update cart
                $_SESSION['cart'] = $cart;

                setHTTPCode(200, "Decrease success");
                return;
        }
        
        public function removeProd($keyItem){   //  Remove product
                //  Check if empty
            if (empty($_SESSION['cart']['items']) || empty($_POST['id'])){
                setHTTPCode(500, "Invalid cart or ID");
                return;
            }
                //  If not pass parameter searching item in list items
            if ($keyItem == NULL)   
                $keyItem = $this->itemInCart($_SESSION['cart'], $_POST['id']);
            if($keyItem === false){  // If not found
                setHTTPCode(500, "Product not exist");
                return;
            }
                //  Get cart
            $cart = $_SESSION['cart'];
                //  Get price of item
            $priceDec = $cart['items'][$keyItem]['priceTotal']; 
                //  Get quantity of item
            $qTy = $cart['items'][$keyItem]['quantity'];
                //  Edit total
            $cart['totalPrice'] -= $priceDec;
            $cart['totalQty'] -= $qTy;
                //  Remove item from list item
            unset($cart['items'][$keyItem]);
                //  Update cart
            $_SESSION['cart'] = $cart;
            
            setHTTPCode(200, "Remove success");
            return;
        
        }

        public function showCart(){ //  Showing cart
            if(!empty($_SESSION['cart'])){
                printB($_SESSION['cart']);
                setHTTPCode(200);
                return;
            }
            setHTTPCode(500, 'Not Found!');
        }


            //  CHECKOUT
        public function checkout(){
            $this->render($this->fileRender['checkout'], [
                'title' => 'Checkout'
            ]);
            return;
        }

        public function postCheckout(){ //  Request for  make charge 
                //  Check if empty cart or user
            if(empty($_SESSION['cart']['items']) || empty($_SESSION['user'])){
                setHTTPCode(400, "Empty cart or user");
                return;
            }   
                //  Create a payment
            $this->createPayment();
        }

        public function createPayment(){ //  Set up and create payment then generate link for user payment
                //  Check empty field
            if(empty($_SESSION['cart']['items']) || empty($_POST['address']) || empty($_POST['phone']) || empty($_POST['email'])){
                setHTTPCode(500, 'Require Field is empty!!');
                return;
            }
                //  Set user information
            $addressPayer = new Address();
            $addressPayer->setLine1($_POST['address'])
                         ->setPhone($_POST['phone'])  
                         ->setCity('Singapore')  
                         ->setCountryCode('SG') 
                         ->setPostalCode('02')
                         ->setLine2($_POST['address'])
                         ->setPhone( $_POST['phone']); 

                //  Set payer info
            $payerInf = new PayerInfo();
            $payerInf->setEmail($_POST['email'])
                     ->setFirstName($_SESSION['user'])
                     ->setBillingAddress($addressPayer);

                //  Create payer, here is paypal account for testing 
            $payer = new Payer();
            $payer->setPaymentMethod('paypal')
                  ->setPayerInfo($payerInf);

                //  Initilize List Item from cart session
            $listItem = [];
            foreach($_SESSION['cart']['items'] as $item){
                $item1 = new Item();
                $item1->setName($item['title'])
                    ->setCurrency('USD')
                    ->setQuantity($item['quantity'])
                    ->setPrice($item['price']);
                $listItem[] = $item1;
            }
            $listItemPaypal = new ItemList();
            $listItemPaypal->setItems($listItem);

                //  Ship fee and subtotal
            $shipFee = 10.5;
            $detail = new Details();
            $detail->setShipping($shipFee)
                   ->setSubtotal($_SESSION['cart']['totalPrice']);
            
                //  Amount to charge
            $amount = new Amount();
            $amount->setTotal($_SESSION['cart']['totalPrice'] + $shipFee)
                    ->setCurrency('USD')
                    ->setDetails($detail);

                //  Set contract for payment
            $transaction = new Transaction();
            $transaction->setAmount($amount);
            $transaction->setItemList($listItemPaypal);

                // Set URL if user cancel or finish charge
            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl(URL_WEBSITE . '/cart/checkout/success') //  success
                         ->setCancelUrl(URL_WEBSITE . '/product');   //  cancel

                // Create new Payment
            $payment = new Payment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions(array($transaction))
                ->setRedirectUrls($redirectUrls);

                // Notice from user
            if(!empty($_POST['notice']))
                $payment->setNoteToPayer($_POST['notice']);
                
            try {
                    //  Execute payment with apiContext setting
                $payment->create($this->apiContext);
                    //  Redirect user to payment link
                header("Location: ". $payment->getApprovalLink());
            }
            catch (\PayPal\Exception\PayPalConnectionException $ex) {
                    //  Get text error
                echo $ex->getData();
                die($ex);
            }
        }

        public function successCheckoutRender(){    //  Checkout success, create Order save to DB
            if(empty($_SESSION['user'])){
                setHTTPCode(500, "User not found, something go wrong!!!");
                return;
            }

            if(empty($_GET['paymentId']) || empty($_GET['PayerID'])){
                setHTTPCode(500, "Something Wrong, You should not be in here!");
                return;
            }

                // Get payment ID from redirect url
            $paymentId = $_GET['paymentId'];
                //  Check if order have already exist by check paymentID
            $checkOrderExist = $this->prodModel->select(NULL, ['paymentID' => $paymentId], 'orders');
            if($checkOrderExist){   //  If exist
                setHTTPCode(500, "Order already create!!");
                return;
            }
                //  Get payment detail by paymentID get from above
            $payment = Payment::get($paymentId, $this->apiContext);

                // Create execute payment, pass PayerID get from redirect url 
            $execution = new PaymentExecution();
            $idPayer = $_GET['PayerID'];
            $execution->setPayerId($idPayer);

            try {
                // Execute payment with execution above
            $result = $payment->execute($execution, $this->apiContext);
            $result = $result->toArray();   //  Convert result to array

                //  Check if list item is empty
            if(empty($result['transactions'][0]['item_list']['items'])){
                setHTTPCode(500, "List item in bill is empty!!!");
                return;
            }

                //  Get item in the bill
            $items = $result['transactions'][0]['item_list']['items'];

                //  Get additional info of product (id)
            $items = $this->getAdditionInfoProd($items);
            if(!$items){    //  If error
                setHTTPCode(400, "Error, some products is invalid!");
                return;
            }

                //  Get user ID
            $userID = $this->userModel->select(['user_id'], ['user_id' => $_SESSION['user']]);
            if(!$userID){   //   If not have
                setHTTPCode(500, 'Something wrong, User not found!!!');
                return;
            }
            $userID = $userID[getFirstKey($userID)]['user_id'];

                //  Create order with User ID and payment detail get from above
            $orderID = $this->createOrderToDB($result, $userID);
            if($orderID === false){ //  If create fail
                    setHTTPCode(500, "Error while create Order!!");
                    return;
            }

                //  Create cart for order
            $createCart = $this->createCartsToDB($orderID, $userID, $items);
            if(!$createCart){
                setHTTPCode(400, "Error while create cart!");
                return;
            }

                //  Success
            setHTTPCode(200, "Success!");
            }
            catch (Exception $ex) {
                die($ex);
            }
            
        }
        
        public function createOrderToDB($payment, $userID){ //  Create new Order to DB
                //  Check if field is not have
            if(!isset($payment['transactions'][0]['item_list']['shipping_address'], $payment['payer']['payer_info']['email'], $payment['state'], $payment['id']))
                return false;

                //  Get address
            $address = $payment['transactions'][0]['item_list']['shipping_address'];
            $address = $address['line1'] . ', ' . $address['city'];
                // Get email  
            $email = $payment['payer']['payer_info']['email'];
                // Get state
            $status = $payment['state'];
                // Get paymentID
            $paymentID = $payment['id'];

                //  Set up value to insert new orders to DB
            $values = [[$userID, $address, $email, $status, $paymentID]];
            $column = ['user_id', 'address', 'email', 'status', 'paymentID'];
            $this->prodModel->insert($values, $column, 'orders');

                //  Get ID of order just create and return
            $orderID = $this->prodModel->pdo->lastInsertId();
            return $orderID;
        }

        public function createCartsToDB($orderID, $userID, $cart){ // Create cart and cart_item to DB
            if(empty($cart) || empty($orderID) || empty($userID))
                return false;
            
                //  Set up value for insert cart to DB
            $values = [[$userID, $orderID]];
            $column = ['user_id', 'order_id'];
            $this->prodModel->insert($values, $column, 'cart');

                //  Get cart id just insert before
            $cartID = $this->prodModel->pdo->lastInsertId();

                //  Initilize cart_item
            $cartItemVal = [];
            foreach($cart as $item){
                    //  Add item to array
                $cartItemVal[] = [$cartID, $item['id'], $item['quantity']];
            }
                //  Insert list item to DB
            $this->prodModel->insert($cartItemVal, NULL, 'cart_item');
            return true;
        }    
}

?>