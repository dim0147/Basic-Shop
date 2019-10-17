<?php 
    class ProductModel extends database{

        function __construct(){
            $this->connect('products');
        }

        public function getAllProduct(){
            if(is_null($this->pdo))
                return NULL;
            $stmt = $this->pdo->prepare("SELECT products.*, images.name, images.product_id, cp.category_name FROM products 
                                        INNER JOIN categorys_link_products cp 
                                        ON cp.product_id = products.id
                                        INNER JOIN images
                                        on images.product_id = products.id
                                        ");
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        }

    }
?>