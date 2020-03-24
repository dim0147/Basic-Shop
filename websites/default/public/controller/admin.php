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
                'edit-product' => 'admin.edit-product',
                'add-category' => 'admin.add-category',
                'edit-category' => 'admin.edit-category',
                'dashboard' => 'admin.dashboard'
            ];
        }
            //  HELPER FUNCTION
        public function uploadHeader(){ //  Upload header image to local storage
            if (checkEmptyFile($_FILES['header'], 1))   //  Check  if empty 
                return false;

            //  Check if file not have error and file is a image
            if ($_FILES['header']['error'] != UPLOAD_ERR_OK || !getimagesize($_FILES['header']['tmp_name']))
                 return false;
             
            //  Initialize random name for Image
            $newName = $this->createNameImg($_FILES['header']['name']);

            //  Upload to local storage
            $result = move_uploaded_file($_FILES['header']['tmp_name'], $this->pathUpLoad . $newName);

            // If error delete image, for avoid waste capacity
            if($result === false){
                removeFiles([$newName], PATH_IMAGE_UPLOAD);
                return false;
            }
            //  Return name Image if success
            return $newName;
        }

        public function uploadThumbnail(){  //  Up thumbnail image to local storage
                //  Check if not empty
            if(checkEmptyFile($_FILES['thumbnail'], 2))
                return false;

                // Get total image Thumbnail
            $total = count($_FILES['thumbnail']['name']);
            
                // Create list Image 
            $listImage = [];

                //  Loop through list thumbnail image upload from client
            for($i = 0; $i < $total; $i++){

                    //  If current image ok and type is image
                if ($_FILES['thumbnail']['error'][$i] != UPLOAD_ERR_OK || !getimagesize($_FILES['thumbnail']['tmp_name'][$i]))
                    return false;

                    //  Initilize name for image
                $newName = $this->createNameImg($_FILES['thumbnail']['name'][$i]);

                    //  Upload to local storage
                $result = move_uploaded_file($_FILES['thumbnail']['tmp_name'][$i], $this->pathUpLoad . $newName);
            
                    //  Check if fails
                if($result === false){
                    removeFiles($listImage, PATH_IMAGE_UPLOAD);
                    return false;
                }

                    //  Success upload, add name of the current image to array  $listImage
                $listImage[] = $newName;
            }

            //  For Loop finish, return list image name
            return $listImage;
        }

        public function uploadThumbOnDB($listImg){  //  Up thumbnail image to DB
                //  Column to insert into images table
            $column = ['product_id', 'type', 'name'];

                //  Initilize values to insert
            $values = [];
            
                //  Loop through list Image
            foreach($listImg as $nameImg){
                $values[] = [$_POST['id'], 'thumbnail', $nameImg];   //  Push data to array value 
            }
                //  Insert to database
            $this->prodModel->insert($values, $column, 'images');
        }

        public function deleteThumbOnDB($arrRmvImg, $arrNameRmvImg){    //  Delete thumbnail image on DB
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

        public function addNewProduct($imageHeader){    //  Add new Product to DB
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

        public function editProduct($imgHeader){ // Edit product to DB
                // Update field, eg: title,description,...  
                $fieldUpdate = [
                    'title' => ($_POST['title']), 
                    'price' => (float)$_POST['price'], 
                    'status' => ($_POST['status']), 
                    'rate' => $_POST['rate'], 
                    'description' => ($_POST['description']),
                    ];
                $oldImage = ''; //  set old image first in case header image is change
                //  If header Image is update
                if ($imgHeader !== false){
                    $fieldUpdate['image'] = $imgHeader;  //  Add character ["] on left and right Img header
                    $oldImage = $this->prodModel->select(['image'], ['id' => $_POST['id']]);//  get old image before update new one
                }
                $this->prodModel->update($fieldUpdate, ['id' => $_POST['id']]); // If err will die immediately
                return;
                if(!empty($oldImage))   //  If not empty, delete old image from storage
                    removeFiles([$oldImage[0]['image']], PATH_IMAGE_UPLOAD);
                return true;
        }

        public function addCategory($arrCate){  //  Add category to DB
            if(empty($arrCate))
                return;
                //  Specify column to insert data
            $column = ['product_id', 'category_id', 'category_name', 'product_title'];
            
                //  Value array to insert
            $values = [];

                //  Add data to values[] array
            foreach($arrCate as $idCate => $nameCate){
                $values[] = [$_POST['id'], (int)$idCate, $nameCate, $_POST['title']];
            }

            $this->prodModel->insert($values, $column, 'categorys_link_products');
        }

        public function deleteCategory($arrCate){   //  Delete category from DB
            $values = [
                'product_id' => [$_POST['id']],
                'category_id' => $arrCate
            ];
            foreach($arrCate as $val){
                $queryCateDel[] = $val;
            }
            $queryCateDel = implode("," , $queryCateDel);
            $this->prodModel->delete($values, 'categorys_link_products');
        }

        public function validArrCate($arrCate){ //  Check if product category exist and remove it
                //  Check if empty
            if(empty($arrCate))
                return false;
                //  Create array of json object array
            $listKey = array_keys($arrCate);
            $listName = array_values($arrCate);
            $arrayCategory = array_combine($listKey, $listName);
                //  Set condition for check category if exist 
            $category = [
                'id' => array_keys($arrayCategory)
            ];
                //  Check product if contain some category
            $product = [
                'category_id' => array_keys($arrayCategory),
                'product_id' => [$_POST['id']]
            ];
                //  Query Check category if exist all
            $categoryCheck = $this->prodModel->selectMany(['COUNT(*)'], NULL, $category, 'categorys');
                //  If empty in count, something wrong!
            if(empty($categoryCheck[0]['COUNT(*)']))    
                return false;
                //  If count not equal length of array category to add, mean some category is fake
            if($categoryCheck[0]['COUNT(*)'] != count($arrayCategory))  
                return false;
                //  Check product  if have some category already
            $productCheck = $this->prodModel->selectMany(['category_id'], NULL, $product, 'categorys_link_products');
                //  If not return completely array category to add
            if(!$productCheck)
                return $arrayCategory;
                // Have some category include already, remove them from array category add so add any category that not add yet
            foreach($productCheck as $element){
                $idCategory = $element['category_id'];  //  Get id category that have already
                unset($arrayCategory[$idCategory]);
            }
            return $arrayCategory;
        }

        public function errorUpload($images, $type){    //  Remove image from local if upload to DB fails
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

        public function createNameImg($file){   //  Create name of Image
            $newName = createRanDomString() .  '.' . getExtFile($file);
            while (file_exists($this->pathUpLoad . $newName)){
                $newName = createRanDomString() . '.' . getExtFile($file);
            }
            return $newName;
        }
    
        public function dashboard(){
            $this->render($this->fileRender['dashboard'], ['title' => 'Dashboard']);
        }

        public function addProductIndex(){
                //  Get all category from db
            $categorys = $this->prodModel->select(NULL, '*', 'categorys');
            $this->render($this->fileRender['add-product'],
            [
                'title' => 'Add Product',
                'categorys' => $categorys
            ]);
            return;
        }

        /**
         * Create new product
         * @param {Object} $_POST['categorys']
         * @param {File}   $_FILES['header'], $_FILES['thumbnail']
         * @param {string} $_POST['categorys'], 
         * @param {string} $_POST['title']
         * @param {string} $_POST['description'] 
         * @param {string} $_POST['price'] 
         * @param {string} $_POST['status'] 
         * @param {string} $_POST['rate']
         */
        public function postAddProduct(){
            if(empty($_POST['categorys'])){
                setHTTPCode(500, 'Empty Field!');
                return;
            }
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
            if($this->prodModel->select(NULL, ['title' => $_POST['title']])){   
                setHTTPCode(500, 'Product exist!');
                return;
            }
                //  upload header image to storage, return name header image
            $imageHeader = $this->uploadHeader();  
                //  Check if error remove header image from storage
            if(!$imageHeader){ 
                setHTTPCode(500, 'Error while upload header image!');
                return;
            }
                //  Add product to database, return the id of product when create finish
            $this->addNewProduct($imageHeader);
                //  upload thumbnail to storage , return list name image;
            if(!checkEmptyFile($_FILES['thumbnail'], 2)){
                // Upload to storage, not DB, return list name image
                $listImg = $this->uploadThumbnail();    
                //  If Error
                if(!$listImg){
                    setHTTPCode(500, "ERROR while update thumbnail!");
                    return false;
                }
                //  Upload thumbnail image to database, get list of name image from upload local storage
                $this->uploadThumbOnDB($listImg);;
            }
                // Add category
            if(!empty($categorys)){
                $this->addCategory($categorys);
            }

            setHTTPCode(200, "Create successful!");
        }

        public function editProductIndex(){
                //  Check if have param
            if(!isset($_GET['id'])){ 
                setHTTPCode(500, "Please pass parameter!");
                return;
            }
                // Get Product
            $product = $this->prodModel->getProductWithId($_GET['id'], ['products.*, images.name, images.image_id, cp.category_id, cp.category_name']);
            if(!$product){  // if product not found
                setHTTPCode(500, "Product not found!");
                return;
            }
                //  Merge many records result to one
            $product = mergeResult(['name', 'category_name'], ['listImage', 'categoryName'], 'id', $product, ['name' => 'image_id', 'category_name' => 'category_id']);
                //  Get All category product
            $listCategoryProduct = array_values($product)[0]['categoryName'];
            $categorys = $this->prodModel->select(NULL, '*', 'categorys');
                // Remove category of product to $categorys Array (contain all category include category of product, we go to remove them out)
                // If product category not empty    
            if(!empty($listCategoryProduct)){  
                    //  Loop through all category of product
                foreach($listCategoryProduct as $idCate => $cateName){   
                    foreach($categorys as $key => $element){ //  Loop through all category 
                        if($element['id'] == $idCate){  //  If element['id'] category equal to $idCate of product
                            unset($categorys[$key]);   //  remove that category from array category and break
                            break;
                        }
                    }
                }
            }
            else{ 
                // product category is empty, set key categoryName of product equal null array
                $product[getFirstKey($product)]['categoryName'] = [];
            }
        
            $this->render($this->fileRender['edit-product'], [
                'product' => $product,
                'category' => $categorys,
                'title' => "Edit Product"
            ]);
            return;
        }

        /**
         * Update product
         * @param {Object} $_POST['imgDel'] 
         * @param {Object} $_POST['nameImgDel']
         * @param {Object} $_POST['cateAdd']
         * @param {Object} $_POST['cateDel']
         * @param {File}   $_FILES['header'], $_FILES['thumbnail']
         * @param {string} $_POST['title']
         * @param {string} $_POST['description'] 
         * @param {string} $_POST['price'] 
         * @param {string} $_POST['status'] 
         * @param {string} $_POST['rate']
         */
        public function postEditProduct(){
            if(!isset($_POST['imgDel'], $_POST['nameImgDel'], $_POST['cateAdd'], $_POST['cateDel'], $_FILES['header'], $_FILES['thumbnail'], $_POST['price'], $_POST['status'], $_POST['rate'], $_POST['description'])){
                setHTTPCode(400, "Error, empty field!!");
                return;
            }
                //  Get array name & id of image thumbnail need to delete
            $arrRmvImg = getArrFromJSON($_POST['imgDel']);
            $arrNameRmvImg = getArrFromJSON($_POST['nameImgDel']);
                //  Get array category add & del
            $cateAdd = getArrFromJSON($_POST['cateAdd']);
            $cateDel = getArrFromJSON($_POST['cateDel']);
                //  Update header image if have
            $imgHeader = false;
            if(!checkEmptyFile($_FILES['header'], 1)){   //  if image header is not empty
                $imgHeader = $this->uploadHeader();     // save to storage, return name image header
                if(!$imgHeader){     //  If error, return
                    setHTTPCode(500, "Error while update header image!");
                    return;
                }
            }
                // Update field, eg: title,description,...,pass variable $imgHeader for check if have header image or not 
            $this->editProduct($imgHeader);  
                // Update thumbnail if have
            if(!checkEmptyFile($_FILES['thumbnail'], 2)){
                $listImg = $this->uploadThumbnail();   // Upload to storage, not DB, return list name image
                if(!$listImg){  //  If error return
                    setHTTPCode(500, "ERROR while update thumbnail!");
                    return false;
                }
                $this->uploadThumbOnDB($listImg); //  If not error, save list name image to database
            }
                // Remove old thumbnail
            $this->deleteThumbOnDB($arrRmvImg, $arrNameRmvImg); 
                // Add Category if array add category send from client not empty
            $cateAdd = $this->validArrCate($cateAdd);
            if(!empty($cateAdd) ){
                $this->addCategory($cateAdd);   //  Add to DB
            }
            
                // Delete category if array delete category send from client not empty
            if(!empty($cateDel)){
                $this->deleteCategory($cateDel); // Remove from DB
            }
                /******** ALL IS SUCCESS  ***********/
            setHTTPCode(200, "Update success!");
        }

        /**
         * ADMIN LOGIN
         * @param {String} $_POST['username']
         * @param {String} $_POST['password']
         */
        public function postLogin(){
                //  Check if not empty 
            if(!empty($_POST['username']) && !empty($_POST['password']) && empty($_SESSION['user'])){ 
                $username = $_POST['username'];
                $password = $_POST['password'];
                    //  Get user
                $user = $this->model->select(NULL, ["username" => $username,
                                                    "type" => 'admin']);
                    //  Check if user exist and verify password success
                $result = isset($user[getFirstKey($user)]['password']);
                if($result && password_verify($password, $user[getFirstKey($user)]['password'])){
                    $_SESSION['user'] = $user[getFirstKey($user)]['user_id'];
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

        /**
         * ADMIN REGISTER
         * @param {String} $_POST['username']
         * @param {String} $_POST['password']
         * @param {String} $_POST['name']
         */
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
                $column = ['username', 'password', 'name', 'status', 'type'];
                $values = [[$username, $password, $name, 'Active', 'admin']];
                $this->model->insert($values);
                setHTTPCode(200, "Register success!!");
                return;
            }
            else
                setHTTPCode(500, "Field Empty!!");
        }

        public function addCateIndex(){
            $this->render($this->fileRender['add-category'], ['title' => 'Add Category']);
        }
        
        /**
         * Add new category
         * @param {String} $_POST['category']
         */
        public function postAddCate(){
            if(empty($_POST['category'])){  //  Check if don't have name category to add
                setHTTPCode(500, 'Empty Field!!');
                return;
            }
            //  Check if category is exist
            if($this->prodModel->select(NULL, ['name' => $_POST['category']], 'categorys')){
                setHTTPCode(500, 'Category exist!!!');
                return;
            }
            //  Check if description is require, this is optional
            $description = '';
            if(!empty($_POST['description']))
                $description = $_POST['description'];

            //  Column need to insert
            $column = ['name', 'description'];
            //  Data need insert  
            $value = 
            [
                [$_POST['category'], $description]
            ];
            $this->prodModel->insert($value, $column, 'categorys');
            setHTTPCode(200, 'Create successful!');
            return;
      
        }  
        
        /**
         * Render pate edit category
         * @param {String} $_GET['id']
         */
        public function editCateIndex(){
            if(empty($_GET['id'])){ //  Check id field if empty
                setHTTPCode(500, 'Please pass id!');
                return;
            }
            //  Searching This ID in db
            $category = $this->prodModel->select(NULL, ['id' => $_GET['id']], 'categorys');
            if(!$category){ //  If not exist
                setHTTPCode(500, 'Cannot get category, please pass correct ID!');
                return;
            }
            $category = $category[getFirstKey($category)];  //  Get category return to view
            $this->render($this->fileRender['edit-category'], [
                                            'title' => "Edit " . $category['name'],
                                            'category' => $category]);
        }
 
        /**
         * Edit category
         * @param {String} $_POST['category']
         * @param {String} $_POST['id'] (require)
         * @param {String} $_POST['description']
         */
        public function postEditCate(){
            if(!isset($_POST['category']) || empty($_POST['id']) || !isset($_POST['description'])){
                setHTTPCode(500, "ERROR, empty field or wrong parameter!");
                return;
            }
            //  Update categorys table
            $this->prodModel->update(['name' => $_POST['category'],'description' => $_POST['description']], ['id' => $_POST['id']], 'categorys');
            //  Update categorys_link_products table
            $this->prodModel->update(['category_name' => $_POST['category']], ['category_id' => $_POST['id']], 'categorys_link_products');
            setHTTPCode(200, 'Success!');
        }

        /**
         * Delete category
         * @param {String} $_POST['id'] (require)
         */
        public function postDeleteCate(){
            if(empty($_POST['id'])){
                setHTTPCode(500, "Error, id is empty!");
                return;
            }

            //  Check Category If not exist
            $exist = $this->prodModel->select(NULL, ['id' => $_POST['id']], 'categorys');
            if(!$exist){
                setHTTPCode(400, 'Not Found Category!');
                return;
            } 

            //  Delete records from categorys_link_products table
            $this->prodModel->delete(['category_id' => $_POST['id']], 'categorys_link_products');
            //  Delete records from categorys table
            $this->prodModel->delete(['id' => $_POST['id']], 'categorys');
            echo "Delete success!";
        }
 
    }
    
?>