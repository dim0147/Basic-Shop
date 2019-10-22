<?php 
    class AdminController extends Controller{

        private $prodModel;

        function __construct(){
            $this->prodModel = new ProductModel();
            $this->fileRender = [
                'add-product' => 'admin.add-product'
            ];
        }

        public function addProduct(){
            $this->render($this->fileRender['add-product'],
            [
                'title' => 'Add Product'
            ]);
            return;
        }

        public function upload(){
            $uploadHeader = $this->uploadHeader();
            if(!$uploadHeader)
                setHTTPCode(500, 'Error while upload file');
            else
                setHTTPCode(200, 'Upload Success!');

        }

        public function uploadHeader(){
            if (empty($_FILES['header'])) 
                return false;

             if ($_FILES['header']['error'] != UPLOAD_ERR_OK || !getimagesize($_FILES['header']['tmp_name']))
                 return false;
             
             $pathUpLoad = PATH_IMAGE_UPLOAD. '/';
             $newName = createRanDomString() .  '.' . getExtFile($_FILES['header']['name']);
             while (file_exists($pathUpLoad . $newName)){
                 $newName = createRanDomString() . '.' . getExtFile($_FILES['header']['name']);
             }
             move_uploaded_file($_FILES['header']['tmp_name'], $pathUpLoad . $newName);
             return true;
        }
    }
?>