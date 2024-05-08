<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Книги</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <?php
      if(isset($_GET['brand_id'])) {
        $brand_id = $_GET['brand_id'];
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bookstore";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        if (!$conn) {
          die("Connection failed: " . mysqli_connect_error());
        }
        $brand_sql = "SELECT * FROM authors WHERE id = ".$brand_id;
          $brand_result = mysqli_query($conn, $brand_sql);
          if (!$brand_result) {
              die("Ошибка выполнения запроса: " . mysqli_error($conn));
          }
        $brand_row = mysqli_fetch_assoc($brand_result);
        echo "<nav aria-label='breadcrumb' class='mt-3'>";
        echo "<ol class='breadcrumb'>";
        echo "<li class='breadcrumb-item'><a href='index.php'>Книги</a></li>";
        echo "</ol>";
        echo "</nav>";
        $products_sql = "SELECT * FROM book WHERE author_id = ".$brand_id;
        $products_result = mysqli_query($conn, $products_sql);
        if (mysqli_num_rows($products_result) > 0) {
          echo "<div class='card bg-light'>";
          echo "<div class='card-body'>";
          echo "<table class='table'>";
          echo "<thead><tr><th>Название</th><th>Описание</th><th>Цена</th><th>Изображение</th><th>Действия</th></tr></thead>";
          echo "<tbody>";
          while($product_row = mysqli_fetch_assoc($products_result)) {
            echo "<tr>";
            echo "<td>".$product_row["title"]."</td>";
            echo "<td>".$product_row["description"]."</td>";
            echo "<td>".$product_row["price"]."</td>";
            echo "<td><img src='".$product_row["image_url"]."' alt='Product Image' style='width:120px;height:120px;'></td>";
            echo "<td>";
            echo "<a href='edit_product.php?id=".$product_row["id"]."' class='btn btn-primary'><i class='bi bi-pencil'></i> Edit</a>";
            echo " ";
            echo "<a href='delete_product.php?id=".$product_row["id"]."&brand_id=".$brand_id."' class='btn btn-danger' onclick='return confirm(\"Вы уверены, что хотите удалить этот продукт?\");'><i class='bi bi-trash'></i> Удалить</a>";
            echo "</td>";
            echo "</tr>";
          }
          echo "</tbody></table>";
          echo "</div></div>";
        } else {
          echo "<p class='mt-3'>Автор не был найден</p>";
        }
        mysqli_close($conn);
      } else {
        echo "<p class='mt-3'>Идентификационный номер автора не указан</p>";
      }
    ?>

  <div class="text-end">
        <a href="add_product.php<?php if(isset($brand_id)) echo "?brand_id=".$brand_id; ?>" class="btn btn-primary">Добавить книгу</a>
      </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://unpkg.com/bootstrap-icons@1.7.0/font/bootstrap-icons.js"></script>

</body>
</html>
