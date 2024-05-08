<?php
require_once 'ProductManager.php';

$productManager = new ProductManager();

$error_message = '';

if(isset($_POST['submit'])) {
    if( empty($_POST['author']) ) {
        $error_message = "Ошибка: Все поля обязательны для заполнения.";
    } else {
        $subject = $_POST['author'];
        $result = $productManager->addBrand($subject);

        if($result === true) {
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Error adding brand: " . $result;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить автора</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mb-4">Добавить автора</h1>
    <?php if(!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Автор</label>
            <input type="text" class="form-control" id="name" name="author">
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Добавить</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
