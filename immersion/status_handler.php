<?php
session_start();
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

function decode_status(){
switch ($_POST["select_elem"]) {
  case 'Онлайн':
    return "success";
    break;
  case 'Отошел':
    return "warning";
    break;
  case 'Не беспокоить':
    return "danger";
    break;
  
  default:
    return "danger";
    break;
}

}

/**
 *  Parametrs:
 *     int $person_id 
 * 
 *  Descritption:  Добавляет общую информацию пользователя в базу.
 * 
 *    Return: value:  void
 **/
function set_user_status_by_id(int $person_id, $status)
{

  $pdo = new PDO("mysql:host=localhost;dbname=marlin_pr1", "root", "");
   

  $sql="INSERT INTO `about_user` (`id`, `status`) 
        VALUES (:id, :status)
        ";
  $statement = $pdo->prepare($sql);
  $statement->execute(["id" => $person_id, "status" => $statsu]);
};


/**
 *  Parametrs:
 *    int $person_id
 * 
 *  Descritption:  Обновляет общую информацию пользователя.
 * 
 *    Return: value:  void
 **/
function update_person_status_by_id(int $person_id, $status){

  $pdo = new PDO("mysql:host=localhost;dbname=marlin_pr1", "root", "");
   
  $sql="UPDATE `about_user`
  SET `status` = :status
  WHERE `id` = :id ";
  $statement = $pdo->prepare($sql);
  $statement->execute(["id" => $person_id, "status" => $status]);
};

/**
 *  Parametrs:
 *    int $person_id
 * 
 *  Descritption:  Получает общую информацию пользователя.
 * 
 *    Return: value:  array
 **/
function get_person_by_id(int $person_id){
  $pdo = new PDO("mysql:host=localhost;dbname=marlin_pr1", "root", "");

  $sql="SELECT * FROM `about_user` WHERE id = :id ";
  $statement = $pdo->prepare($sql);
  $statement->execute(['id' => $person_id]);
  return $statement->fetch(PDO::FETCH_ASSOC);
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
//========  end functions list ========//



$err_page_url = "users.php";
// Проверяем наличие обязательных параметров
(isset($_POST["select_elem"])) && isset($_SESSION["user_editing"]) ? $status = decode_status() : header("location: ". $err_page_url);

// Для проверки авторизации получаем id пользователя которого собираемся редактировать.
$get_id = $_SESSION["user_editing"] ;
unset($_SESSION["user_editing"] );

// авторизация
$user = autoriztion4editing($get_id, $err_page_url);

// Обновляем статус
$person_id = $user["id"];

$person = get_person_by_id($person_id);

if(empty($person)) 
{
  set_person_status_by_id($person_id, $status);
} else {
  update_person_status_by_id($person_id, $status);
};

$key= "success";
$message = "Профиль успешно обновлен.";
set_flash_message($key, $message);

$path = "page_profile.php";
redirect_to($path);