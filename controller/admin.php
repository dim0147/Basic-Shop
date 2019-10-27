<?php 
    class AdminController extends Controller{

        private $prodModel;
        private $cateModel;
        private $pathUpLoad = PATH_IMAGE_UPLOAD. '/';

        function __construct(){
            $this->prodModel = new ProductModel();
            $this->cateModel = new CategoryModel();
            $this->model = new UserModel();
            $this->fileRender = [
                'add-product' => 'admin.add-product',
                'edit-product' => 'admin.edit-product'
            ];
        }

        public function addProduct(){
            $categorys = $this->cateModel->getCategory();
            $this->render($this->fileRender['add-product'],
            [
                'title' => 'Add Product',
                'categorys' => $categorys
            ]);
            return;
        }

        public function upload(){
            // *************  SET UP, VALIDATION DATA *********************//
                //  Convert category json get from client to array 
                $categorys = json_decode($_POST['categorys']);
                $categorys = (array)$categorys;
                //  Check if any field is empty
                if(checkEmptyFile($_FILES['header'], 1) || checkEmptyFile($_FILES['thumbnail'], 2) || checkEmpty([ $_POST['categorys'], $_POST['title'], $_POST['description'], $_POST['price'], $_POST['status'], $_POST['rate']]))
                {
                    setHTTPCode(500, 'Empty Field!');
                    return;
                }
                //  Check title if exist
                if($this->prodModel->select(['id'], ['title' => $_POST['title']])){   
                    setHTTPCode(500, 'Product exist!');
                    return;
                }

            // ************* UPLOAD HEADER -> PRODUCT -> THUMBNAIL *********************//

                    // **      UPLOAD HEADER ** //
                $imageHeader = $this->uploadHeader();  //  upload header image to storage, return name header image
                if(!$imageHeader){ //  Check if error remove header image from storage
                    setHTTPCode(500, 'Error while upload header image!');
                    return;
                }

                     // **      UPLOAD PRODUCT ** //
                //  Create product to database, return the id of product when create finish
                $this->addField($imageHeader);

                     // **      UPLOAD THUMBNAIL ** //
                //  upload thumbnail to storage , return list name image;
                if(!checkEmptyFile($_FILES['thumbnail'], 2)){
                    $listImg = $this->uploadThumbnail();    // Upload to storage, not DB, return list name image
                    if(!$listImg){
                        setHTTPCode(500, "ERROR while update thumbnail!");
                        return false;
                    }
                    $this->uploadThumbOnDB($listImg); // Query database
                }

             // ************* CREATE CATEGORY LINK PRODUCT  *********************//
                if(!empty($categorys)){
                    $this->addCategory($categorys);
                }

            setHTTPCode(200, "Create successful!");
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
            //  Get All category product
            $listCategoryProduct = array_values($product)[0]['categoryName'];
            $categorys = $this->cateModel->getCategory();
            if(!empty(array_values($listCategoryProduct)[0])){  //  If product category not empty
                foreach($listCategoryProduct as $key => $cate){   //  Loop through all category
                    if (in_array($cate, $listCategoryProduct))   //  if product category exist in array category
                        unset($categorys[$key]);    //  remove that category from array category
                }
            }
            else{  // product category is empty
                $product[getFirstKey($product)]['categoryName'] = NULL;
            }
            $this->render($this->fileRender['edit-product'], [
                'product' => $product,
                'category' => $categorys,
                'title' => "Edit Product"
            ]);
            return;
        }

        public function postEditProduct(){
                //  Get array name & id of image thumbnail need to del
                    $arrRmvImg = getArrFromJSON($_POST['imgDel']);
                    $arrNameRmvImg = getArrFromJSON($_POST['nameImgDel']);

                //  Get array category add & del
                    $cateAdd = getArrFromJSON($_POST['cateAdd']);
                    $cateDel = getArrFromJSON($_POST['cateDel']);

             /******** UPDATE HEADER IMAGE ***********/
                //  update header image if have
                $imgHeader = false;
                if(!checkEmptyFile($_FILES['header'], 1)){
                    $imgHeader = $this->uploadHeader(); // save to storage, return name image header
                    if(!$imgHeader){
                        setHTTPCode(500, "Error while update header image!");
                        return;
                    }
                }

             /******** UPDATE PRODUCT AND HEADER IMAGE GET FROM ABOVE TO DB ***********/
                // Update field, eg: title,description,...  
                $this->editField($imgHeader);   //  Query database

             /******** UPDATE THUMBNAIL AND REMOVE  ***********/
                // P1:Update thumbnail if have
                if(!checkEmptyFile($_FILES['thumbnail'], 2)){
                    $listImg = $this->uploadThumbnail();    // Upload to storage, not DB, return list name image
                    if(!$listImg){
                        setHTTPCode(500, "ERROR while update thumbnail!");
                        return false;
                    }
                    $this->uploadThumbOnDB($listImg); // Query database
                }
                    
                // P2:Remove old thumbnail
                    $this->deleteThumbOnDB($arrRmvImg, $arrNameRmvImg); // Query database

             /******** UPDATE CATEGORY   ***********/
                // P1:  Add Category if array add category send from client not empty
                if(!empty($cateAdd))
                    $this->addCategory($cateAdd);   //  Query database


                //  P2: Delete Category if array delete category send from client not empty
                if(!empty($cateDel)){
                    $this->deleteCategory($cateDel); //  Query database
                }
        
    
            /******** ALL IS SUCCESS  ***********/
            setHTTPCode(200, "Update success!");
        }

        public function postLogin(){
            if(!empty($_POST['username']) && !empty($_POST['password']) && !isset($_SESSION['username'])){ 
                $username = $_POST['username'];
                $password = $_POST['password'];
                $passwordQuery = $this->model->select(NULL, ["username" => $username,
                                                                "type" => 'admin']);
                $result = isset($passwordQuery[getFirstKey($passwordQuery)]['password']);
                if($result && password_verify($password, $passwordQuery[getFirstKey($passwordQuery)]['password'])){
                    // $_SESSION['username'] = $username;
                    setHTTPCode(200, "Sign In Success!");
                    return;
                }
                else{
                    setHTTPCode(500, "Username or Password Wrong!");
                    return;
                }
            }
            setHTTPCode(500, "Something wrong, please check again!");
        }

        public function postRegister(){
            if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['name'])){
                //  Assign variable
                $username = $_POST['username'];
                $password = $_POST['password'];
                $name = $_POST['name'];

                //  Check if username is exist
                $checkExist = $this->model->select(NULL, ['username' => $username,
                                                        'type' => 'admin']); 
                if($checkExist){    //  If yes return
                    setHTTPCode(500, "Username exist!");
                    return;
                }

                // Insert new User
                $password = password_hash($password, PASSWORD_DEFAULT); //  hash password
                $values = createQuery(['DEFAULT', $username, $password, $name, 'Active', 'DEFAULT', 'admin']);
                $this->model->insert($values);
                setHTTPCode(200, "Register success!!");
                return;
            }
            else
                setHTTPCode(500, "Field Empty!!");
        }

        public function addField($imageHeader){
            $idNewProduct = $this->prodModel->addNewProduct(
                $_POST['title'], 
                $_POST['description'], 
                (float)$_POST['price'],
                $imageHeader, 
                $_POST['status'], 
                $_POST['rate']);
            if(!$idNewProduct && $idNewProduct !== 0){ // check if fail remove header image from storage
            removeFiles([$imageHeader], PATH_IMAGE_UPLOAD);
            setHTTPCode(500, 'Error while create new Product');
            return;
            }
            $_POST['id'] = $idNewProduct;
        }

        public function addCategory($arrCate){
            $queryCateAdd = [];
            echo $_POST['id'];
            foreach($arrCate as $idCate => $nameCate){
                $queryCateAdd[] = createQuery(['DEFAULT', $_POST['id'], (int)$idCate, $nameCate, $_POST['title']]);
            }
            $queryCateAdd = implode(',' , $queryCateAdd);
            $this->prodModel->addCategoryProduct($queryCateAdd);
        }

        public function uploadHeader(){
            if (checkEmptyFile($_FILES['header'], 1)) 
                return false;

             if ($_FILES['header']['error'] != UPLOAD_ERR_OK || !getimagesize($_FILES['header']['tmp_name']))
                 return false;
             
             $newName = $this->createNameImg($_FILES['header']['name']);
             $result = move_uploaded_file($_FILES['header']['tmp_name'], $this->pathUpLoad . $newName);
             if($result === false){
                removeFiles([$newName], PATH_IMAGE_UPLOAD);
                return false;
             }
             return $newName;
        }

        public function uploadThumbnail(){
            if(checkEmptyFile($_FILES['thumbnail'], 2))
                return false;

            $total = count($_FILES['thumbnail']['name']);
            $listImage = [];
            for($i = 0; $i < $total; $i++){
                if ($_FILES['thumbnail']['error'][$i] != UPLOAD_ERR_OK || !getimagesize($_FILES['thumbnail']['tmp_name'][$i]))
                    return false;

                $newName = $this->createNameImg($_FILES['thumbnail']['name'][$i]);
                $result = move_uploaded_file($_FILES['thumbnail']['tmp_name'][$i], $this->pathUpLoad . $newName);
                if($result === false){
                    removeFiles($listImage, PATH_IMAGE_UPLOAD);
                    return false;
                }
                $listImage[] = $newName;
            }
            return $listImage;
        }

        public function uploadThumbOnDB($listImg){
            $queryCategory = [];
            foreach($listImg as $nameImg){
                $queryCategory[] = createQuery(['DEFAULT', $_POST['id'], 'thumbnail', $nameImg, 'DEFAULT']);
            }
            $queryCategory = implode(',' ,$queryCategory);
            $this->prodModel->addThumbnailProduct($queryCategory);
        }

        public function editField($imgHeader){
            /******** UPDATE PRODUCT WITH HEADER IMAGE ***********/
                // Update field, eg: title,description,...  
                $fieldUpdate = [
                    'title' => addApostrophe($_POST['title']), 
                    'price' => (float)$_POST['price'], 
                    'status' => addApostrophe($_POST['status']), 
                    'rate' => $_POST['rate'], 
                    'description' => addApostrophe($_POST['description'])
                    ];
                $oldImage = ''; //  set old image first in case header image is change
                //  If header Image is update
                if ($imgHeader !== false){
                    $fieldUpdate['image'] = addApostrophe($imgHeader);  //  Add character ["] on left and right Img header
                    $oldImage = $this->prodModel->select(['image'], ['id' => $_POST['id']]);//  get old image before update new one
                }
                $query = createQuery($fieldUpdate, true);   //  True mean create update query
                $this->prodModel->updateProduct($query, $_POST['id']); // If err will die immediately
                if(!empty($oldImage))   //  If not empty, delete old image from storage
                    removeFiles([$oldImage['image']], PATH_IMAGE_UPLOAD);
                return true;
        }

        public function deleteThumbOnDB($arrRmvImg, $arrNameRmvImg){
            if(!empty($arrRmvImg)){
                $queryDelImg = [];
                foreach($arrRmvImg as $idImg){
                    $queryDelImg[] = (int)$idImg;
                }
                $queryDelImg = implode(',' , $queryDelImg);
                $delImg = $this->prodModel->deleteThumbnail($queryDelImg);
                removeFiles($arrNameRmvImg, PATH_IMAGE_UPLOAD);
                return true;
            }
        }

        public function deleteCategory($arrCate){
                $queryCateDel = [];
                foreach($arrCate as $val){
                    $queryCateDel[] = $val;
                }
                $queryCateDel = implode("," , $queryCateDel);
                $this->prodModel->delCategoryProd($queryCateDel, $_POST['id']);
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