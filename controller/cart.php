<style>
    .success {
        color: green;
        text-align: center;
    }

    .black {
        border: 1px solid black;
    }
</style>

<?php 

if (!isset($_SESSION)){
    echo "chua khoi tao \n";
    session_start();
    if(!isset($_SESSION['cart'])){
    echo "cart chua khoi tao";
    $_SESSION['cart'] = [
            "items" => [],
            "totalPrice" => 0,
            "totalQty" => 0
        ];
    }
}

class CartController extends Controller{

        private $prodModel;

        function __construct(){
            $this->model = new CartModel();
            $this->prodModel = new ProductModel();
           // $this->fileRender = [
                //'checkout' => 'cart.checkout'
           // ];
        }

        /*
        public function checkout(){
            $this->render($this->fileRender['checkout'], [
                'title' => 'Checkout'
            ]);
            return;
        }

        public function postCheckout(){ // TODO Later update
            if(!empty($_SESSION['cart']) && isset($_SESSION['user'])){
                $cart = $_SESSION['cart'];
                printB($_SESSION['cart']);
                $value = [];
                foreach($cart['items'] as $item){
                    $value[] = createQuery([1, $item['product_id'], $item['quantity']]);
                }
            }
            else{
                echo 'empty cart or user!';
            }
        }*/

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
            $this->total();
            printB($_SESSION['cart']);
        }

        public function showCart(){
            if(!empty($_SESSION['cart'])){
                printB($_SESSION['cart']);
                setHTTPCode(200);
                return;
            }
            setHTTPCode(500, 'Not Found!');
        }


        // ADD PRODUCT AND INCREASE QUANTITY
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

            $cart['items'][$key]['quantity'] += $qTy;  //  increase quantity of specific item
            $cart['items'][$key]['priceTotal'] = $cart['items'][$key]['price'] * $cart['items'][$key]['quantity'];

            $_SESSION['cart'] = $cart;
            setHTTPCode(200, "<h1 class=success>Increase Product success!</h1>");
        }

        public function addProdToCart(){
            $prod = $this->prodModel->getSingleProduct($_POST['id'], ['products.price', 'products.title', 'products.image']);
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
                'image' => $prod[0]['image'],
                'priceTotal' => (float)$prod[0]['price'] * (int)$_POST['quantity']
            ];
            //  push new item to items field
            array_push($cart['items'], $item);  
            $_SESSION['cart'] = $cart;
            setHTTPCode(200, "<h1 class=success>Add product success!</h1>");
            return;
        }



        // REMOVE PRODUCT AND DECREASE QUANTITY
        public function removeProd($keyItem){
            if (empty($_SESSION['cart']) || empty($_POST['id'])){
                setHTTPCode(500, "Invalid cart or ID");
                return;
            }
            if ($keyItem == NULL)   //  If not pass parameter searching item in list items
                $keyItem = $this->itemInCart($_SESSION['cart'], $_POST['id']);
            if($keyItem || $keyItem === 0){  
                $cart = $_SESSION['cart'];
                unset($cart['items'][$keyItem]);
                $_SESSION['cart'] = $cart;
                setHTTPCode(200, "<h1 class=success>Remove success</h1>");
                return;
            }
            setHTTPCode(500, "Product not exist");
            return;
        }
 
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

                if(( ($cart['items'][$key]['quantity'] -= $qTy) <= 0 )){
                    $this->removeProd($key);
                    return;
                }
                //  Edit item in list
                $cart['items'][$key]['quantity'] -= $qTy;
                $cart['items'][$key]['priceTotal'] = $cart['items'][$key]['price'] * $cart['items'][$key]['quantity'];

                $_SESSION['cart'] = $cart;
                setHTTPCode(200, "Decrease success");
                return;
            }
            setHTTPCode(500, "Product not found!");
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

        public function total(){
            $cart = $_SESSION['cart'];

            $cart['totalPrice'] = 0;
            $cart['totalQty'] = 0;
            if(!empty($cart)){
                foreach($cart['items'] as $key => $item){
                    $cart['totalPrice'] += $cart['items'][$key]['priceTotal'];
                    $cart['totalQty'] += $cart['items'][$key]['quantity'];

                    echo
                        "<div class=black>".
                            "<H1>".$cart['items'][$key]['title']."</H1>".
                            "<p> totalPrice ".$cart['items'][$key]['priceTotal']."</p>".
                            "<p> quantity ".$cart['items'][$key]['quantity']."</p>".
                            "<br>".
                        "</div>"
                    ;
                }
            }
            $_SESSION['cart'] = $cart;
        } 
}

?>
