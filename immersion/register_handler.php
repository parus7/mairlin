<?php

/**
*	Parameters:
*		string = $email
*	
*	Description:  поиск пользователя по электронному адресу
*
*	Return  value:    array
**/
function get_user_by_email(&$pdo, $email) {
  $sql = "SELECT `id`, `email`, `password` FROM `users` WHERE email=:metka";
  $statement = $pdo->prepare($sql);
  $statement->execute(['metka' => $email]);
  $result = $statement->fetch(PDO::FETCH_ASSOC);
  return $result;
};

/**
*	Parameters:
*		string = $email
*		String=$password
*	
*	Description:  добавить пользователя в бд
*
*	Return  value:    int (user_id)
**/
function  add_user(&$pdo, $email, $password){
  $sql = "INSERT INTO users (`email`, `password`) VALUE (:met_email, :met_pass)";
  $statement = $pdo->prepare($sql);
  $statement->execute(['met_email' => $email, 'met_pass' => $password]);  
};

/**
*	Parameters:
*		string = $name  -- это key в сессии.
*		string= $message
*	
*	Description:  подготавливает сообщения.
*
*	Return  value:  null  - патаму что через сессию 
**/
function set_flash_message($name, $message){
  $_SESSION[$name]=$message;
};

/**
*	Parameters:
*		string = $path
*	
*	Description:  оболочка над location
*
*	Return  value:  null  
**/
function redirect_to($path){
  exit(header("location: ". $path));
};

//========  end function ========//

session_start();


if(empty($_POST["emailfrm"]) || empty($_POST["passwordfrm"]))
{
  $name = "danger";
  $message = "Не указан email или пароль";
  set_flash_message($name, $message);

  $path = "page_register.php";
  redirect_to($path);
}


$email = $_POST["emailfrm"];
$password = password_hash($_POST["passwordfrm"], PASSWORD_DEFAULT);

$pdo= new PDO("mysql:host=localhost;dbname=marlin_pr1", "root", "");


// Проверяем наличие пользователя.
$user = get_user_by_email($pdo, $email);
if(!empty($user["email"]))
{
  $name = "danger";
  $message = "<strong>Уведомление!</strong> Этот эл. адрес ". $email . " уже занят другим пользователем.";
  set_flash_message($name, $message);

  $path = "page_register.php";
  redirect_to($path);
}

// Добавляем нового пользователя
add_user($pdo, $email, $password);
// уведомляем об успешной регистрации.
$name = "success";
$message = "Регистрация успешна";
set_flash_message($name, $message);


$path = "page_login.php";
redirect_to($path);

$user = get_user_by_email($pdo, $email);
$_SESSION["user_id"] = $user["id"];