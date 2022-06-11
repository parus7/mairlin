<?php
//Запускаем сессию 
session_start();


// Определяем связь с полем формы
$file_upload= "upload_image";
$file_delete= "delete_id";

// Определяем место
$uploaddir = 'uploads/';

// Подключаемся к базе
$pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "");

// Если имя не пустое делаем создание записи, 
if(!empty($_FILES[$file_upload]['name']))
{
  // Создаем уникальное имя.
  $uploadfile = $uploaddir . uniqid() . "_" . basename($_FILES[$file_upload]['name']);
  // Укладывам полученный файл
  move_uploaded_file($_FILES[$file_upload]['tmp_name'], $uploadfile);
  
  
  //  Регистрируем  запись о файле в базе 
  
  $sql = "INSERT  INTO `images` (`image`) VALUE (:img_name)";
  $statement = $pdo->prepare($sql);
  $statement->execute(['img_name' => $uploadfile]);
}


//Если имя удаляемого файла не пустое удаляем файл
if(!empty($_GET["id"]))
{
$sql = "DELETE FROM images WHERE id=:id";
$statement = $pdo->prepare($sql);
$statement->execute([ "id" => $_GET["id"]]);
}

exit(header("Location: task_19.php"));
