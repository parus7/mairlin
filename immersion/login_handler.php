<?php
/**
* Parameters:
*   string = $email
*   string=$password
* 
* Description:  проверить  autenfication пользователя 
*
* Return  value:    boolean
**/
function login($email, $password){
  $user = get_user_by_email($email);
  
  $hash = $user['password'];
  $result = password_verify($password, $hash);

  return $result;
}

/**
*	Parameters:
*		string = $email
*	
*	Description:  поиск пользователя по электронному адресу
*
*	Return  value:    array
**/
function get_user_by_email($email) {
  $pdo= new PDO("mysql:host=localhost;dbname=marlin_pr1", "root", "");
  $sql = "SELECT `id`, `email`, `password` FROM `users` WHERE email=:metka";
  $statement = $pdo->prepare($sql);
  $statement->execute(['metka' => $email]);
  $result = $statement->fetch(PDO::FETCH_ASSOC);
  return $result;
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

$email = $_POST["emailfrm"];
$password = $_POST["passwordfrm"];

// Проверяем исходные данные пользователя.

if(empty($email) || empty($password))
{
  $name = "danger";
  $message = "Не указан email или пароль";
  set_flash_message($name, $message);

  $path = "page_login.php";
  redirect_to($path);
}


if(login($email, $password)){
    $user = get_user_by_email($email);
    $_SESSION["user"] = ["email" => $email, "user_id" => $user["id"]];
    $path = "users.php";
    redirect_to($path);
}
  
$name = "danger";
$message = "<strong>Уведомление!</strong> Этот эл. адрес ". $email . " не зарегистрирован.";
set_flash_message($name, $message);

$path = "page_login.php";
redirect_to($path);  