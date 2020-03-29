<?php

$adminController = new AdminController();

route('/admin/dashboard', function ()
{
    global $adminController;
    // NOTE: DEBUG
    $isAdmin = $adminController->checkAdmin();
    if (!$isAdmin) return;
    $adminController->dashboard();
});

route('/admin/add-product', function ()
{
    global $adminController;
    $isAdmin = $adminController->checkAdmin();
    if (!$isAdmin) return;
    $adminController->addProductIndex();
});

route('/admin/post/add-product', function ()
{
    global $adminController;
    $isAdmin = $adminController->checkAdmin();
    if (!$isAdmin) return;
    $adminController->postAddProduct();
});

route('/admin/show-category', function ()
{
    global $adminController;
    $isAdmin = $adminController->checkAdmin();
    if (!$isAdmin) return;
    $adminController->showCategoryIndex();
});

route('/admin/add-category', function ()
{
    global $adminController;
    $isAdmin = $adminController->checkAdmin();
    if (!$isAdmin) return;
    $adminController->addCateIndex();
});

route('/admin/post/add-category', function ()
{
    global $adminController;
    $isAdmin = $adminController->checkAdmin();
    if (!$isAdmin) return;
    $adminController->postAddCate();
});

route('/admin/post/delete-category', function ()
{
    global $adminController;
    $isAdmin = $adminController->checkAdmin();
    if (!$isAdmin) return;
    $adminController->postDeleteCate();
});

route('/admin/edit-category', function ()
{
    global $adminController;
    $isAdmin = $adminController->checkAdmin();
    if (!$isAdmin) return;
    $adminController->editCateIndex();
});

route('/admin/post/edit-category', function ()
{
    global $adminController;
    $isAdmin = $adminController->checkAdmin();
    if (!$isAdmin) return;
    $adminController->postEditCate();
});

route('/admin/show-product', function ()
{
    global $adminController;
    $isAdmin = $adminController->checkAdmin();
    if (!$isAdmin) return;
    $adminController->showProductIndex();
});


route('/admin/show-user', function ()
{
    global $adminController;
    $isAdmin = $adminController->checkAdmin();
    if (!$isAdmin) return;
    $adminController->showUserIndex();
});

route('/admin/edit-product?{id}', function ()
{
    global $adminController;
    $isAdmin = $adminController->checkAdmin();
    if (!$isAdmin) return;
    $adminController->editProductIndex();
});

route('/admin/post/edit-product/', function ()
{
    global $adminController;
    $isAdmin = $adminController->checkAdmin();
    if (!$isAdmin) return;
    $adminController->postEditProduct();
});

route('/admin/post/remove-product/', function ()
{
    global $adminController;
    $isAdmin = $adminController->checkAdmin();
    if (!$isAdmin) return;
    $adminController->postRemoveProduct();
});

route('/admin/login', function ()
{
    global $adminController;
    $adminController->loginIndex();
});

route('/admin/post/login', function ()
{
    global $adminController;
    $adminController->postLogin();
});

route('/admin/register', function ()
{
    global $adminController;
    $adminController->registerIndex();
});

route('/admin/post/register', function ()
{
    global $adminController;
    $adminController->postRegister();
});

?>
