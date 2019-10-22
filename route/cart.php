<?php 

    $cart = new CartController();

    route('/cart', function(){
        global $cart;
        $cart->action();
    });

?>