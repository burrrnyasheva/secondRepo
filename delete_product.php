<?php
require_once 'ProductManager.php';

$productManager = new ProductManager();

if(isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $brand_id = isset($_GET['brand_id']) ? $_GET['brand_id'] : null;

    $result = $productManager->deleteProduct($product_id);

    if($result === true) {
        if ($brand_id !== null) {
            header("Location: products.php?brand_id=" . $brand_id);
        } else {
            header("Location: products.php");
        }
        exit();
    } else {
        echo "Error deleting product: " . $result;
    }
} else {
    echo "Error: Product ID is not provided";
}
?>
