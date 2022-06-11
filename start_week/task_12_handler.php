<?php
session_start();

if(empty($_POST['text'])) {
  exit(header("Location: task_12.php"));
}

$text = $_POST['text'];

$pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "");

$sql = "SELECT form_data FROM my12_lesson WHERE form_data=:metka";
$statement = $pdo->prepare($sql);
$statement->execute(['metka' => $text]);
$task = $statement->fetch(PDO::FETCH_ASSOC);
// var_dump($task); die;


if(!empty($task)) {
  $danger = "Введенная запись уже присутствует в таблице";
  $_SESSION['danger'] = $danger;
  exit(header("Location: task_12.php"));
}


$sql = "INSERT INTO my12_lesson (form_data) VALUE (:metka)";
$statement = $pdo->prepare($sql);
$statement->execute(['metka' => $text ]);

$success = "Введенная запись зарегистрирована";
$_SESSION['success'] = $success;

header("Location: task_12.php");