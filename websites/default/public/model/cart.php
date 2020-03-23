<?php 

class CartModel extends database{

    function __construct(){
        $this->connect('cart');
    }

    public function getSpecificCart($id){
        if (is_null($this->pdo))
            return null;
        
        $stmt = $this->pdo->prepare("SELECT o.order_id, c.cart_id AS cart_id, ci.product_id, ci.quantity, p.title, p.image, p.price, o.address, o.phone, o.email, o.status, o.paymentID
                                    FROM cart c
                                    INNER JOIN cart_item ci ON ci.cart_id = c.cart_id
                                    INNER JOIN products p ON ci.product_id = p.id
                                    INNER JOIN orders o ON o.order_id = c.order_id
                                    WHERE c.user_id = $id
                                    ");
        $stmt->execute();
        $orders = $stmt->fetchAll(\PDO::FETCH_ASSOC); 
        $orders = $this->mergeOrders($orders);
        return $orders;
    }

    public function mergeOrders($orders){
        if(empty($orders))
            return [];
        // Merge all items from DB
        $allOrders = null;
            foreach ($orders as $order) {
                if (empty($allOrders)){
                    $allOrders = $order;
                    continue;
                }
                $allOrders = array_merge_recursive($allOrders, $order);
        }
        // Split order by id
        $ordersData = [];
        $lengthCart = count($allOrders['order_id']);
        for($i = 0; $i < $lengthCart; $i++){
            $idCart = $allOrders['order_id'][$i];
            $idProduct = $allOrders['product_id'][$i];
            $imgProduct = $allOrders['image'][$i];
            $qtyProduct = $allOrders['quantity'][$i];
            $nameProduct = $allOrders['title'][$i];
            $priceProduct = $allOrders['price'][$i];
            $totalPrice = (double)$priceProduct * (int)$qtyProduct;
            $addressOrder = $allOrders['address'][$i];
            $emailOrder = $allOrders['email'][$i];
            $statusOrder = $allOrders['status'][$i];
            $paymentID = $allOrders['paymentID'][$i];
            $item = [
                'product_id' => $idProduct,
                'product_image' => $imgProduct,
                'product_quantity' => $qtyProduct,
                'product_name' => $nameProduct,
                'product_price' => $priceProduct,
                'product_total_price' => $totalPrice
            ];
            if(!array_key_exists($idCart, $ordersData)){
                $ordersData[$idCart]['items'] = [];
                array_push($ordersData[$idCart]['items'], $item);
                $ordersData[$idCart]['total_price'] = $item['product_total_price'];
                $ordersData[$idCart]['total_quantity'] = $item['product_quantity'];
            }
            else{
                array_push($ordersData[$idCart]['items'], $item); 
                $ordersData[$idCart]['total_price'] += $item['product_total_price'];
                $ordersData[$idCart]['total_quantity'] += $item['product_quantity'];
            }
            $ordersData[$idCart]['address'] = $addressOrder;
            $ordersData[$idCart]['email'] = $emailOrder;
            $ordersData[$idCart]['status'] = $statusOrder;
            $ordersData[$idCart]['payment_id'] = $paymentID;
        }
        return $ordersData;
    }
}

?>