<?php
session_start();

if (isset($_SESSION['user_id'])) {
  if ($_SESSION['user_role']) {
    $lk = 'teacherLk.php';
  } else {
    $lk = 'studentLk.php';
  }
  header('Location: /inf/' . $lk);
  exit;
}

if (isset($_COOKIE['user_id'])) {
  $_SESSION['user_id'] = $_COOKIE['user_id'];
  if ($_SESSION['user_role']) {
    $lk = 'teacherLk.php';
  } else {
    $lk = 'studentLk.php';
  }
  header('Location: /inf/' . $lk);
  exit;
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {
  require_once $_SERVER['DOCUMENT_ROOT'] . "/inf/php/connect.php";
 
  $username = mysqli_real_escape_string($mysqli, $_POST['username']);
  $initials = mysqli_real_escape_string($mysqli, $_POST['initials']);
  $password = mysqli_real_escape_string($mysqli, $_POST['password']);
  $cpassword = mysqli_real_escape_string($mysqli, $_POST['cpassword']);
  $email = mysqli_real_escape_string($mysqli, $_POST['email']);
  $remember_me = isset($_POST['remember_me']);
  $is_admin = $_POST['role'] == 'teacher' ? 1 : 0;

  if ($password != $cpassword) {
    $error_message = "Пароли не совпадают!";
  } else {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM Users WHERE login = '$username'";
    $result = mysqli_query($mysqli, $sql);

    if (mysqli_num_rows($result) > 0) {
      $error_message = "Имя пользователя уже занято!";
    } else {
      $sql = "INSERT INTO Users (login, initials, password, email, is_admin) VALUES ('$username', '$initials', '$hashed_password', '$email', '$is_admin')";
      if (mysqli_query($mysqli, $sql)) {
        $user_id = mysqli_insert_id($mysqli);

        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_role'] = $is_admin;

        if ($remember_me) {
          setcookie('user_id', $user_id, time() + (30 * 24 * 60 * 60), "/");
          setcookie('user_role', $is_admin, time() + (30 * 24 * 60 * 60), "/");
        } else {
          setcookie('user_id', $user_id, 0, "/");
          setcookie('user_role', $is_admin, 0, "/");
        }

        $location = $_SESSION['user_role'] ? 'teacherLk.php' : 'studentLk.php';
        header('Location: /inf/' . $location);
      } else {
        $error_message = "Ошибка: " . mysqli_error($mysqli);
      }
    }
  }
  mysqli_close($mysqli);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Регистрация</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons'>
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="css/style.css">
  
</head>
<body>
<nav class="nav">
        <div class="container">
            <div class="nav-row">
                <a href="./index.html" class="logo"><strong><em>Informatics</em></strong> <em>Education</em></a>
                <ul class="nav-list">
                    
                    <li class="nav-list__item"><a href="./test.php" class="nav-list__link">Тестирование</a></li>
                    <li class="nav-list__item"><a href="./contacts.html" class="nav-list__link">Контакты</a></li>
                    <li class="nav-list__item"><a href="./login.php" class="nav-list__link">Авторизация</a></li>
                    
                </ul>
            </div>   
          </div>
</nav>        
  <div class="form">
    <div class="form-toggle"></div>
    <div class="form-panel one">
      <div class="form-header">
        <h1>Регистрация</h1>
        <?php if (!empty($error_message)): ?>
          <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
      </div>
      <div class="form-content">
        <form action="" method="POST">
          <div class="form-group">
            <label for="initials">Ваше ФИО</label>
            <input type="text" id="initials" name="initials" required="required"/>
          </div>   
          <div class="form-group">
            <label for="username">Имя пользователя</label>
            <input type="text" id="username" name="username" required="required"/>
          </div>
          <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" required="required"/>
          </div>
          <div class="form-group">
            <label for="cpassword">Подтвердите пароль</label>
            <input type="password" id="cpassword" name="cpassword" required="required"/>
          </div>
          <div class="form-group">
            <label for="email">Ваш Email</label>
            <input type="email" id="email" name="email" required="required"/>
          </div>
          <div class="form-group-radio">
            <div>
              <input type="radio" name="role" value="teacher" required="required">
              <label>Я преподаватель</label>
            </div>
            <div>
              <input type="radio" name="role" value="student" required="required"> 
              <label>Я студент</label>
            </div>
          </div>
          <div class="form-group-remember">
            <input type="checkbox" name="remember_me"/>
            <label class="form-remember">Запомнить меня</label>
          </div>
          <div class="form-group">
            <button type="submit">Зарегистрироваться</button>
          </div>
        </form>
        <style>
        .btn2 {
          text-decoration: none;
          color: #4a7dc0;
             }
      </style>
        <a href="./login.php" class="btn2">Войти в учетную запись</a>
      </div>
    </div>
  </div>
</body>
</html>
