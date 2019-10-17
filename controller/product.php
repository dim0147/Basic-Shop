<?php 
    class ProductController extends Controller{

        private $categoryModel;
        private $categoryLinkProductM;
        private $imageModel;

        function __construct(){
           $this->model = new ProductModel();
           $this->categoryModel = new CategoryModel();  //  Get All category
           $this->categoryLinkProductM= new CategoryLinkProductModel(); //  Get Category Link To Product
           $this->imageModel = new ImageModel();    //  Get Image Product
        }

        public function index(){
            if($this->model != NULL){
                $products = $this->model->getAllProduct();; //  get all from Product Model
                mergeResult(['category_name', 'name'], ['category_list', 'image_list'], 'product_id', $products);
                return;
                foreach($products as $key => $product){   //  loop through products, edit some field

                    $description = $product['description'];
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