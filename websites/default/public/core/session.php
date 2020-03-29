<?php
session_start();
if (!isset($_SESSION['cart']))
{
    $_SESSION['cart'] = ["items" => [], "totalPrice" => 0, "totalQty" => 0];
}
?>
