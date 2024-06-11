<?
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
    if ($_SESSION['user_role']) {
        header('Location: /inf/teacherLk.php');   
        exit;   
    }
} elseif (isset($_COOKIE['user_role'])) {
    $_SESSION['user_role'] = $_COOKIE['user_role'];
    if ($_COOKIE['user_role']) {
        header('Location: /inf/teacherLk.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет студента</title>
    <link rel="stylesheet" href="./css/main.css">
</head>
<body>
    <nav class="nav">
        <div class="container">
            <div class="nav-row">
                <a href="./index.html" class="logo"><strong><em>Informatics</em></strong> <em>Education</em></a>
                <ul class="nav-list">
                    
                    <li class="nav-list__item"><a href="./testMenu.php" class="nav-list__link">Тестирование</a></li>
                    <li class="nav-list__item"><a href="./lectures.php" class="nav-list__link">Лекции</a></li>
                    <li class="nav-list__item"><a href="./logout.php" class="nav-list__link">Выход</a></li>
                    
                </ul>
            </div>   
        </div>
        
    </nav>
    <main class="section">
        <div class="container">
            <h2 class="title1">Личный кабинет студента</h2>
            <ul class="projects">
                <li class="project">
                    <img src="./img/facts/6.jpg" alt="Project img" class="project__img">
                    <h1 class="project__title"><a href="./testMenu.php">Тестирование</a></h1>
                </li>
                <li class="project">
                    <img src="./img/facts/5.jpg" alt="Project img" class="project__img">
                    <h2 class="project__title"><a href="./profile.php">Личные данные</a></h2>
                </li>
            </ul>
        </div>
    </main>
</body>
</html>