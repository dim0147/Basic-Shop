<?php 

class CartModel extends database{

    function __construct(){
        $this->connect('cart');
    }

    public function getSpecificCart($id){
        if (is_null($this->pdo))
            return null;
        $stmt = $this->pdo->prepare("SELECT ci.product_id, ci.quantity, p.title, p.price
                                    FROM (cart_item as ci, cart as c)
                                    INNER JOIN products p ON ci.product_id = p.id
                                    WHERE c.user_id = $id");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}

?>