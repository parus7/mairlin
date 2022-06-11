<?php


// Определяем связь с полем формы
$userfile= "upload_image";

if(empty($_FILES[$userfile]['name'])) exit(header("Location: task_18.php"));


// Определяем место
$uploaddir = 'uploads/';

// Создаем уникальное имя.
$uploadfile = $uploaddir . uniqid() . "_" . basename($_FILES[$userfile]['name']);

// Укладывам полученный файл
move_uploaded_file($_FILES[$userfile]['tmp_name'], $uploadfile);


// Подключаемся к базе
$pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "");

//  Регистрируем  запись о файле в базе 

$sql = "INSERT  INTO `images` (`image`) VALUE (:img_name)";
$statement = $pdo->prepare($sql);
$statement->execute(['img_name' => $uploadfile]);


exit(header("Location: task_18.php"));

// выводим картинки.

// Получаем список картинок
// $sql="SELECT `image` FROM `images`";
// $statement = $pdo->prepare($sql);
// $statement->execute();
// $pictures = $statement->FetchAll(PDO::FETCH_ASSOC);


// Передаем картинки через сессию

// $_SESSION["img_list"] = $pictures;

