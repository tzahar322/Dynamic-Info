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
}?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование профиля</title>
    <!-- Подключение Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/main.css">
    <style>
        .h2{
            color: #4a7dc0;
        }
    </style>
</head>

<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/inf/php/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];
  if (!empty($password)) {
      $password_hashed = password_hash($password, PASSWORD_DEFAULT);
      $update_query = $mysqli->prepare("UPDATE Users SET email = ?, password = ? WHERE id = ?");
      $update_query->bind_param("ssi", $email, $password_hashed, $user_id);
  } else {
      $update_query = $mysqli->prepare("UPDATE Users SET email = ? WHERE id = ?");
      $update_query->bind_param("si", $email, $user_id);
  }

  if ($update_query->execute()) {
      echo "Данные успешно обновлены.";
  } else {
      echo "Ошибка обновления данных: " . $mysqli->error;
  }
}

$query = $mysqli->prepare("SELECT initials, login, email FROM Users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();
?>


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

<div class="container mt-5">
    <h2 class="mb-4">Редактирование профиля</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="initials">ФИО:</label>
            <input type="text" class="form-control" id="initials" name="initials" value="<?php echo htmlspecialchars($user['initials']); ?>" disabled>
        </div>
        <div class="form-group">
            <label for="username">Имя пользователя:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['login']); ?>" disabled>
        </div>
        <div class="form-group">
            <label for="email">Почта:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Пароль (оставьте пустым, если не хотите менять):</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
</div>

<!-- Подключение Bootstrap JS и зависимостей -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

