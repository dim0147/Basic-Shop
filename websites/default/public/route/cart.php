<?php 

    $cart = new CartController();

    route('/cart/action', function(){
        global $cart;
        $cart->action();
    });

    route('/cart/show', function(){
        $_POST['action'] = 'show';
        global $cart;
        $cart->action();
    });

    route('/cart/checkout', function(){
        global $cart;
        $cart->checkout();
    });

    route('/cart/post/checkout', function(){
        global $cart;
        $cart->postCheckout();
    });

    route('/cart/checkout/success', function(){
        global $cart;
        $cart->successCheckoutRender();
    });

?>