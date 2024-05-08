<?php
require_once 'ProductManager.php';

$productManager = new ProductManager();

if(isset($_GET['id'])) {
    $product_id = $_GET['id'];

    if(isset($_POST['submit'])) {
        $teacher = $_POST['teacher'];
        $description = $_POST['description'];
        $duration = $_POST['duration'];
        $brand_id = $_POST['brand_id'];
        $image_url = $_POST['image_url']; // Путь к изображению

        // Вызываем метод редактирования продукта из класса ProductManager
        $result = $productManager->editProduct($product_id, $teacher, $description, $duration, $brand_id, $image_url);

        // Проверяем результат редактирования
        if($result === true) {
            // Редирект на страницу с продуктами
            header("Location: products.php?brand_id=" . $brand_id);
            exit(); // Прекращаем выполнение скрипта после перенаправления
        } else {
            echo "Error updating product: " . $result;
        }
    }

    // Получаем информацию о продукте для отображения в форме
    $product_info = $productManager->getProductInfo($product_id);
    ?>
    <!-- Форма для редактирования продукта -->
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Product</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="container mt-5">
        <h3>Редактировать книгу</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="teacher" class="form-label">Название</label>
                <input type="text" class="form-control" id="teacher" name="teacher" value="<?php echo $product_info['teacher']; ?>">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <input type="text" class="form-control" id="description" name="description" value="<?php echo $product_info['description']; ?>">
            </div>
            <div class="mb-3">
                <label for="duration" class="form-label">Цена</label>
                <input type="text" class="form-control" id="duration" name="duration" value="<?php echo $product_info['duration']; ?>">
            </div>
            <div class="mb-3">
                <label for="brand_id" class="form-label">ID автора</label>
                <input type="text" class="form-control" id="brand_id" name="brand_id" value="<?php echo $product_info['brand_id']; ?>">
            </div>
            <div class="mb-3">
                <label for="image_url" class="form-label">URL изображения</label>
                <input type="text" class="form-control" id="image_url" name="image_url" value="<?php echo $product_info['image_url']; ?>">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Изменить</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/bootstrap-icons@1.7.0/font/bootstrap-icons.js"></script>
    </body>
    </html>
    <?php
} else {
    echo "Product ID is not provided";
}
?>
