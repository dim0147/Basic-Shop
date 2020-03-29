<?php
class UserModel extends database
{
    function __construct()
    {
        $this->connect('users');
    }

    public function getOrderUser($userID)
    {
        try
        {
            if ($this->pdo === null) return false;

            $stmt = $this
                ->pdo
                ->prepare("SELECT orders.order_id, orders.status, orders.address, orders.phone, orders.email, orders.paymentID,
                                    products.id as 'prodID', products.title, products.image,
                                    categorys_link_products.category_name, categorys_link_products.product_id
                                    FROM orders
                                    LEFT JOIN cart ON cart.order_id = orders.order_id
                                    LEFT JOIN cart_item ON cart_item.cart_id = cart.cart_id
                                    LEFT JOIN products ON products.id = cart_item.product_id
                                    LEFT JOIN categorys_link_products ON categorys_link_products.product_id = products.id    
                                    WHERE orders.user_id = :userID");
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $err)
        {
            die($err);
        }
    }
}
?>
