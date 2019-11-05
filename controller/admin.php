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
                'edit-category' => 'admin.edit-category'
            ];
        }
                /**
                 *    METHOD: GET
                 *    DESCRIPTION: RENDER PAGE ADD PRODUCT, GET CATEGORY LIST FROM DB AND PASS TO VIEW.
                 *    VALUES RETURN:
                 *      'title' => 'Add Product'
                 *      'categorys' => $categorys
                 */
        public function addProduct(){
            //  Get all category from db
            $categorys = $this->cateModel->getCategory();
            $this->render($this->fileRender['add-product'],
            [
                'title' => 'Add Product',
                'categorys' => $categorys
            ]);
            return;
        }


                /**
                 *    METHOD: GET
                 *    DESCRIPTION: RENDER PAGE ADD CATEGORY.
                 *    VALUES RETURN:
                 *      'title' => 'Add Category'
                 */
        public function addCateIndex(){
            $this->render($this->fileRender['add-category'], ['title' => 'Add Category']);
        }
         

                /**
                 *    METHOD: POST
                 *    DESCRIPTION: ADD NEW CATEGORY TO DB, CHECK EMPTY FIELD BEFORE ADDING.
                 *    VALUES REQUIRE:
                 *       $_POST['category'] (Require)
                 *       $_POST['description'] (Optional)
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
                 *    METHOD: GET
                 *    DESCRIPTION: Render EDIT CATEGORY Page, Check ID Field, Then get category from DB with ID,
                 *                 return that category to view.
                 *    VALUES REQUIRE:
                 *       $_GET['id'] (Require)
                 *    VALUES RETURN:
                 *       'title' => "Edit " . $category['name']
                 *       'category' => $category
                 *    TYPE:
                 *       $category = [
                 *                      "id" => Number,
                 *                      "name" => String,
                 *                      "description" => String
                 *                   ]
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
             *    METHOD: POST
             *    DESCRIPTION: Edit Category in DB, check empty field('category','id','description') then 
             *                 update table categorys and then update table categorys_link_products in DB.
             *    VALUES REQUIRE:
             *       $_POST['category'] (Require)
             *       $_POST['id'] (Require)
             *       $_POST['description'] (Optional)
             */   
        public function postEditCate(){
            if(empty($_POST['category']) || empty($_POST['id']) || !isset($_POST['description'])){
                setHTTPCode(500, "ERROR!");
                return;
            }
            //  Update categorys table
            $this->prodModel->update(['name' => $_POST['category'],'description' => $_POST['description']], ['id' => $_POST['id']], 'categorys');
            //  Update categorys_link_products table
            $this->prodModel->update(['category_name' => $_POST['category']], ['category_id' => $_POST['id']], 'categorys_link_products');
            setHTTPCode(200, 'Success!');
        }


            /**
             *    METHOD: POST
             *    DESCRIPTION: Delete category from DB with ID, require ID field then delete records from  
             *                 categorys_link_products table and then delete record from categorys table
             *    VALUES REQUIRE:
             *       $_POST['id'] (Require)
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


            /**
             *    METHOD: POST
             *    DESCRIPTION: Add new product, check field before adding new product, then check title if
             *                 product is exist return error, otherwise upload header image to local store(storage), 
             *                 get url of that image header then create new product on DB, after that upload 
             *                 thumbnail image to local  store this will return list of url image thumbnail, pass 
             *                 that list to function  uploadThumbOnDB() for upload thumbnail product to DB.
             *    VALUES REQUIRE:
             *        $_FILES['header'] (Require)
             *        $_FILES['thumbnail'] (Require)
             *        $_POST['categorys'] (Require)
             *        $_POST['title'] (Require)
             *        $_POST['description'] (Require)
             *        $_POST['price'] (Require)
             *        $_POST['status'] (Require)
             *        $_POST['rate'] (Require)
             */   
        public function postAddProduct(){
            
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
            $this->addField($imageHeader);

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


            /**
             *    METHOD: GET
             *    DESCRIPTION: Render edit product page, check empty field(id), then get product by ID, if exist 
             *                 merge product records to one element, then get all category, then remove from that list
             *                 category with category of product, when finish render to view with list category
             *                 and product.
             *    VALUES REQUIRE:
             *        $_GET['id'] (Require)
             *    VALUES RETURN:
             *        'product' => $product
             *        'category' => $categorys
             *        'title' => "Edit Product"
             *    TYPE:
             *      $product = [
             *          [id] => Number
             *          [title] => String
             *          [description] => String
             *          [price] => Number
             *          [image] => String
             *          [status] => String
             *          [rate] => Number
             *          [listImage] => Array(
             *                            [idImage] => URL Image
             *                          )
             *          [categoryName] => Array(
             *                            [idCategory] => Name Category
             *                          )
             *      ]
             * 
             *      $categorys = [ 
             *          Array(
             *              [id] => Number
             *              [name] => String
             *           )
             *      ]
             */  
        public function editProduct(){
            if(!isset($_GET['id'])){ //  check if have param
                setHTTPCode(500, "Please pass parameter!");
                return;
            }
            // Get Product
            $product = $this->prodModel->getProductWithId($_GET['id'], ['products.*, images.name, images.image_id, cp.category_id, cp.category_name']);
            if(!$product){  // if product not found
                setHTTPCode(500, "Product not found!");
                return;
            }
            $product = mergeResult(['name', 'category_name'], ['listImage', 'categoryName'], 'id', $product, ['name' => 'image_id', 'category_name' => 'category_id']);
            //  Get All category product
            $listCategoryProduct = array_values($product)[0]['categoryName'];
            $categorys = $this->cateModel->getCategory();
            //  If product category not empty, use array values because default have 1 element in array []
            if(!empty(array_values($listCategoryProduct)[0])){  
                foreach($listCategoryProduct as $key => $cate){   //  Loop through all category
                    if (in_array($cate, $listCategoryProduct))   //  if product category exist in array category
                        unset($categorys[$key]);    //  remove that category from array category
                }
            }
            else{  // product category is empty
                $product[getFirstKey($product)]['categoryName'] = [];
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
                $cateAdd = $this->validArrCate($cateAdd);
                if(!empty($cateAdd)){
                    $this->addCategory($cateAdd);   //  Query database
                }


                //  P2: Delete Category if array delete category send from client not empty
                if(!empty($cateDel)){
                    $this->deleteCategory($cateDel); //  Query database
                }
        
    
                     /******** ALL IS SUCCESS  ***********/
            setHTTPCode(200, "Update success!");
        }

        public function postLogin(){
            if(!empty($_POST['username']) && !empty($_POST['password']) && !isset($_SESSION['user'])){ 
                $username = $_POST['username'];
                $password = $_POST['password'];
                $passwordQuery = $this->model->select(NULL, ["username" => $username,
                                                                "type" => 'admin']);
                $result = isset($passwordQuery[getFirstKey($passwordQuery)]['password']);
                printB($result);
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

        public function validArrCate($arrCate){
            $newArr = [];
            // printB($arrCate);
            foreach($arrCate as $idCate => $nameCate){
                $result = $this->prodModel->select(NULL, ['category_id' => $idCate, 'product_id' => $_POST['id']], 'categorys_link_products');
                if(!$result)
                    $newArr[$idCate] = $nameCate;
            }
            return $newArr;
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
            //  Specify column to insert data
            $column = ['product_id', 'category_id', 'category_name', 'product_title'];
            
            //  Value array to insert
            $values = [];

            foreach($arrCate as $idCate => $nameCate){
                $values[] = [$_POST['id'], (int)$idCate, $nameCate, $_POST['title']];
            }

            $this->prodModel->insert($values, $column, 'categorys_link_products');
        }

        public function uploadHeader(){
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

        public function uploadThumbnail(){
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

        public function uploadThumbOnDB($listImg){
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

        public function editField($imgHeader){
            /******** UPDATE PRODUCT WITH HEADER IMAGE ***********/
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