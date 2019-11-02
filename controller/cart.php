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

        public function checkout(){
            $this->render($this->fileRender['checkout'], [
                'title' => 'Checkout'
            ]);
            return;
        }

        public function postCheckout(){
            if(!empty($_SESSION['cart']['items']) && isset($_SESSION['user'])){
                $this->createPayment();
            }
            else{
                echo 'empty cart or user!';
            }
        }

        public function successCheckoutRender(){
            if(empty($_SESSION['user'])){
                setHTTPCode(500, "User not found, something go wrong!!!");
                return;
            }

            if(empty($_GET['paymentId'])){
                setHTTPCode(500, "Something Wrong!");
                return;
            }

            // Get payment ID from redirect url
            $paymentId = $_GET['paymentId'];
            //  Check if order have already exist by check paymentID
            $checkOrderExist = $this->prodModel->select(NULL, ['paymentID' => $paymentId], 'orders');
            if($checkOrderExist){
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
            if(empty($result['transactions'][0]['item_list']['items'])){
                setHTTPCode(500, "List item in bill is empty??!!!");
                return;
            }
            $items = $result['transactions'][0]['item_list']['items'];
            $items = $this->getIdAndMergeToProd($items);
            $userID = $this->userModel->select(['user_id'], ['name' => $_SESSION['user']]);
            if(!$userID){
                setHTTPCode(500, 'Something wrong!!!');
                return;
            }
            $userID = $userID[getFirstKey($userID)]['user_id'];
            $orderID = $this->createOrderToDB($result, $userID);
            if($orderID === false){
                    setHTTPCode(500, "Error while create Order!!");
                    return;
            }

            $this->createCartsToDB($orderID, $userID, $items);
            }
            catch (Exception $ex) {
                die($ex);
            }
            
        }

        public function getIdAndMergeToProd($listItem){
            $condition = [];
            foreach($listItem as $item){
                $condition[] ="'" . $item['name'] . "'";
            }
            $condition = "title IN(" . implode(', ', $condition) . ")";
            $result = $this->prodModel->getProdWithField(['id', 'title'], $condition);
            $arr = [];
            foreach($result as $value){
                $arr[$value['id']] = $value['title'];
            }
            foreach($listItem as $key => $item){
                $titleProd = $item['name'];
                foreach($arr as $id => $nameProd){
                    if ($titleProd == $nameProd){
                        $listItem[$key]['id'] = $id;
                    }
                }
            }
            return $listItem;
        }

        public function createPayment(){
            if(empty($_POST['address']) || empty($_POST['phone']) || empty($_POST['email'])){
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

            $payerInf = new PayerInfo();
            $payerInf->setEmail($_POST['email'])
                     ->setFirstName($_SESSION['user'])
                     ->setBillingAddress($addressPayer);

            //  Method payer, here is paypal account for testing later
            $payer = new Payer();
            $payer->setPaymentMethod('paypal')
                  ->setPayerInfo($payerInf);

            //  List Item
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

            //  Ship free and subtotal
            $shipFee = 10.5;
            $detail = new Details();
            $detail->setShipping($shipFee)
                   ->setSubtotal($_SESSION['cart']['totalPrice']);
            
            //  Amount Money
            $amount = new Amount();
            $amount->setTotal($_SESSION['cart']['totalPrice'] + $shipFee)
                    ->setCurrency('USD')
                    ->setDetails($detail);

            //  Set contract for payment
            $transaction = new Transaction();
            $transaction->setAmount($amount);
            $transaction->setItemList($listItemPaypal);

            // Set URL if user cancel or return
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
                

                // After Step 3
            try {
                $payment->create($this->apiContext);
                header($payment->getApprovalLink());
            }
            catch (\PayPal\Exception\PayPalConnectionException $ex) {
                // This will print the detailed information on the exception.
                //REALLY HELPFUL FOR DEBUGGING
                echo $ex->getData();
            }
        }

        public function createOrderToDB($payment, $userID){
            if(!isset($payment['transactions'][0]['item_list']['shipping_address'], $payment['payer']['payer_info']['email'], $payment['state'], $payment['id']))
                return false;

            $address = $payment['transactions'][0]['item_list']['shipping_address'];
            $address = $this->getAddressFromArr($address);
            $email = $payment['payer']['payer_info']['email'];
            $status = $payment['state'];
            $paymentID = $payment['id'];
            $values = createQuery([$userID, $address, 'NULL', $email, $status, $paymentID, 'DEFAULT']);
            $this->prodModel->insert($values, 'orders');
            $orderID = $this->prodModel->pdo->lastInsertId();
            return $orderID;
        }

        public function createCartsToDB($orderID, $userID, $cart){
            $cartVal = createQuery(['DEFAULT', $userID, $orderID]);
            $this->prodModel->insert($cartVal, 'cart');
            $cartID = $this->prodModel->pdo->lastInsertId();
            $cartItemVal = [];
            foreach($cart as $item){
                $cartItemVal[] = createQuery([$cartID, $item['id'], $item['quantity']]);
            }
            $cartItemVal = implode(', ',$cartItemVal);
            $this->prodModel->insert($cartItemVal, 'cart_item');
            
        }

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

        public function showCart(){
            if(!empty($_SESSION['cart'])){
                printB($_SESSION['cart']);
                setHTTPCode(200);
                return;
            }
            setHTTPCode(500, 'Not Found!');
        }

        public function removeProd($keyItem){
            if ( empty($_SESSION['cart']['items']) || empty($_POST['id']) ){
                setHTTPCode(500, "Invalid cart or ID");
                return;
            }
            if ($keyItem == NULL)   //  If not pass parameter searching item in list items
                $keyItem = $this->itemInCart($_SESSION['cart'], $_POST['id']);
            if($keyItem || $keyItem === 0){  
                $cart = $_SESSION['cart'];
                $priceDec = $cart['items'][$keyItem]['priceTotal']; 
                $qTy = $cart['items'][$keyItem]['quantity'];
                $cart['totalPrice'] -= $priceDec;
                $cart['totalQty'] -= $qTy;
                unset($cart['items'][$keyItem]);
                $_SESSION['cart'] = $cart;
                setHTTPCode(200, "Remove success");
                return;
            }
            setHTTPCode(500, "Product not exist");
            return;
        }

        // *** DECREASE QUANTITY PRODUCT  
        public function decreaseProd(){
            if (empty($_SESSION['cart']['items']) || empty($_POST['id']) || empty($_POST['quantity'])){
                setHTTPCode(500, "Invalid cart or ID or quantity");
                return;
            }

            $checkItem = $this->itemInCart($_SESSION['cart'], $_POST['id']);    //  Check item exist, return key if true
            if($checkItem || $checkItem === 0){ //  if exist
                $cart = $_SESSION['cart'];
                $key = $checkItem; //   Key of item in items list
                $qTy = (int)$_POST['quantity'];
                $priceDec = $cart['items'][$key]['price'] * $qTy;    //  price to decrease
                if( $cart['items'][$key]['quantity'] - $qTy <= 0 ){
                    $cart['totalPrice'] -= $cart['items'][$key]['priceTotal'];
                    $cart['totalQty'] -= $cart['items'][$key]['quantity'];
                    $this->removeProd($key);
                    return;
                }
                //  Edit item in list
                $cart['items'][$key]['quantity'] -= $qTy;
                $cart['items'][$key]['priceTotal'] -= $priceDec;
                // Edit total
                $cart['totalPrice'] -= $priceDec;
                $cart['totalQty'] -= $qTy;

                $_SESSION['cart'] = $cart;
                setHTTPCode(200, "Decrease success");
                return;
            }
            setHTTPCode(500, "Product not found!");
            return;
        }

        // **** ADD PRODUCT ***
        public function addToCart(){
            if (empty($_SESSION['cart']) || empty($_POST['id']) || empty($_POST['quantity'])){
                setHTTPCode(500, "Invalid cart or ID or quantity");
                return;
            }
            
            $cart = $_SESSION['cart'];
            $checkItem = $this->itemInCart($cart, $_POST['id']);
            if($checkItem || $checkItem === 0){
                $this->increaseProd($checkItem, $_POST['quantity']); //  Check if item exist on cart
                return;
            }
            $this->addProdToCart(); //  if not add new product
            return;
        }

        public function increaseProd($key, $qTy){
            $cart = $_SESSION['cart'];
            $priceToInc = $cart['items'][$key]['price'] * $qTy; // equal price of the item * quatity item
            //  Edit item in list items
            $cart['items'][$key]['quantity'] += $qTy;  //  increase quantity of specific item
            $cart['items'][$key]['priceTotal'] += $priceToInc; //  increate price
            //  Edit total
            $cart['totalPrice'] += $priceToInc;
            $cart['totalQty'] += $qTy;
            //  Change session cart
            $_SESSION['cart'] = $cart;
            setHTTPCode(200, "Increase Product success!");
        }

        public function addProdToCart(){
            $prod = $this->prodModel->getSingleProduct($_POST['id'], ['products.price', 'products.title']);
            if(empty($prod)){
                setHTTPCode(500, "Product not found!");
                return;
            }
                
            $cart = $_SESSION['cart'];

            $item = [   //  Create new item
                'product_id' => $_POST['id'],
                'quantity' => $_POST['quantity'],
                'title' => $prod[0]['title'],
                'price' => $prod[0]['price'],
                'priceTotal' => (float)$prod[0]['price'] * (int)$_POST['quantity']
            ];
            //  push new item to items field
            array_push($cart['items'], $item);  
            //  Edit total
            $cart['totalPrice'] += $item['priceTotal'];
            $cart['totalQty'] += $item['quantity'];
            //  Change session cart
            $_SESSION['cart'] = $cart;
            setHTTPCode(200, "Add product success!");
            return;
        }
        
        public function itemInCart($cart, $idItem){ //  Check item exist in cart
            foreach($cart['items'] as $key => $item){   
                if($item['product_id'] == $idItem){
                    return $key;
                }
            }
            return false;
        }

        public function createCartFromItems($listItem){
            $cartTemp = [
                'items' => [],
                'totalPrice' => 0,
                'totalQty' => 0
            ];
            if(!is_array($listItem))
                return $cartTemp;
            foreach($listItem as $item){
                $item['priceTotal'] = (int)$item['quantity'] * (float)$item['price'];
                $cartTemp['totalPrice'] += $item['priceTotal'];
                $cartTemp['totalQty'] += $item['quantity'];
                array_push($cartTemp['items'], $item);
            }
           return $cartTemp;
        }

        public function getAddressFromArr($arr){
            return $arr['line1'] . ', ' . $arr['city'];
        }
}

?>