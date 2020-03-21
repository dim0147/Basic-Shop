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
            $id = $_GET['q']; //  Get product id
            $title = '';
            $result = '';

            if (empty($id)){
                setHTTPCode(400, 'Need param!');
                return;
            }

            $result = $this->model->getProductWithId($id);
            if(!$result) { //  If query not empty
                setHTTPCode(400, 'Bad request!');
                return $this->renderNotFound();
            }
            $result = mergeResult(['name'], ['image_list'], 'id', $result);
            $title = $result[key($result)]['title'];
            /*
                    //  Query Product

                    //  If not have
                
                    // Merge to one  

                    //  Get title product
            */

                    //  Render Product   
            $this->render($this->fileRender['detail'],
            [
                'title' => $title,
                'products' => $result
            ]);
        }

    }
?>