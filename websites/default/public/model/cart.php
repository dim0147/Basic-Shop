<?php 

class CartModel extends database{

    function __construct(){
        $this->connect('cart');
    }

    public function getSpecificCart($id){
        if (is_null($this->pdo))
            return null;
        
        $stmt = $this->pdo->prepare("SELECT c.cart_id AS cart_id, ci.product_id, ci.quantity, p.title, p.price
                                    FROM cart c
                                    INNER JOIN cart_item ci ON ci.cart_id = c.cart_id
                                    INNER JOIN products p ON ci.product_id = p.id
                                    WHERE c.user_id = $id
                                    ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}

?>