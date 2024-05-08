<?php

class ProductManager{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "bookstore";
    private $conn;

    public function __construct() {
        $this->conn = mysqli_connect($this->servername, $this->username, $this->password, $this->dbname);

        // Проверка соединения
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Функция для обработки загруженного изображения
    function processImageUpload($file, $upload_dir) {
        // Проверяем, был ли загружен файл без ошибок
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return "Upload error: " . $file['error'];
        }

        $mime_type = mime_content_type($file['tmp_name']);
        if (strpos($mime_type, 'image') === false) {
            return "Error: File is not an image.";
        }

        $file_name = uniqid() . '_' . $file['name'];

        // Формируем путь для сохранения изображения
        $upload_path = $upload_dir . '/' . $file_name;

        // Проверяем, существует ли уже файл с таким именем
        while (file_exists($upload_path)) {
            // Если файл существует, добавляем случайное число к имени файла
            $file_name = uniqid() . '_' . $file['subject'];
            $upload_path = $upload_dir . '/' . $file_name;
        }

        // Перемещаем файл из временной директории в целевую директорию
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            // Возвращаем путь к загруженному изображению
            return $upload_path;
        } else {
            return "Error: Failed to move uploaded file.";
        }
    }

    public function getProductInfo($id) {
        $id = mysqli_real_escape_string($this->conn, $id);

        $sql = "SELECT * FROM book WHERE author_id='$id'";
        $result = mysqli_query($this->conn, $sql);

        if(mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        } else {
            return null;
        }
    }

    // Функция для добавления нового продукта
    public function addProduct($teacher, $description, $duration, $brand_id, $image_url) {
        $teacher = mysqli_real_escape_string($this->conn, $teacher);
        $description = mysqli_real_escape_string($this->conn, $description);
        $duration = mysqli_real_escape_string($this->conn, $duration);
        $brand_id = mysqli_real_escape_string($this->conn, $brand_id);
        $image_url = mysqli_real_escape_string($this->conn, $image_url);

        $sql = "INSERT INTO book (title, description, price, author_id, image_url) VALUES ('$teacher', '$description', '$duration', '$brand_id', '$image_url')";

        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return "Error: " . $sql . "<br>" . mysqli_error($this->conn);
        }
    }

    // Функция для редактирования продукта
    public function editProduct($id, $teacher, $description, $duration, $brand_id, $image_url) {
        $id = mysqli_real_escape_string($this->conn, $id);
        $teacher = mysqli_real_escape_string($this->conn, $teacher);
        $description = mysqli_real_escape_string($this->conn, $description);
        $duration = mysqli_real_escape_string($this->conn, $duration);
        $brand_id = mysqli_real_escape_string($this->conn, $brand_id);
        $image_url = mysqli_real_escape_string($this->conn, $image_url);

        // SQL запрос
        $sql = "UPDATE book SET title='$teacher', description='$description', price='$duration', author_id='$brand_id'";

        // Проверяем, если передан URL изображения, то обновляем его
        if (!empty($image_url)) {
            $sql .= ", image_url='$image_url'";
        }

        // Завершаем SQL запрос
        $sql .= " WHERE id='$id'";

        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return "Error: " . $sql . "<br>" . mysqli_error($this->conn);
        }
    }




  // Функция для удаления продукта
  public function deleteProduct($id) {
    // Подготавливаем запрос на выборку информации о продукте, включая путь к изображению
    $stmt_select = mysqli_prepare($this->conn, "SELECT image_url FROM book WHERE id=?");
    mysqli_stmt_bind_param($stmt_select, "i", $id);
    mysqli_stmt_execute($stmt_select);
    mysqli_stmt_store_result($stmt_select);

    // Проверяем, найдена ли запись
    if(mysqli_stmt_num_rows($stmt_select) > 0) {
        // Привязываем результаты запроса к переменным
        mysqli_stmt_bind_result($stmt_select, $image_url);
        mysqli_stmt_fetch($stmt_select);

        // Закрываем запрос
        mysqli_stmt_close($stmt_select);

        // Если у записи есть изображение, удаляем его
        if(!empty($image_url) && file_exists($image_url)) {
            unlink($image_url);
        }
    } else {
        // Если продукт не найден, возвращаем сообщение об ошибке
        mysqli_stmt_close($stmt_select);
        return "Product with ID ".$id." does not exist.";
    }

    // Подготавливаем запрос на удаление записи о продукте
    $stmt_delete = mysqli_prepare($this->conn, "DELETE FROM book WHERE id=?");
    mysqli_stmt_bind_param($stmt_delete, "i", $id);

    // Выполняем запрос
    if (mysqli_stmt_execute($stmt_delete)) {
        // Если удаление прошло успешно, закрываем запрос и возвращаем true
        mysqli_stmt_close($stmt_delete);
        return true;
    } else {
        // Если произошла ошибка при удалении, закрываем запрос и возвращаем сообщение об ошибке
        $error_message = "Error: " . mysqli_error($this->conn);
        mysqli_stmt_close($stmt_delete);
        return $error_message;
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Функция для получения информации о бренде по его идентификатору
    public function getBrandInfo($id) {
        $id = mysqli_real_escape_string($this->conn, $id);

        $sql = "SELECT * FROM authors WHERE id='$id'";
        $result = mysqli_query($this->conn, $sql);

        if($result !== false) {
            if(mysqli_num_rows($result) > 0) {
                return mysqli_fetch_assoc($result);
            } else {
                return null;
            }
        } else {
            return "Error in query: " . mysqli_error($this->conn);
        }
    }

    // Функция для редактирования бренда
    public function editBrand($brand_id, $subject) {
        $subject = mysqli_real_escape_string($this->conn, $subject);
        $brand_id = mysqli_real_escape_string($this->conn, $brand_id);
        $sql = "UPDATE authors SET author='$subject' WHERE id='$brand_id'";
        if(mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return "Error updating brand: " . mysqli_error($this->conn);
        }

    }

    // Функция для удаления бренда
    public function deleteBrand($id) {
        $id = mysqli_real_escape_string($this->conn, $id);

        $sql = "DELETE FROM authors WHERE id='$id'";
        if(mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return "Error deleting brand: " . mysqli_error($this->conn);
        }
    }

    // Функция для добавления нового бренда
    public function addBrand($subject) {
        $subject = mysqli_real_escape_string($this->conn, $subject);

        $sql = "INSERT INTO authors (author) VALUES ('$subject')";

        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return "Error: " . $sql . "<br>" . mysqli_error($this->conn);
        }
    }

    public function getProductCountByBrand($brand_id) {
        // Подготавливаем запрос
        $stmt = mysqli_prepare($this->conn, "SELECT COUNT(*) FROM book WHERE author_id=?");

        // Привязываем параметры
        mysqli_stmt_bind_param($stmt, "i", $brand_id);

        // Выполняем запрос
        mysqli_stmt_execute($stmt);

        // Получаем результат запроса
        mysqli_stmt_bind_result($stmt, $count);

        // Извлекаем значение результата
        mysqli_stmt_fetch($stmt);

        // Закрываем запрос
        mysqli_stmt_close($stmt);

        // Возвращаем количество продуктов
        return $count;
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Функция для закрытия соединения с базой данных
    public function closeConnection() {
        mysqli_close($this->conn);
    }
}

?>
