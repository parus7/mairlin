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


/**
*	Parameters:
*		string = $path
*	
*	Description:  оболочка над location
*
*	Return  value:  void  
**/
function delete_user($user) {
  //удалить пользователя
  // получить имя file аватара
  $avatar_file = $user["img_src"];
  // удалить запись в базе
  $pdo = new PDO("mysql:host=localhost;dbname=marlin_pr1", "root", "");

  $sql = "DELETE `users`, `about_user`, users_social_links  FROM ((`users` 
            LEFT JOIN about_user ON users.id = about_user.id) 
            LEFT JOIN users_social_links ON users.id = users_social_links.user_id) 
            WHERE users.id = :metka
            ";
  $statement = $pdo->prepare($sql);
  $statement->execute(['metka' => $user["id"]]);

  $default_avatar = "img/demo/avatars/avatar-m.png"; // issue это нужно делать глобальной константой.

  // если имя аватара не пусто или не по умолчанию, то удаляем файл с аватаром
  if(!empty($avatar_file) && $avatar_file != $default_avatar) unlink("$avatar_file");

  // сформировать сообщение пользователь удален.
  $key= "success";
  $message = "Пользователь удален.";
  set_flash_message($key, $message);

  // если пользователь удалил не себя то переход в список пользователей
  $err_page_url= "users.php"; // issue это нужно делать глобальной константой.
  $path = $err_page_url;
  if($_GET["id"] != $_SESSION["id"]) redirect_to($path);

  //удалить сессию и переход на страницу регистрации
  unset($_SESSION["id"]);
  redirect_to("login.php");
}

//========  end function list ========//

$err_page_url= "users.php"; // issue это нужно делать глобальной константой.
(isset($_GET["id"])) ? $get_id = $_GET["id"] : header("location: ". $err_page_url);

// авторизация.
$user = autoriztion4editing($get_id, $err_page_url);
delete_user($user);
