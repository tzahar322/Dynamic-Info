<?php

$hostname = "31.31.198.18";
$database = "u0881075_edu2024";
$username = "u0881075_stud1";
$password = "Vkrb2024@";

$mysqli = mysqli_connect($hostname, $username, $password, $database);
if (mysqli_connect_errno()) {
    throw new RuntimeException('Ошибка соединения с БД: ' . mysqli_connect_error());
}

mysqli_set_charset($mysqli, 'utf8mb4');