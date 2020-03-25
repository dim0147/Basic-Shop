<?php 
class unitTest{
    function __construct(){
    }


    public function addcartTesting(){
        $product = $this->setUp();
        echo "<h1> Testing add to cart function, Location: controller > cart.php > action()</h1>\n";
        $_SESSION['cart'] = [
			"items" => [],
			"totalPrice" => 0,
			"totalQty" => 0
		];
        $_POST['action'] = 'add';
        $product->action();
    }

    public function setUp(){
        $model = new CartController();
        return $model;
    }

}