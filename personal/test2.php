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
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тест по информатике</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="./css/main.css">
    
    <style>
        body {
            background-color: #fff;
        }
        .containerTest {
            max-width: 800px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            margin-left: auto;
            margin-right: auto;
            margin-top: 90px;
        }
        .question-counter {
            margin-bottom: 20px;
        }
        .form-check {
            margin-bottom: 10px;
        }
        #sortable {
            padding-left: 0;
        }
        #sortable li {
            margin-bottom: 10px;
            cursor: move;
        }
        .btn-primary {
            background-color: #4a7dc0;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        #timer {
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            color: #26292D;
            font-size: 30px;
        }
        .match-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .match-item .form-control {
            width: 45%;
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
    <div class="containerTest">
        <h1 class="text-center mb-4">Тест по информатике №2</h1>

        <?php
        require_once $_SERVER['DOCUMENT_ROOT'] . "/inf/php/connect.php";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['user_name'])) {
                $_SESSION['user_name'] = $_POST['user_name'];
                $_SESSION['question_index'] = 0;
                $_SESSION['score'] = 0;
                $_SESSION['answers'] = [];
                $_SESSION['start_time'] = time();
            } else {
                $question_index = $_SESSION['question_index'];
                $question_id = $_POST['question_id'];
                $selected_answers = isset($_POST['answers']) ? $_POST['answers'] : [];

                if ($question_id == 6) { // Вопрос с упорядочиванием
                    $correct_order = ['Анализ требований', 'Проектирование', 'Разработка', 'Тестирование', 'Внедрение', 'Поддержка'];
                    $selected_answers_array = explode( ',', $selected_answers);
                    foreach ( $selected_answers_array as $index => $answer_text) {
                        if ($answer_text === $correct_order[$index]) {
                            $_SESSION['score']++;
                        }
                    }
                } elseif ($question_id == 10) { // Вопрос с развернутым ответом
                    $user_answer = $_POST['user_answer'];

                } elseif ($question_id == 11) { // Вопрос на соответствие
                    $correct_matches = [
                        'HTML' => 'Язык разметки',
                        'CSS' => 'Язык стилей',
                        'JavaScript' => 'Язык программирования',
                        'PHP' => 'Язык серверного программирования'
                    ];
                    foreach ($correct_matches as $key => $value) {
                        if (isset($selected_answers[$key]) && $selected_answers[$key] === $value) {
                            $_SESSION['score']++;
                        }
                    }
                } else {
                    foreach ($selected_answers as $answer_id) {
                        $sql = "SELECT is_correct FROM answers WHERE id = $answer_id";
                        $result = $mysqli->query($sql);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            if ($row['is_correct']) {
                                $_SESSION['score']++;
                            }
                        }
                    }
                }

                $_SESSION['answers'][$question_id] = $selected_answers;
                $_SESSION['question_index']++;
            }
        }

        if (!isset($_SESSION['user_name'])) {
            $user_id = $_COOKIE['user_id'];
            $sql = "SELECT initials FROM Users WHERE id = $user_id";
            $result = $mysqli->query($sql);
            $user = $result->fetch_assoc();
            $initials = $user['initials'];
            echo '<form method="POST">';
            echo '<div class="form-group">';
            echo '<input type="text" name="user_name" class="form-control" value="' . $initials . '" required hidden>';
            echo '</div>';
            echo '<button type="submit" class="btn btn-primary btn-block">Начать тест</button>';
            echo '</form>';
            echo '<a href="testMenu.php" class="btn btn-secondary btn-block mt-3">Вернуться назад</a>';
        } else {
            $question_index = $_SESSION['question_index'];

            // Получаем количество вопросов
            $total_questions_sql = "SELECT COUNT(*) AS total FROM questions";
            $total_questions_result = $mysqli->query($total_questions_sql);
            $total_questions_row = $total_questions_result->fetch_assoc();
            $total_questions = $total_questions_row['total'];

            // Получаем текущий вопрос
            $sql = "SELECT * FROM questions LIMIT 1 OFFSET $question_index";
            $question_result = $mysqli->query($sql);

            if ($question_result->num_rows > 0) {
                $question = $question_result->fetch_assoc();
                $question_id = $question['id'];
                echo '<form method="POST" id="questionForm">';
                echo '<input type="hidden" name="question_id" value="' . $question_id . '">';
                echo '<div class="form-group">';
                echo '<div class="question-counter text-right">Вопрос ' . ($question_index + 1) . ' из ' . $total_questions . '</div>';
                echo '<label>' . $question['question_text'] . '</label>';
                
                if ($question_id == 6) { // Вопрос с упорядочиванием
                    $answers = [];
                    $sql = "SELECT * FROM answers WHERE question_id = $question_id";
                    $answers_result = $mysqli->query($sql);
                    if ($answers_result->num_rows > 0) {
                        while ($answer = $answers_result->fetch_assoc()) {
                            $answers[] = $answer;
                        }
                    }
                    shuffle($answers);
                    echo '<ul id="sortable" class="list-group">';
                    foreach ($answers as $answer) {
                        echo '<li class="list-group-item" data-answer-id="' . $answer['id'] . '">' . $answer['answer_text'] . '</li>';
                    }
                    echo '</ul>';
                    echo '<input type="hidden" name="answers" id="sorted_answers">';
                } elseif ($question_id == 10) { // Вопрос с развернутым ответом
                    echo '<textarea name="user_answer" class="form-control" rows="5" required></textarea>';
                } else {
                    $sql = "SELECT * FROM answers WHERE question_id = $question_id";
                    $answers_result = $mysqli->query($sql);
                    if ($answers_result->num_rows > 0) {
                        $answers = [];
                        while ($answer = $answers_result->fetch_assoc()) {
                            $answers[] = $answer;
                        }
                        shuffle($answers); // Перемешиваем варианты ответов
                        foreach ($answers as $answer) {
                            if ($question_id == 5) { // Вопрос с несколькими правильными ответами
                                echo '<div class="form-check">';
                                echo '<input type="checkbox" name="answers[]" value="' . $answer['id'] . '" class="form-check-input">';
                                echo '<label class="form-check-label">' . $answer['answer_text'] . '</label>';
                                echo '</div>';
                            } else {
                                echo '<div class="form-check">';
                                echo '<input type="radio" name="answers[]" value="' . $answer['id'] . '" class="form-check-input" required>';
                                echo '<label class="form-check-label">' . $answer['answer_text'] . '</label>';
                                echo '</div>';
                            }
                        }
                    }
                }
                echo '</div>';
                echo '<button type="submit" class="btn btn-primary btn-block">Следующий вопрос</button>';
                echo '</form>';
            } else {

                $user_name = $_SESSION['user_name'];
                $score = $_SESSION['score'];
                $sql = "INSERT INTO results (user_name, score) VALUES ('$user_name', $score)";
                $mysqli->query($sql);
                session_unset();
                session_destroy();

                echo "<div class='alert alert-success'>Спасибо за прохождение теста, $user_name! Ваш результат: $score</div>";
                echo '<a href="studentLk.php" class="btn btn-secondary btn-block">Вернуться назад</a>';
            }
        }

        $mysqli->close();
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
    $(function() {
        $("#sortable").sortable();
        $("#sortable").disableSelection();
        $("form").on("submit", function() {
            var sortedAnswers = $("#sortable li").map(function() {
                return $(this).text();
            }).get();
            $("#sorted_answers").val(sortedAnswers.join(","));
        });
        
        // Таймер
        var timerDuration = 10 * 60;
        var timerDisplay = document.getElementById('timer');
        var timer = setInterval(function() {
            var minutes = Math.floor(timerDuration / 60);
            var seconds = timerDuration % 60;
            timerDisplay.textContent = minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
            if (timerDuration <= 0) {
                clearInterval(timer);
                document.getElementById('questionForm').submit();
            }
            timerDuration--;
        }, 1000);
    });
    </script>
</body>
</html>

