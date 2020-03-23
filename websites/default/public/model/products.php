<?php 
    class ProductModel extends database{

        function __construct(){     //The constructor is called on an object after it has been created
            $this->connect('products');
        }

        public function getAllProduct(){
            if(is_null($this->pdo))
                return NULL;
            $stmt = $this->pdo->prepare("SELECT products.*, images.name, images.product_id, cp.category_name FROM products 
                                            LEFT JOIN categorys_link_products cp 
                                            ON products.id = cp.product_id
                                            LEFT JOIN images
                                            on products.id = images.product_id
                                        ");
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        }

        public function getProductWithId($id, $fields = ['products.*', 'images.name']){
            try {
                if(is_null($this->pdo))
                    return NULL;
                    
                $fieldQuery = '';
                if(!empty($fields)){    //  If not empty
                    foreach ($fields as $key => $field){
                        if (end($fields) == $field) //  Check if reach last element
                            $fieldQuery = $fieldQuery . $field ;
                        else
                            $fieldQuery = $fieldQuery . $field . ', ';
                    }
                }

                $stmt = $this->pdo->prepare("SELECT DISTINCT $fieldQuery
                                    FROM products 
                                    LEFT JOIN categorys_link_products cp ON products.id = cp.product_id
                                    LEFT JOIN images ON images.product_id = products.id
                                    WHERE products.id = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }
            catch(PDOException $err){
                return [];
            }
        }

        public function addNewProduct($title, $descr, $price, $imageName, $stat, $rate){
            try{
                if(is_null($this->pdo))
                    return false;

                $stmt = $this->pdo->prepare("INSERT INTO products VALUES 
                (null, :title, :descr, :price, :imageName, :stat, :rate)");
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':descr', $descr);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':imageName', $imageName);
                $stmt->bindParam(':stat', $stat);
                $stmt->bindParam(':rate', $rate);
                $stmt->execute();
                return $this->pdo->lastInsertId();
            }
            catch(PDOException $err){
                die($err);
            }
        }

        public function deleteThumbnail($listID){
            try{
                if(is_null($this->pdo))
                    return false;
                $stmt = $this->pdo->prepare("DELETE FROM images WHERE image_id IN ($listID)");
                $stmt->execute();
                return true;
            }
            catch(PDOException $err){
                die($err);
            }


        }
    }
?>