<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Изменить автора</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            border-radius: 20px 20px 0 0; /* Скругленные верхние углы */
        }
        .rounded-text {
            background-color: #e9ecef;
            padding: 20px;
            border-radius: 0 0 10px 10px; /* Скругленные нижние углы */
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            <h1 class="m-0">Изменить автора</h1>
        </div>
        <div class="card-body rounded-text">
            <?php
            require_once 'ProductManager.php';

            $productManager = new ProductManager();

            if(isset($_GET['id'])) {
                $brand_id = $_GET['id'];
                if(isset($_POST['submit'])) {
                    $subject = $_POST['author'];
                    $result = $productManager->editBrand($brand_id, $subject);
                    $result = $productManager->editBrand($brand_id, $subject);

                    if($result === true) {

                        header("Location: index.php");
                        exit();
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Ошибка обновления предмета: ' . $result . '</div>';
                    }
                }

                $brand_info = $productManager->getBrandInfo($brand_id);
                ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Предмет</label>
                        <input type="text" class="form-control" id="name" name="author" value="<?php echo $brand_info['author']; ?>">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Изменить</button>
                </form>
                <?php
            } else {
                echo "ID не указан";
            }
            ?>
        </div>
    </div>
</div>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
