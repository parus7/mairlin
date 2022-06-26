<?php
session_start();
// --------- functions list ----------- //

/**
*	Parameters:
*		string = URL страницы при ошибке авторизации
*	
*	Description:  Проверка прав, требуется администратор или автор записи на странице.
*         В случае ошибки переводит на станицу указанную в входном параметре
*         В случае успеха возвращает набор данных пользователя полученных через $_GET["id"].
*
*	Return  value:  array
**/
function autoriztion4editing($get_id, $err_page_url){
  if(empty($_SESSION["user"]["user_id"])) header("Location: page_login.php");

  $is_author = $_SESSION["user"]["user_id"] == $get_id ? true : false ;

  $pdo = new PDO("mysql:host=localhost;dbname=marlin_pr1", "root", "");

  $sql = "SELECT `role` FROM `users` WHERE `id` = :metka ";
  $statement = $pdo->prepare($sql);
  $statement->execute(['metka' => $_SESSION["user"]["user_id"] ]);
  $role = $statement->fetch(PDO::FETCH_ASSOC);
  
  $is_administrator = ($role['role'] == "admin") ? true : false ;
  
  $autorization = ($is_administrator || $is_author) ? true : false ;

  if($autorization) {
      $sql = "SELECT * FROM ((`users` 
          LEFT JOIN about_user ON users.id = about_user.id) 
          LEFT JOIN users_social_links ON users.id = users_social_links.user_id) 
          WHERE users.id = :metka
          ";
    $statement = $pdo->prepare($sql);
    $statement->execute(['metka' => $get_id ]);
    $user_data = $statement->fetch(PDO::FETCH_ASSOC);
    return $user_data;
  }

  $key= "danger";
  $message = "Можно редактировать только свой профиль";
  $_SESSION[$key]=$message;
  exit(header("location: ". $err_page_url));
}



/**
*	Parameters:
*		string = $email
*	
*	Description:  поиск пользователя по электронному адресу
*
*	Return  value:  array
**/
function get_user_by_email($email) {
  $pdo = new PDO("mysql:host=localhost;dbname=marlin_pr1", "root", "");
  $sql = "SELECT `id`, `email`, `password` FROM `users` WHERE email=:metka";
  $statement = $pdo->prepare($sql);
  $statement->execute(['metka' => $email]);
  $result = $statement->fetch(PDO::FETCH_ASSOC);
  return $result;
};

/**
*	Parameters:
*		string = $email
*	
*	Description:  Ищет  email дубликат в базе.
*
*	Return  value:  boolean  
**/
function check_email_duplicate($email) {
  $result = get_user_by_email($email);

  if(empty($result)) return false;

  return true;
};

/**
 *  Parametrs:
 *    int $person_id
 * 
 *  Descritption:  Обновляет информацию пользователя.
 * 
 *    Return: value:  void
 **/
function update_user_by_id($user, $email, $password_hash)
{
  // Пустые поля не допускаются, заполняем пустоты.
  if(empty($email)) $email = $user["email"];
  if(empty($password_hash)) $password_hash = $user["password"];



  $pdo = new PDO("mysql:host=localhost;dbname=marlin_pr1", "root", "");
   
  $sql="UPDATE `users`
  SET `password` = :password, `email` = :email WHERE `id` = :id ";
  $statement = $pdo->prepare($sql);
  $statement->execute(["id" => $user["id"], "password" => $password_hash, "email" => $email]);
};

/**
*	Parameters:
*		string = $key  -- это key в сессии.
*		string= $message
*	
*	Description:  подготавливает сообщения.
*
*	Return  value: void
**/
function set_flash_message($key, $message){
  $_SESSION[$key]=$message;
};

/**
*	Parameters:
*		string = $path
*	
*	Description:  оболочка над location
*
*	Return  value:  void  
**/
function redirect_to($path) {
  exit(header("location: ". $path));
};


//========  end function list ========//


// init
$email = $_POST["emailfrm"];
$password = $_POST["passwordfrm"];
$confirm_password = $_POST["confirm_passwordfrm"];
//
$err_page_url = "users.php";
if(empty($email) && empty($password) && empty($confirm_password)) header("location: ". $err_page_url);

if($password != $confirm_password)
{
  $key = "danger";
  $message = "Введенные пароли не совпадают";
  set_flash_message($key, $message);
  redirect_to($err_page_url);
}

// Для проверки авторизации получаем id пользователя которого собираемся редактировать.
isset($_SESSION["user_editing"]) ? $get_id = $_SESSION["user_editing"] : header("location: ". $err_page_url) ;
unset($_SESSION["user_editing"] );

// авторизация
$user = autoriztion4editing($get_id, $err_page_url);


if(!empty($email) && $email != $user["email"]){
  if(check_email_duplicate($email))
  {
    $key= "danger";
    $message = "Этот эл адрес уже занят другим пользователем";
    set_flash_message($key, $message);

    redirect_to($err_page_url);
  }
}

$password_hash  = password_hash($password, PASSWORD_DEFAULT);


update_user_by_id($user, $email, $password_hash);

$key= "success";
$message = "Профиль успешно обновлен.";
set_flash_message($key, $message);

$path = "page_profile.php";
redirect_to($path);