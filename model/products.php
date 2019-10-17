<?php 
    class ProductModel extends database{

        function __construct(){
            $this->connect('products');
        }

        public function getAllProduct(){
            if(is_null($this->pdo))
                return NULL;
            $stmt = $this->pdo->prepare("SELECT * FROM products 
                                        INNER JOIN categorys_link_products cp 
                                        ON cp.product_id = products.id
                                        INNER JOIN images
                                        on images.product_id = products.id
                                        ");
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $arr = [];  //  Create new empty array
            foreach($result as $e){ //  Loop through arr
                $idProduct = $e['product_id'];  
                if(!isset($arr[$idProduct])){   //  if empty array don't have current id product
                    $e['categoryList'] = [$e['category_name']]; //  create empty Caterogy list
                    $arr[$idProduct] = $e;  //  Add to empty array
                }
                else{
                    $categoryName =  $e['category_name'];  //  Assign name category of duplicate product
                    if (!in_array($categoryName, $arr[$idProduct]['categoryList'])){
                        array_push($arr[$idProduct]['categoryList'], $categoryName);  //  push to list category
                    }
                }
            }
            printb($arr);
            return $arr;
        }

    }
?>