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

<<<<<<< HEAD
        public function index(){
=======
        function index(){
           
>>>>>>> 24f4ad514435d6c76af2f4fe13a5eb28c4f05569
            if($this->model != NULL){
                $products = $this->model->getAllProduct();; //  get all from Product Model
                $products = mergeResult(['category_name', 'name'], ['category_list', 'image_list'], 'product_id', $products);
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