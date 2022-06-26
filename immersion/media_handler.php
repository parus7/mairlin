<?php
session_start();

/**
*	Parameters:
*		string = URL страницы при ошибке авторизации
*	
*	Description:  Проверка прав, требуется администратор или автор записи на странице.
*         В случае ошибки переводит на станицу указанную в входном параметре
*         В случае успеха возвращает набор данных пользователя полученных через $get_id.
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
 * Parameters:
 *  arr $user
 *  string $uploaddir
 *  string $upload_arr_name
 * 
 * Description: заменяет аватар на аватар полученый из формы
 *   при отсутсвии аватара, устанавливает аватар по умолчанию
 * 
 * Return  value: void
 */
function change_avatar($user, $uploaddir, $upload_arr_name) {
  // Получаем из базы полное имя файла старого аватара
  $old_file_name = $user["img_src"];
  $default_avatar = "img/demo/avatars/avatar-m.png"; // issue это нужно делать глобальной константой.

  if(empty($old_file_name)) $old_file_name = $default_avatar;// аватар всегда должен быть, хотя бы по умолчанию .

  // Если имя файла нового аватара пустое, ставим ставим старую картинку  
  if(isset($_FILES[$upload_arr_name]['name'])){

    $uploadfile = $uploaddir . uniqid() . "_" . basename($_FILES[$upload_arr_name]['name']);

    move_uploaded_file($_FILES[$upload_arr_name]['tmp_name'], $uploadfile);
    
  } else {
    $uploadfile = $old_file_name;
  };

  // Вносим в базу имя файла нового аватара
  $pdo = new PDO("mysql:host=localhost;dbname=marlin_pr1", "root", "");

  $sql = "UPDATE `users` SET `img_src`=:img_name WHERE id = :id";
  $statement = $pdo->prepare($sql);
  $statement->execute(['img_name' => $uploadfile, "id" => $user["id"] ]);

  // если имя файла старого аватара не пустое, или не картинка по умолчанию, то удаляем файл старого аватара
  if($old_file_name != $default_avatar) unlink($old_file_name);


  $key= "success";
  $message = "Профиль успешно обновлен.";
  set_flash_message($key, $message);

  $path = "page_profile.php";
  redirect_to($path);
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

// Для проверки авторизации получаем id пользователя которого собираемся редактировать.
$get_id = $_SESSION["user_editing"] ;
unset($_SESSION["user_editing"] );
//init
$err_page_url = "users.php"; // issue это нужно делать глобальной константой.
$default_avatar = "img/demo/avatars/avatar-m.png"; // issue это нужно делать глобальной константой.

// Если не просят поменять аватар, то что мы тут делаем
if(!isset($_FILES["upload_image"])) header("location: ". $err_page_url);

// Если просят установить имя по умолчанию, то это делать не надо.
if($_FILES["upload_image"]["name"] == $default_avatar ) {
  $key= "danger";
  $message = "Профиль не обновлен. Аватар по умолчанию уже устанавлен.";
  set_flash_message($key, $message);

  $path = "page_profile.php"; // issue это похоже на default_edit_page
  redirect_to($path);
} 

if($_FILES["upload_image"]["error"] != 0 ) {
  $key= "danger";
  $message = "Профиль не обновлен. Ошибка загрузки файла аватара.";
  set_flash_message($key, $message);

  $path = "page_profile.php"; // issue это похоже на default_edit_page
  redirect_to($path);
} 

// issue Проверка на максимальный размер загружаемого файла

// авторизация
$user = autoriztion4editing($get_id, $err_page_url);

// Замена аватара.
$uploaddir = 'img/avatars/'; // Определяем место
$upload_arr_name = "upload_image"; // Имя массива из формы
change_avatar($user, $uploaddir, $upload_arr_name);