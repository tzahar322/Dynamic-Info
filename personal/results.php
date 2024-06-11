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
    <title>Результаты теста №1</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/main.css">
    <style>
        body {
            background-color: #fff;
        }
        .containerRes {
            max-width: 800px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 30px auto; /* Центрируем контейнер по горизонтали и добавляем отступы сверху и снизу */
        }
        .table {
            margin-top: 20px;
            font-size: 18px; /* Увеличиваем размер шрифта таблицы */
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-size: 18px; /* Увеличиваем размер шрифта кнопок */
            padding: 10px 20px; /* Увеличиваем внутренние отступы кнопок */
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .alert {
            font-size: 18px; /* Увеличиваем размер шрифта для сообщений */
        }
        /* Добавляем стиль для ячейки с результатом больше 8 */
        .result-green {
            background-color: #c3e6cb; /* Зеленый фон */
        }
        /* Добавляем стиль для ячейки с результатом меньше 4 */
        .result-red {
            background-color: #f5c6cb; /* Красный фон */
        }
        h1 {
            color: #26292D; /* Цвет заголовка */
            font-size: 36px; /* Увеличиваем размер заголовка */
            text-align: center; /* Выравниваем по центру */
            margin-bottom: 30px; /* Добавляем отступ снизу */
        }
    </style>
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

    <div class="containerRes">
        <h1>Результаты теста №1</h1>
        <?php
        require_once $_SERVER['DOCUMENT_ROOT'] . "/inf/php/connect.php";

        $sql = "SELECT user_name, score FROM results";
        $results = $mysqli->query($sql);
         
        if ($results->num_rows > 0) {
            echo "<table class='table table-bordered'>";
            echo "<thead><tr><th>Имя</th><th>Результат</th></tr></thead>";
            echo "<tbody>";
            while($row = $results->fetch_assoc()) {
                // Применяем стиль для ячейки, в зависимости от результата
                if ($row['score'] > 8) {
                    echo "<tr><td>" . $row['user_name'] . "</td><td class='result-green'>" . $row['score'] . "</td></tr>";
                } elseif ($row['score'] < 4) {
                    echo "<tr><td>" . $row['user_name'] . "</td><td class='result-red'>" . $row['score'] . "</td></tr>";
                } else {
                    echo "<tr><td>" . $row['user_name'] . "</td><td>" . $row['score'] . "</td></tr>";
                }
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-info'>Нет результатов для отображения.</div>";
        }

        $mysqli->close();
        ?>
        
        <a href="teacherLk.php" class="btn btn-primary btn-block mt-3">Вернуться в личный кабинет</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>