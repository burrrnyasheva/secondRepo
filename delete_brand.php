<?php
// Подключаем класс ProductManager
require_once 'ProductManager.php';

$productManager = new ProductManager();

if(isset($_GET['id'])) {
    // Получаем id из URL
    $brand_id = $_GET['id'];

    // Проверяем, есть ли продукты, связанные с этим брендом
    $products_count = $productManager->getProductCountByBrand($brand_id);

    if ($products_count > 0) {
        echo "Ошибка при удалении предмета: Есть переподавателя, связанные с этим предметом. Пожалуйста, сначала удалите все переподавателей, связанные с этим предметом.";
    } else {
        $result = $productManager->deleteBrand($brand_id);

        // Проверяем результат удаления
        if($result === true) {
            header("Location: index.php");
            exit();
        } else {
            echo "Ошибка при удалении предмета: " . $result;
        }
    }
} else {
    echo "Идентификатор предмета не указан";
}
?>
