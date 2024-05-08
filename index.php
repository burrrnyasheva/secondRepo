<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>crud 2-х таблиц</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h1 class='bg-primary text-light p-3 rounded-top mb-3 fs-4'>CRUD 2-х таблиц</h1>
    <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Автор</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "bookstore";
            $conn = mysqli_connect($servername, $username, $password, $dbname);
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $sql = "SELECT * FROM `authors`";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td><a href='products.php?brand_id=".$row["id"]."'>".$row["author"]."</a></td>";
                    echo "<td>";
                    echo "<a href='delete_brand.php?id=".$row["id"]."' class='btn btn-danger'>Удалить</a>";
                    echo "<a href='edit_brand.php?id=".$row["id"]."' class='btn btn-primary'>Изменить</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>0 results</td></tr>";
            }
            mysqli_close($conn);
            ?>
            </tbody>
        </table>
    </div>
    <div class="text-end">
        <a href="add_brand.php" class="btn btn-primary">Добавить автора</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
