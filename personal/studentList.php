<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} elseif (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
    $_SESSION['user_id'] = $user_id;
} else {
    header('Location: /inf/login.php');
    exit;
}

if (isset($_SESSION['user_role'])) {
    if (!$_SESSION['user_role']) {
        header('Location: /inf/studentLk.php');   
        exit;   
    }
} elseif (isset($_COOKIE['user_role'])) {
    $_SESSION['user_role'] = $_COOKIE['user_role'];
    if (!$_COOKIE['user_role']) {
        header('Location: /inf/studentLk.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список студентов</title>
    <link rel="stylesheet" href="./css/main.css">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .title1 {
            color: #4a7dc0; 
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
    <h2 class="title1">Списки групп и студентов</h2>

    <div class="container mt-5">
        <ul class="list-group">
            <li class="list-group-item"><a href="./aa16.php" class="text-decoration-none">Группа АА-16</a></li>
            <li class="list-group-item"><a href="#" class="text-decoration-none">Группа АА-17</a></li>
            <li class="list-group-item"><a href="#" class="text-decoration-none">Добавить группу...</a></li>
            <li class="list-group-item"><a href="#" class="text-decoration-none">Добавить группу...</a></li>
        </ul>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
