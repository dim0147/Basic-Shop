<?php 
    class ProductController extends Controller{

        public $userModel;

        function __construct(){
           $this->model = new ProductModel();
           $this->userModel = new UserModel();
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
            if (empty($_GET['id'])){
                setHTTPCode(400, 'Need param!');
                redirectBut();
                return;
            }
            $id = $_GET['id']; //  Get product id

            $product = $this->model->getProductWithId($id);
            if(!$product) { //  If query not empty
                setHTTPCode(400, 'Bad request!');
                redirectBut();
                return;
            }
            $product = mergeResult(['name'], ['image_list'], 'id', $product);
            $product = $product[getFirstKey($product)];
            // printB($product);
            if(!is_array($product['image_list']))
                $product['image_list'] = [];
                    //  Render Product   
            $this->render($this->fileRender['detail'],
            [
                'title' => $product['title'],
                'product' => $product
            ]);
        }

    }
?>