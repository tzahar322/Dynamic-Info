<?
session_start();

if (isset($_SESSION['user_id'])) {
  if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role']) {
      $lk = 'teacherLk.php';
    } else {
      $lk = 'studentLk.php';
    }
  } elseif (isset($_COOKIE['user_role'])) {
    $_SESSION['user_role'] = $_COOKIE['user_role'];
    if ($_COOKIE['user_role']) {
      $lk = 'teacherLk.php';
    } else {
      $lk = 'studentLk.php';
    }
  }
  
  header('Location: /inf/' . $lk);
  exit;
}

if (isset($_COOKIE['user_id'])) {
  $_SESSION['user_id'] = $_COOKIE['user_id'];
  if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role']) {
      $lk = 'teacherLk.php';
    } else {
      $lk = 'studentLk.php';
    }
  } elseif (isset($_COOKIE['user_role'])) {
    $_SESSION['user_role'] = $_COOKIE['user_role'];
    if ($_COOKIE['user_role']) {
      $lk = 'teacherLk.php';
    } else {
      $lk = 'studentLk.php';
    }
  }
  header('Location: /inf/' . $lk);
  exit;
}

if (!empty($_POST)) {
  require_once $_SERVER['DOCUMENT_ROOT'] . "/inf/php/connect.php";
  $login = mysqli_real_escape_string($mysqli, $_POST['username']);
  $password = mysqli_real_escape_string($mysqli, $_POST['password']);
  $remember_me = isset($_POST['remember_me']);
  $error_message = '';

  $sql = "SELECT * FROM Users WHERE login = '$login'";
  $result = mysqli_query($mysqli, $sql);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    if (password_verify($password, $row['password'])) {

      $_SESSION['user_id'] = $row['id'];
      $_SESSION['user_role'] = $row['is_admin'];

      if ($remember_me) {
        setcookie('user_id', $row['id'], time() + (30 * 24 * 60 * 60), "/");
        setcookie('user_role', $row['is_admin'], time() + (30 * 24 * 60 * 60), "/");
      } else {
        setcookie('user_id', $row['id'], 0, "/");
        setcookie('user_role', $row['is_admin'], 0, "/");
      }

      $location = $_SESSION['user_role'] ? 'teacherLk.php' : 'studentLk.php';
      header('Location: /inf/' . $location);
      exit;
    } else {
      $error_message = "Неверный логин или пароль";
    }
  } else {
    $error_message = "Неверный логин или пароль";
  }

  mysqli_close($mysqli);
}?>

<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Авторизация</title>
  
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
            <li class="nav-list__item"><a href="./login.php" class="nav-list__link">Тестирование</a></li>
                <li class="nav-list__item"><a href="./contacts.html" class="nav-list__link">Контакты</a></li>
                <li class="nav-list__item"><a href="./login.php" class="nav-list__link">Авторизация</a></li>
                
            </ul>
        </div>   
    </div>
    
</nav>
<!-- Form-->
<div class="form">
  <div class="form-toggle"></div>
  <div class="form-panel one">
    <div class="form-header">
      <h1>Авторизация</h1>
      <?php if (!empty($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
      <?php endif; ?>
    </div>
    <div class="form-content">
      <form action="" method="POST">
        <div class="form-group">
          <label for="username">Имя пользователя</label>
          <input type="text" id="username" name="username" required="required"/>
        </div>
        <div class="form-group">
          <label for="password">Пароль</label>
          <input type="password" id="password" name="password" required="required"/>
        </div>
        <div class="form-group">
          <label class="form-remember">
          <input type="checkbox" name="remember_me"/>Запомнить меня
        </div>
        <div class="form-group">
          <button type="submit">Войти</button>
        </div>
      </form>
      <style>
        .btn1 {
          text-decoration: none;
          color: #4a7dc0;
             }
      </style>
       <a href="./register.php" class="btn1">Нет аккаунта? Зарегистрироваться</a>
    </div>
  </div>
  



</body>
</html>