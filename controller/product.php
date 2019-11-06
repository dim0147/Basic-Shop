<?php 
    class ProductController extends Controller{

        function __construct(){
           $this->model = new ProductModel();
           $this->fileRender = [
            'index' => 'product.index',
            'detail' => 'product.detail'
            ]; 
        }

        public function index(){
            if($this->model != NULL){
                    //  Get all from Product Model
                $products = $this->model->getAllProduct();

                    //  Merge result identify by id product
                $products = mergeResult(['category_name', 'name'], ['category_list', 'image_list'], 'id', $products);
                
                    //  Render product
                $this->render($this->fileRender['index'],
                 [
                     'title' => 'Products',
                     'products' => $products
                 ]);
            }
        }

        public function detail(){
                 //  If query not empty
            if (empty($_GET['q'])){ 
                setHTTPCode(400, 'Need param!');
                return $this->renderNotFound();
            }
                    //  Get id product
                $id = $_GET['q'];   

                    //  Query Product
                $result = $this->model->getProductWithId($id); 

                    //  If not have
                 if(!$result) {
                    setHTTPCode(400, 'Bad request!');
                    return $this->renderNotFound();
                 }
                
                    // Merge to one 
                $result = mergeResult(['name'], ['image_list'], 'id', $result); 

                    //  Get title product
                $title = $result[key($result)]['title']; 

                    //  Render Product   
                $this->render($this->fileRender['detail'],
                 [
                    'title' => $title,
                    'products' => $result
                 ]);
        }

    }
?>