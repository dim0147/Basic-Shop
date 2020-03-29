<?php
class unitTest
{
    function __construct()
    {
    }

    public function addcartTesting()
    {
        $cartController = $this->setUpCart();
        echo "<h1> Testing add to cart function, Location: controller > cart.php > action()</h1>\n";
        $_POST['action'] = 'add';
        //  Incorrect quantity
        // $_POST['quantity'] = 'text';
        //  Without id
        // $_POST['id'] = 1;
        $cartController->action();
    }

    public function deleteCategory()
    {
        $adminController = $this->setUpCategory();
        $_POST['id'] = 18;
        $adminController->postDeleteCate();
    }

    public function addCategory()
    {
        $adminController = $this->setUpCategory();
        $_POST['category'] = null;
        $adminController->postAddCate();
    }

    public function editCategory()
    {
        $adminController = $this->setUpCategory();
        $_POST['id'] = 40;
        $_POST['category'] = 'WAD1';
        $_POST['description'] = 1111111;
        $adminController->postEditCate();
    }

    public function setUpCart()
    {
        return new CartController();
    }

    public function setUpCategory()
    {
        return new AdminController();
    }

}

