<?php
    $unitTest = new unitTest();
    

    route('/unit/test/addcart', function(){
        global $unitTest;
        $unitTest->addcartTesting();
    });

    route('/unit/test/removeCategory', function(){
        global $unitTest;
        $unitTest->deleteCategory();
    });
    
    route('/unit/test/addCategory', function(){
        global $unitTest;
        $unitTest->addCategory();
    });

    route('/unit/test/editCategory', function(){
        global $unitTest;
        $unitTest->editCategory();
    });
    
    
?>