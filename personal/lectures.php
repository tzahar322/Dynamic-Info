<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/inf/php/connect.php";
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file_name = $_FILES['file']['name'];
    $file_path = 'uploads/' . basename($file_name);
    
    if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
        $stmt = $mysqli->prepare("INSERT INTO files (file_name, file_path) VALUES (?, ?)");
        $stmt->bind_param("ss", $file_name, $file_path);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error uploading file.";
    }
}


// Обработка удаления файла
if (isset($_GET['delete'])) {
    $file_id = $_GET['delete'];
    $stmt = $mysqli->prepare("SELECT file_path FROM files WHERE id = ?");
    $stmt->bind_param("i", $file_id);
    $stmt->execute();
    $stmt->bind_result($file_path);
    $stmt->fetch();
    $stmt->close();
    
    if (file_exists($file_path)) {
        unlink($file_path);
    }
    
    $stmt = $mysqli->prepare("DELETE FROM files WHERE id = ?");
    $stmt->bind_param("i", $file_id);
    $stmt->execute();
    $stmt->close();
}

// Получение списка файлов из базы данных
$result = $mysqli->query("SELECT id, file_name, file_path FROM files");
$files = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $files[] = $row;
    }
}
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лекционные материалы</title>
    <link rel="stylesheet" href="./css/main.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .centered-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin-top: 50px;
        }
        .content {
            width: 100%;
            max-width: 600px;
        }
        .file-list {
            list-style-type: none;
            padding: 0;
        }
        .file-list li {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .file-list a {
            margin-right: 20px;
        }
        .delete-button {
            background-color: #ff4d4d;
            border: none;
            color: white;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
        }
        .delete-button:hover {
            background-color: #ff1a1a;
        }
        .btn-danger {
            display: flex;
            align-items: center;
            justify-content: center;
        }

    </style>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="nav">
        <div class="container">
            <div class="nav-row">
                <a href="./index.html" class="logo"><strong><em>Informatics</em></strong> <em>Education</em></a>
                <ul class="nav-list">
                    
                <li class="nav-list__item"><a href="./studentLk.php" class="nav-list__link">Личный кабинет</a></li>
                    <li class="nav-list__item"><a href="./lectures.php" class="nav-list__link">Лекции</a></li>
                    <li class="nav-list__item"><a href="./logout.php" class="nav-list__link">Выход</a></li>
                    
                </ul>
            </div>   
        </div>
        
    </nav>
   
    <div class="container centered-container">
        <div class="content">
            <h1 class="mb-4 text-center">Список лекций и файлов доступных для скачивания</h1>
            <ul class="list-group mb-4">
                <?php foreach ($files as $file): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?php echo $file['file_path']; ?>" download><?php echo $file['file_name']; ?></a>
                        <?if ($_COOKIE['user_role'] == 1):?>
                            <a href="?delete=<?php echo $file['id']; ?>" class="btn btn-danger btn-sm">Удалить</a>
                        <?endif;?>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <?if ($_COOKIE['user_role'] == 1):?>
                <h2 class="text-center">Добавить новый файл</h2>
                
                <form action="" method="post" enctype="multipart/form-data" class="mt-3">
                    <div class="form-group">
                        <input type="file" name="file" class="form-control-file" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Загрузить</button>
                </form>
            <?endif;?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>