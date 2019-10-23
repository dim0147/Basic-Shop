<?php 

class CartController extends Controller{

        private $prodModel;

        function __construct(){
            $this->model = new CartModel();
            $this->prodModel = new ProductModel();
        }

        public function action(){
             printB($_SESSION['cart']);
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
            if ( empty($_SESSION['cart']) || empty($_POST['id']) ){
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
            if (empty($_SESSION['cart']) || empty($_POST['id']) || empty($_POST['quantity'])){
                setHTTPCode(500, "Invalid cart or ID or quantity");
                return;
            }

            $checkItem = $this->itemInCart($_SESSION['cart'], $_POST['id']);    //  Check item exist, return key if true
            if($checkItem || $checkItem === 0){ //  if exist
                $cart = $_SESSION['cart'];
                $key = $checkItem; //   Key of item in items list
                $qTy = (int)$_POST['quantity'];
                $priceDec = $cart['items'][$key]['price'] * $qTy;    //  price to decrease
                if((int)$cart['items'][$key]['quantity']  <= 1){
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
}

?>