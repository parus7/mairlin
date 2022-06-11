<?php
// var_dump($_POST); die;
$text = $_POST['text'];

$pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "");
$sql = "INSERT INTO my11_lesson (form_data) VALUE (:metka)";
$statement = $pdo->prepare($sql);
$statement->execute(['metka' => $text ]);

header("Location: /marlin/task_11.html");