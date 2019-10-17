<?php 
    class ProductController extends Controller{

        function __construct(){
           $this->model = new ProductModel();
        }

        function index(){
           
            if($this->model != NULL){
                $products = $this->model->getAll();
                foreach($products as $key => $value){
                    $description = $value['description'];
                    if (strlen($description) > 100)
                        $products[$key]['description'] = substr($description, 0, 90) . '...';
                }
                $this->render('product.index',
                 [
                     'title' => 'Products',
                     'products' => $products
                 ]);
            }
        }
    }
?>