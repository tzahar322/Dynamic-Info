<?php
session_start();

// Проверка, аутентифицирован ли пользователь через сессию или куки
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} elseif (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
    $_SESSION['user_id'] = $user_id;
} else {
    header('Location: /inf/login.php');
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/inf/php/connect.php";

// Обработка добавления студента
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_student'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $sql = "INSERT INTO aa16 (name, email) VALUES ('$name', '$email')";

    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('Новая запись успешно добавлена');</script>";
    } else {
        echo "<script>alert('Ошибка: " . $sql . " " . $mysqli->error . "');</script>";
    }
}

// Обработка удаления студента
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM students WHERE id=$id";

    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('Запись успешно удалена');</script>";
    } else {
        echo "<script>alert('Ошибка при удалении записи: " . $mysqli->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список студентов</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/main.css">
    <style>
        .title1 {
            color: #4a7dc0;
            font-size: 28px; 
        }
        .btn2 {
           
        }
    </style>
</head>
<body>
<nav class="nav">
    <div class="container">
        <div class="nav-row">
            <a href="./index.html" class="logo"><strong><em>Informatics</em></strong> <em>Education</em></a>
            <ul class="nav-list">
                <li class="nav-list__item"><a href="./teacherLk.php" class="nav-list__link">Личный кабинет</a></li>
                <li class="nav-list__item"><a href="./lectures.php" class="nav-list__link">Лекции</a></li>
                <li class="nav-list__item"><a href="./logout.php" class="nav-list__link">Выход</a></li>
            </ul>
        </div>   
    </div>
</nav>
<div class="container mt-5">
    <h2 class="title1">Список студентов АА-16</h2>
    
    <form action="aa16.php" method="post" class="mb-4">
        <div class="form-row">
            <div class="col">
                <input type="text" class="form-control" name="name" placeholder="Имя" required>
            </div>
            <div class="col">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary" name="add_student">Добавить</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM aa16";
            $result = $mysqli->query($sql);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td>
                                <a href='#' class='btn2 btn-success btn-sm' onclick='alert(\"Студент будет оповещен!\")'>Оповестить студента</a>
                                <a href='index.php?delete_id={$row['id']}' class='btn1 btn-danger btn-sm' onclick='return confirm(\"Вы уверены, что хотите удалить эту запись?\")'>Удалить</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Нет записей</td></tr>";
            }

            $mysqli->close();
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
