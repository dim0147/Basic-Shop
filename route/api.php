<?php 

    $cartAPI = new CartAPIController();

    route('/api/cart', function(){
        global $cartAPI;
        $cartAPI->navigate();
    });

?>