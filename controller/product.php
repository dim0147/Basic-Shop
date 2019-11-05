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
                $products = $this->model->getAllProduct();; //  get all from Product Model
                $products = mergeResult(['category_name', 'name'], ['category_list', 'image_list'], 'id', $products);
                $this->render($this->fileRender['index'],
                 [
                     'title' => 'Products',
                     'products' => $products
                 ]);
            }
        }

        public function detail(){
            if (!empty($_GET['q'])){    //  If query not empty
                $id = $_GET['q'];
                $result = $this->model->getProductWithId($id); //  Query Product
                $result = mergeResult(['name'], ['image_list'], 'id', $result); // Merge to one 
                if(count($result) <= 0) //  If not have
                    return $this->renderNotFound();
                $title = $result[key($result)]['title'];    //  Get title product
                $this->render($this->fileRender['detail'],
                 [
                    'title' => $title,
                    'products' => $result
                 ]);
            }
            else    //  query is empty
                return $this->renderNotFound();
        }

    }
?>