<?php
    $unitTest = new unitTest();
    

    route('/unit/test/addcart', function(){
        global $unitTest;
        $unitTest->addcartTesting();
    });
    
    
?>