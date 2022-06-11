<?php
session_start();
// var_dump($_POST);

$e_mail = $_POST["email"];
$pasw =  $_POST["password"];

if(empty($pasw) || empty($e_mail)) {
  $danger = "Не указан email или пароль.";
  $_SESSION['danger'] = $danger;
  exit(header("Location: task_13.php"));
}


$pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "");

$sql = "SELECT `email`, `password` FROM `task13_lesson` WHERE email=:metka";
$statement = $pdo->prepare($sql);
$statement->execute(['metka' => $e_mail]);
$task = $statement->fetch(PDO::FETCH_ASSOC);
// var_dump($task);

if(!empty($task)) {
  $danger = "Этот эл адрес уже занят другим пользователем";
  $_SESSION['danger'] = $danger;
  exit(header("Location: task_13.php"));
}

$pass =  password_hash($pasw, PASSWORD_DEFAULT);

$sql = "INSERT INTO task13_lesson (email, password) VALUE (:met_email, :met_pass)";
$statement = $pdo->prepare($sql);
$statement->execute(["met_email" => $e_mail, "met_pass" => $pass]);

$_SESSION["success"]="success";
exit(header("Location: task_13.php"));