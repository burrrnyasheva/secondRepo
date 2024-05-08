<?php
require_once 'ProductManager.php';

$productManager = new ProductManager();

if(isset($_GET['brand_id']) && is_numeric($_GET['brand_id'])) {
    $brand_id = $_GET['brand_id'];
} else {
    $error_message = "Error: Brand ID is missing or invalid.";
}

// Проверяем, была ли отправлена форма для добавления продукта
if(isset($_POST['submit'])) {
    // Проверяем, что все поля заполнены
    if(empty($_POST['teacher']) || empty($_POST['description']) || empty($_POST['duration']) || empty($_POST['brand_id']) || empty($_FILES['image']['name'])) {
        // Устанавливаем сообщение об ошибке
        $error_message = "Error: All fields are required.";
    } else {
        // Получаем данные из формы
        $teacher = htmlspecialchars($_POST['teacher']);
        $description = htmlspecialchars($_POST['description']);
        $duration = htmlspecialchars($_POST['duration']);
        $brand_id = htmlspecialchars($_POST['brand_id']);
        $image_url = '';

        // Проверяем, было ли загружено изображение
        if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_name = $_FILES['image']['name'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_url = 'inc/images/' . $image_name;

            // Проверяем, не существует ли уже файл с таким именем
            while(file_exists($image_url)) {
                $random_suffix = rand(10, 99); // Генерируем случайное двузначное число
                $image_name = $random_suffix . '_' . $image_name; // Добавляем случайный суффикс к имени файла
                $image_url = 'inc/images/' . $image_name;
            }

            // Перемещаем загруженное изображение в нужную директорию
            if(move_uploaded_file($image_tmp_name, $image_url)) {
                // Вызываем метод добавления продукта из класса ProductManager
                $result = $productManager->addProduct($teacher, $description, $duration, $brand_id, $image_url);

                // Проверяем результат добавления
                if($result === true) {
                    // Редирект на страницу со списком продуктов или другую страницу
                    header("Location: products.php?brand_id=" . $brand_id);
                    exit(); // Прекращаем выполнение скрипта после перенаправления
                } else {
                    // Устанавливаем сообщение об ошибке
                    $error_message = "Error adding product: " . $result;
                }
            } else {
                // Если не удалось переместить файл, устанавливаем сообщение об ошибке
                $error_message = "Error: Failed to move uploaded file.";
            }
        } else {
            // Если изображение не было загружено, устанавливаем сообщение об ошибке
            $error_message = "Error: Image upload failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mb-4">Добавить книгу</h1>
    <!-- Плашка с сообщением об ошибке -->
    <?php if(isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="teacher" class="form-label">Название</label>
            <input type="text" class="form-control" id="teacher" name="teacher">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <input type="text" class="form-control" id="description" name="description">
        </div>
        <div class="mb-3">
            <label for="duration" class="form-label">Цена</label>
            <input type="text" class="form-control" id="duration" name="duration">
        </div>
        <div class="mb-3">
            <label for="brand_id" class="form-label">ID автора</label>
            <input type="text" class="form-control" id="brand_id" name="brand_id" value="<?php echo $brand_id; ?>">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Изображение</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Добавить</button>
    </form>
</div>