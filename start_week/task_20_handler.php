<?php
echo "<pre>",print_r($_FILES["imagesfrm"]["name"][0]),     "</pre>";

// Определяем место
$uploaddir = 'uploads/';

// Подключаемся к базе
$pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "");
$my_pdo = &$pdo;
// П
for($i = 0; $i < count($_FILES["imagesfrm"]["tmp_name"]); $i++)
{
  $name_file = $_FILES["imagesfrm"]["name"][$i];
  $tmp_name_file = $_FILES["imagesfrm"]["tmp_name"][$i];
  load_file($name_file, $tmp_name_file,$uploaddir, $my_pdo);
}


function load_file($name_file, $tmp_name_file, $uploaddir, $pdo) {
  
  // Создаем уникальное имя.
  $uploadfile = $uploaddir . uniqid() . "_" . basename($name_file);
  
  // Укладывам полученный файл
  move_uploaded_file($tmp_name_file, $uploadfile);
  
  //  Регистрируем  запись о файле в базе 
  $sql = "INSERT  INTO `images` (`image`) VALUE (:img_name)";
  $statement = $pdo->prepare($sql);
  $statement->execute(['img_name' => $uploadfile]);
}

exit(header("Location: task_20.php"));