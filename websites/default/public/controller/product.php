<?php 
    class ProductController extends Controller{

        public $userModel;

        function __construct(){
           $this->model = new ProductModel();
           $this->userModel = new UserModel();
           $this->fileRender = [
                'index' => 'product.index',
                'detail' => 'product.detail',
                'search-product' => 'product.search-product'
            ]; 
        }

        public function index(){
            if($this->model != NULL){
                    //  Get all from Product Model
                $products = $this->model->getAllProduct();

                    //  Merge result identify by id product
                $products = mergeResult(['category_name', 'name'], ['category_list', 'image_list'], 'id', $products);
                $getCategoryHeader = $this->model->getAllCategory();
                    //  Render product
                $this->render($this->fileRender['index'],
                [
                     'title' => 'Products',
                     'products' => $products,
                     'categoryHeader' => $getCategoryHeader
                ]);
            }
        }
        
        public function searchProductByString(){
            if(empty($_GET['s'])){
                setHTTPCode(400, 'Please enter search string!');
                redirectBut();
                return;
            }
            $products = $this->model->getProductByString($_GET['s']);
            if(!empty($products)){
                //  Merge result identify by id product
                $products = mergeResult(['category_name', 'name'], ['category_list', 'image_list'], 'id', $products);
            }
            $getCategoryHeader = $this->model->getAllCategory();
            $this->render($this->fileRender['search-product'], ['title' => "Search with  '" . $_GET['s']. "'", 
                                            'products' => $products,
                                            'searchKey' => "string '" . $_GET['s'] . "'",
                                            'categoryHeader' =>  $getCategoryHeader]);
        }

        public function searchProductByCategory(){
            if(empty($_GET['s'])){
                setHTTPCode(400, 'Please enter category!');
                redirectBut();
                return;
            }
            $category = $this->model->select(NULL, ['name' => $_GET['s']], 'categorys');
            if(empty($category)){
                setHTTPCode(400, 'Not found category!');
                redirectBut();
                return;
            }
            $products = $this->model->getProductByCategory($_GET['s']);
            if(!empty($products)){
                $products = mergeResult(['category_name', 'name'], ['category_list', 'image_list'], 'id', $products);
            }
            $getCategoryHeader = $this->model->getAllCategory();
            $this->render($this->fileRender['search-product'], ['title' => "Search by category " . $_GET['s'], 
                                            'products' => $products,
                                            'searchKey' => "category " . $_GET['s'],
                                            'categoryHeader' =>  $getCategoryHeader]);
            
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
            $getCategoryHeader = $this->model->getAllCategory();
                    //  Render Product   
            $this->render($this->fileRender['detail'],
            [
                'title' => $product['title'],
                'product' => $product,
                'categoryHeader' => $getCategoryHeader
            ]);
        }

    }
?>