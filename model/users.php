<?php
    class UserModel extends database{
        function __construct(){
            $this->connect('users');
        }

        public function getProfile(){
            try{
                if ($this->pdo === null && !empty($_SESSION['user']))
                    return false;

                $user_id = $_SESSION['user'];
                $user_id = $this->pdo->quote($user_id);
                $stmt = $this->pdo->prepare("SELECT users.username, users.name,
                                    orders.order_id, orders.status, orders.address, orders.phone, orders.email, orders.paymentID,
                                    products.id as 'prodID', products.title, products.image,
                                    categorys_link_products.category_name, categorys_link_products.product_id
                                    FROM users
                                    LEFT JOIN orders ON orders.user_id = users.user_id
                                    INNER JOIN cart ON cart.order_id = orders.order_id
                                    INNER JOIN cart_item ON cart_item.cart_id = cart.cart_id
                                    INNER JOIN products ON products.id = cart_item.product_id
                                    LEFT JOIN categorys_link_products ON categorys_link_products.product_id = products.id    
                                    WHERE users.user_id = $user_id");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            catch(PDOException $err){
                die($err);
            }
        }
    }
?>