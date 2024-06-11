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
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Создание теста</title>
    <!-- Подключение Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/main.css">
    <style>
        .mb-4 {
            font-size: 26px;
            color: #4a7dc0; 
        }
        .input-group-text {
            width: 150px;
        }
        .form-check-input {
            margin-left: 0;
            margin-right: 10px;
        }
        .form-check-label {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <nav class="nav">
        <div class="container">
            <div class="nav-row">
                <a href="./index.html" class="logo"><strong><em>Informatics</em></strong> <em>Education</em></a>
                <ul class="nav-list">
                    <li class="nav-list__item"><a href="./studentList.php" class="nav-list__link">Списки студентов</a></li>
                    <li class="nav-list__item"><a href="./results.php" class="nav-list__link">Результаты тестирования</a></li>
                    <li class="nav-list__item"><a href="./lectures.php" class="nav-list__link">Лекции</a></li>
                    <li class="nav-list__item"><a href="./logout.php" class="nav-list__link">Выход</a></li>
                </ul>
            </div>   
        </div>
    </nav>
    <div class="container mt-5">
        <h1 class="mb-4">Создание теста</h1>
        <form action="save_question.php" method="post">
            <div class="form-group">
                <label for="question_text">Напишите текст вашего вопроса:</label>
                <textarea class="form-control" id="question_text" name="question_text" rows="4" required></textarea>
            </div>
            
            <label>Ответы:</label>
            <div class="form-group">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="answers[]" placeholder="Ответ 1" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <input type="checkbox" class="form-check-input" name="correct_answers[]" value="0">
                        </div>
                    </div>
                    <div class="input-group-append">
                        <span class="input-group-text">Правильный ответ</span>
                    </div>
                </div>
                
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="answers[]" placeholder="Ответ 2" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <input type="checkbox" class="form-check-input" name="correct_answers[]" value="1">
                        </div>
                    </div>
                    <div class="input-group-append">
                        <span class="input-group-text">Правильный ответ</span>
                    </div>
                </div>
                
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="answers[]" placeholder="Ответ 3" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <input type="checkbox" class="form-check-input" name="correct_answers[]" value="2">
                        </div>
                    </div>
                    <div class="input-group-append">
                        <span class="input-group-text">Правильный ответ</span>
                    </div>
                </div>
                
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="answers[]" placeholder="Ответ 4" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <input type="checkbox" class="form-check-input" name="correct_answers[]" value="3">
                        </div>
                    </div>
                    <div class="input-group-append">
                        <span class="input-group-text">Правильный ответ</span>
                    </div>
                </div>
            </div>

            <label>Выберите вариант вопроса:</label>
            <div class="form-group">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="radio" class="form-check-input" name="single_choice" value="option1" required>
                        </div>
                    </div>
                    <input type="text" class="form-control" placeholder="Вопрос с одиночным выбором" readonly>
                </div>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="radio" class="form-check-input" name="single_choice" value="option2" required>
                        </div>
                    </div>
                    <input type="text" class="form-control" placeholder="Вопрос с множественным выбором" readonly>
                </div>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="radio" class="form-check-input" name="single_choice" value="option3" required>
                        </div>
                    </div>
                    <input type="text" class="form-control" placeholder="Вопрос с упорядочиванием" readonly>
                </div>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="radio" class="form-check-input" name="single_choice" value="option4" required>
                        </div>
                    </div>
                    <input type="text" class="form-control" placeholder="Вопрос с текстовым ответом" readonly>
                </div>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="radio" class="form-check-input" name="single_choice" value="option5" required>
                        </div>
                    </div>
                    <input type="text" class="form-control" placeholder="Вопрос на соответствие" readonly>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Сохранить вопрос</button>
        </form>
    </div>
    <!-- Подключение Bootstrap JS и зависимостей -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
