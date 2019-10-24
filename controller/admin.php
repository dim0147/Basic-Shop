<?php 
    class AdminController extends Controller{

        private $prodModel;
        private $cateModel;
        private $pathUpLoad = PATH_IMAGE_UPLOAD. '/';

        function __construct(){
            $this->prodModel = new ProductModel();
            $this->cateModel = new CategoryModel();
            $this->fileRender = [
                'add-product' => 'admin.add-product',
                'edit-product' => 'admin.edit-product'
            ];
        }

        public function addProduct(){
            $categorys = ($this->cateModel->getCategory());
            $this->render($this->fileRender['add-product'],
            [
                'title' => 'Add Product',
                'categorys' => $categorys
            ]);
            return;
        }

        public function upload(){
            // *************  SET UP, VALIDATION DATA *********************//
                //  Convert categorys json get from client to array 
                $categorys = json_decode($_POST['categorys']);
                $categorys = (array)$categorys;
                //  Check if any field is empty
                if(checkEmptyFile($_FILES['header'], 1) || checkEmptyFile($_FILES['thumbnail'], 2) || checkEmpty([ $_POST['categorys'], $_POST['title'], $_POST['description'], $_POST['price'], $_POST['status'], $_POST['rate']]))
                {
                    setHTTPCode(500, 'Empty Field!');
                    return;
                }

                if($this->prodModel->checkExist('title', $_POST['title'])){   //  Check title if exist
                    setHTTPCode(500, 'Product exist!');
                    return;
                }

            // ************* UPLOAD HEADER -> PRODUCT -> THUMBNAIL *********************//

                    // **      UPLOAD HEADER ** //
                $uploadHeader = $this->uploadHeader();  //  upload header image to storage, return name header image
                if(!$uploadHeader){ //  Check if error remove header image from storage
                    removeFiles([$uploadHeader], PATH_IMAGE_UPLOAD);
                    setHTTPCode(500, 'Error while upload header image!');
                    return;
                }

                     // **      UPLOAD PRODUCT ** //
                //  Create product to database, return the id of product when create finish
                $idNewProduct = $this->prodModel->addNewProduct(
                                                    $_POST['title'], 
                                                    $_POST['description'], 
                                                    (float)$_POST['price'],
                                                    $uploadHeader, 
                                                    $_POST['status'], 
                                                    $_POST['rate']);
                if(!$idNewProduct && $idNewProduct !== 0){ // check if fail remove header image from storage
                    removeFiles([$uploadHeader], PATH_IMAGE_UPLOAD);
                    setHTTPCode(500, 'Error while create new Product');
                    return;
                }

                     // **      UPLOAD THUMBNAIL ** //
                //  upload thumbnail to storage , return list name image;
                $uploadThumbnail = $this->uploadThumbnail();    
                if(!$uploadThumbnail){  // if error
                    setHTTPCode(500, 'Error while upload thumbnail image, ProductID: ' . $idNewProduct);
                    return;
                }
                //  create image thumbnail to database
                $queryThumbnail = []; //  query to add many thumbnail image
                foreach($uploadThumbnail as $image){    //  Loop array of storage image name return from above
                    $queryThumbnail[] = "(DEFAULT, " . $idNewProduct . ", 'thumbnail', '". $image . "', DEFAULT)"; 
                }
                $queryThumbnail = implode(',', $queryThumbnail);
                $result = $this->prodModel->addThumbnailProduct($queryThumbnail); //  Create thumbnails to db
                if(!$result){   //  if fail delete thumbnail in storage
                    removeFiles($uploadThumbnail, PATH_IMAGE_UPLOAD);
                    setHTTPCode(500, "Error while save thumbnail, ProductID: " . $idNewProduct);
                    return;
                }

             // ************* CREATE CATEGORY LINK PRODUCT  *********************//
                if(!empty($categorys)){
                    // create category to category_link_product table
                    $queryCategory = [];//  query to add many category
                    foreach($categorys as $id=>$name){  //  Loop array of category get from client
                        $queryCategory[] = createQuery(['DEFAULT', (int)$idNewProduct, (int)$id, $name, $_POST['title']]);
                    }
                    $queryCategory = implode(',', $queryCategory);
                    $finalResult = $this->cateModel->addMany($queryCategory, 'categorys_link_products');
                    if(!$finalResult){   //  if fail 
                        setHTTPCode(500, "Error while save category, ProductID: " . $idNewProduct);
                        return;
                    }
                }

            setHTTPCode(200, "Create successful!");
        }

        public function uploadHeader(){
            if (empty($_FILES['header'])) 
                return false;

             if ($_FILES['header']['error'] != UPLOAD_ERR_OK || !getimagesize($_FILES['header']['tmp_name']))
                 return false;
             
             $newName = $this->createNameImg($_FILES['header']['name']);
             move_uploaded_file($_FILES['header']['tmp_name'], $this->pathUpLoad . $newName);
             return $newName;
        }

        public function uploadThumbnail(){
            if(!empty($_FILES['thumbnail'])){
                $total = count($_FILES['thumbnail']['name']);
                $listImage = [];
                for($i = 0; $i < $total; $i++){
                    if ($_FILES['thumbnail']['error'][$i] != UPLOAD_ERR_OK || !getimagesize($_FILES['thumbnail']['tmp_name'][$i]))
                        return false;

                    $newName = $this->createNameImg($_FILES['thumbnail']['name'][$i]);
                    move_uploaded_file($_FILES['thumbnail']['tmp_name'][$i], $this->pathUpLoad . $newName);
                    $listImage[] = $newName;
                }
                return $listImage;
            }
                return false;
        }

        public function editProduct(){
            if(!isset($_GET['id'])){ //  check if have param
                setHTTPCode(500, "Please pass parameter!");
                return;
            }
            // Get Product
            $product = $this->prodModel->getSingleProduct($_GET['id'], ['products.*, images.name, images.image_id, cp.category_id, cp.category_name']);
            if(!$product){  // if product not found
                setHTTPCode(500, "Product not found!");
                return;
            }
            $product = mergeResult(['name', 'category_name'], ['listImage', 'categoryName'], 'id', $product, ['name' => 'image_id', 'category_name' => 'category_id']);
            //  Get All category
            $categorys = $this->cateModel->getCategory();
            $categoryOfProd = array_values($product)[0]['categoryName'];    //  Get category of product
            foreach($categorys as $key => $cate){   //  Loop through all category
                if (in_array($cate['name'], $categoryOfProd))   //  if product category exist in array category
                    unset($categorys[$key]);    //  remove that category from array category
            }
            $this->render($this->fileRender['edit-product'], [
                'product' => $product,
                'category' => $categorys,
                'title' => "Edit Product"
            ]);
            return;
        }

        public function postEditProduct(){
            //  Get name,id of image thumbnail need to del
            $arrRmvImg = json_decode($_POST['imgDel']);
            $arrRmvImg = (array)$arrRmvImg;

            $arrNameRmvImg = json_decode($_POST['nameImgDel']);
            $arrNameRmvImg = (array)$arrNameRmvImg;


            /******** UPDATE HEADER IMAGE ***********/
                //  update header image if have
                $uploadHeader = false;
                if(!checkEmptyFile($_FILES['header'], 1)){  //  If not empty
                    $uploadHeader = $this->uploadHeader();
                    if(!$uploadHeader){ //  Check if error then remove header image just upload from storage
                        $this->errorUpload($uploadHeader, 1);
                        return;
                    }
                }

             /******** UPDATE PRODUCT WITH HEADER IMAGE ***********/
                // Update field, eg: title,description,...  
                $fieldUpdate = [
                    'title' => addApostrophe($_POST['title']), 
                    'price' => (float)$_POST['price'], 
                    'status' => addApostrophe($_POST['status']), 
                    'rate' => $_POST['rate'], 
                    'description' => addApostrophe($_POST['description'])
                    ];
                //  If header is update
                if ($uploadHeader !== false)
                    $fieldUpdate['image'] = addApostrophe($uploadHeader);
                $query = createQuery($fieldUpdate, true);
                $result = $this->prodModel->updateProduct($query, $_POST['id']);
                if(!$result){
                    $this->errorUpload($uploadHeader, 1);
                    return;
                }

         /******** UPDATE THUMBNAIL PRODUCT AND THEN REMOVE IF USER WANT REMOVE  ***********/
                // Update thumbnail if have
                if(!checkEmptyFile($_FILES['thumbnail'], 2)){
                    $listImg = $this->uploadThumbnail();
                    if(!$listImg){
                        $this->errorUpload($listImg, 2);
                        return;
                    }
                    $queryCategory = [];
                    foreach($listImg as $nameImg){
                        $queryCategory[] = createQuery(['DEFAULT', $_POST['id'], 'thumbnail', $nameImg, 'DEFAULT']);
                    }
                    $queryCategory = implode(',' ,$queryCategory);
                    $uploadThumbnail = $this->prodModel->addThumbnailProduct($queryCategory);
                    if(!$uploadThumbnail){
                        $this->errorUpload($uploadThumbnail, 2);
                        return;
                    }
                }
                // Remove old thumbnail
                if(!empty($arrRmvImg)){
                    $queryDelImg = [];
                    foreach($arrRmvImg as $idImg){
                        $queryDelImg[] = (int)$idImg;
                    }
                    $queryDelImg = implode(',' , $queryDelImg);
                    $delImg = $this->prodModel->deleteThumbnail($queryDelImg);
                    if(!$delImg){
                        setHTTPCode(500, "Error while remove from db!");
                        return;
                    }
                    removeFiles($arrNameRmvImg, PATH_IMAGE_UPLOAD);
                }
    
            /******** ALL IS SUCCESS  ***********/
            echo "Success full!";
        }

        public function errorUpload($images, $type){
            if($type === 1){
                removeFiles([$images], PATH_IMAGE_UPLOAD);
                setHTTPCode(500, 'Error while upload header image!');
                return;
            }
            if($type === 2){
                removeFiles($images, PATH_IMAGE_UPLOAD);
                setHTTPCode(500, 'Error while upload thumbnail image!');
                return;
            }
        }

        public function createNameImg($file){
            $newName = createRanDomString() .  '.' . getExtFile($file);
            while (file_exists($this->pathUpLoad . $newName)){
                $newName = createRanDomString() . '.' . getExtFile($file);
            }
            return $newName;
        }
    }
    
?>