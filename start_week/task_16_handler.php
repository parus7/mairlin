<?php
session_start();

$e_mail = $_POST["emailFrm"];
$passwd = $_POST["passFrm"];

if( empty($_POST["emailFrm"]) || empty($_POST["passFrm"]) ) 
{
  $danger = "Не указан email или пароль.";
  $_SESSION["danger"] = $danger;
  exit(header("Location: task_16.php")) ;
}

$pdo= new PDO("mysql:host=localhost;dbname=my_project", "root", "");

$sql = "SELECT * FROM `task16_lesson` WHERE email =:mail";
$statement = $pdo->prepare($sql);
$statement->execute(["mail" => $e_mail]);
$user= $statement->fetch(PDO::FETCH_ASSOC);


if(empty($user["email"])) 
{
  $danger = "Такого пользователя не существует.";
  $_SESSION["danger"] = $danger;
  exit(header("Location: task_16.php")) ;
}

$hash = $user['password'];

if (password_verify($passwd, $hash)) {
    $success = "Пользователь аутенфицирован";
    $_SESSION["success"]= $success ;
    $_SESSION["user"] = ["email" => $e_mail, "id" => $user["id"]];
    exit(header("Location: task_16.php")) ;
}

$danger = "Неправильно набран email или пароль.";
$_SESSION["danger"] = $danger;
exit(header("Location: task_16.php")) ;
