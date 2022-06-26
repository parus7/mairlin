<?php

// Warning!  Данные находятся в 3 разных таблицах



/**
* Parameters:
*   string = $email
*   string=$password
* 
* Description:  проверить authenfication пользователя в бд
*
* Return  value:    boolean
**/
function login($email, $password){
  $user = get_user_by_email($email);
  
  $hash = $user['password'];
  $result = password_verify($password, $hash);

  return $result;
};

  /**
  *	
  *	Description:  check administrator authorization
  *
  *	Return  value:  boolean
  **/ 
  function is_administrator() 
  { 
      $id = $_SESSION["user"]["user_id"];
      $pdo = new PDO("mysql:host=localhost;dbname=marlin_pr1", "root", "");
      $sql = "SELECT * FROM `users` WHERE id=:metka";
      $statement = $pdo->prepare($sql);
      $statement->execute(['metka' => $id]);
      $user = $statement->fetch(PDO::FETCH_ASSOC);
      return $user["role"] == "admin"? true : false;
  };


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
function check_email_duplicate($email)
{
  $result = get_user_by_email($email);

  if(empty($result)) return false;

  return true;
};

/**
*	Parameters:
*		string = $key  -- это key в сессии.
*		string= $message
*	
*	Description:  подготавливает сообщения.
*
*	Return  value:  null  - патаму что через сессию 
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
*	Return  value:  null  
**/
function redirect_to($path)
{
  exit(header("location: ". $path));
};



/**
 *  Parametrs:
 *    string = $email
 *    string = $password
 *    
 * 
 *  Descritption:  Создает пользователя и возращает его ID
 * 
 *    Return: value:  int
 **/

function set_user_by_email($email, $password_hash)
{
  $pdo = new PDO("mysql:host=localhost;dbname=marlin_pr1", "root", "");
  $sql="INSERT INTO `users` (`email`, `password`) VALUES (:email, :password_hash)";
  $statement = $pdo->prepare($sql);
  $statement->execute(['email' => $email, 'password_hash' => $password_hash]);
}

/**
 *  Parametrs:
 *    int = $user_id
 *    string = $uploaddir
 * 
 *  Descritption:  Загружает avatar пользователя.
 * 
 *    Return: value:  void
 **/
function set_user_avatar($user_id, $uploaddir, $upload_arr_name)
{
  // если нет картинки, то надо ставить картинку по умолчанию.
  $default_avatar = "img\demo\avatars\avatar-m.png"; // issue это нужно делать глобальной константой.
  if(isset($_FILES[$upload_arr_name]['name'])){
    // Создаем уникальное имя.
    $uploadfile = $uploaddir . uniqid() . "_" . basename($_FILES[$upload_arr_name]['name']);
    // Укладывам полученный файл
    move_uploaded_file($_FILES[$upload_arr_name]['tmp_name'], $uploadfile);
  } else {
    $uploadfile = $default_avatar;
  }

  $pdo = new PDO("mysql:host=localhost;dbname=marlin_pr1", "root", "");

  $sql = "UPDATE `users` SET  `img_src` = :img_name WHERE id = :id";
  $statement = $pdo->prepare($sql);
  $statement->execute(['img_name' => $uploadfile, "id" => $user_id ]);
}


/**
 *  Parametrs:
 *    int = $user_id
 * 
 *  Descritption:  Загружает общую информацию пользователя.
 * 
 *    Return: value:  void
 **/
function set_user_general_infromation($user_id)
{
  $name = $_POST["namefrm"];
  $profession = $_POST["professionfrm"];
  $telephone = $_POST["telephonefrm"];
  $address = $_POST["addressfrm"];
  $status = $_POST["statusfrm"];

  $pdo = new PDO("mysql:host=localhost;dbname=marlin_pr1", "root", "");

  $sql="INSERT INTO `about_user` (`id`, `name`, `status`, `profession`, `telephone`, `address`) 
        VALUES (:id, :name, :status, :profession, :telephone, :address)"
        ;
  $statement = $pdo->prepare($sql);
  $statement->execute(["id" => $user_id, "name" => $name, "status" => $status, "profession" => $profession, 
                      "telephone" => $telephone, "address" => $address]);
}



/**
 *  Parametrs:
 *    int = $user_id
 * 
 *  Descritption:  Загружает ссылки соц. сетей пользователя.
 * 
 *    Return: value:  void
 **/
function set_user_social_links($user_id)
{
  $vk  = $_POST["vkfrm"];
  $telegram  = $_POST["telegramfrm"];
  $insta  = $_POST["instafrm"];
  
  $pdo = new PDO("mysql:host=localhost;dbname=marlin_pr1", "root", "");

  $sql="INSERT INTO `users_social_links` (`user_id`, `vk_link`, `telegram_link`, `instagram_link`) VALUES (:id, :vk, :tlg, :insta)";
  $statement = $pdo->prepare($sql);
  $statement->execute(["id" => $user_id, "vk" => $vk, "tlg" => $telegram, "insta" => $insta]);

};

//========  end function list ========//

session_start();

if(empty($_SESSION["user"]["user_id"])) header("Location: page_login.php");

if(!is_administrator()){
  $key= "danger";
  $message = "Не достаточно прав на это действие";
  set_flash_message($key, $message);

  unset($_SESSION["user"]["user_id"]);

  $path = "page_login.php";
  redirect_to($path);
};


// init
$email = $_POST["emailfrm"];
$password_hash  = password_hash($_POST["passwordfrm"], PASSWORD_DEFAULT);


$name = $_POST["namefrm"];
$profession = $_POST["professionfrm"];
$telephone = $_POST["telephonefrm"];
$address = $_POST["addressfrm"];
$status = $_POST["statusfrm"];

$vk  = $_POST["vkfrm"];
$telegram  = $_POST["telegramfrm"];
$insta  = $_POST["instafrm"];

$upload_arr_name = "imagefrm";
$uploaddir = "img/avatars/";
 



if(check_email_duplicate($email))
{
  $key= "danger";
  $message = "Этот эл адрес уже занят другим пользователем";
  set_flash_message($key, $message);

  $path = "users.php";
  redirect_to($path);
}

// create_user by email
set_user_by_email($email, $password_hash);


// get new user id, email, password_hash
$new_user = get_user_by_email($email, $password_hash);
$user_id = $new_user["id"];

set_user_avatar($user_id, $uploaddir, $upload_arr_name);


//set general information
if(!empty($name) || !empty($status) || !empty($profession) || !empty($telephone) || !empty($address)) 
{
  set_user_general_infromation($user_id) ;
}

// set social network link
if(!empty($vk) || !empty($telegram) || !empty($$insta)) set_user_social_links($user_id);




// set messages  && go to user list
  $key= "success";
  $message = "Пользователь успешно добавлен";
  set_flash_message($key, $message);

  $path = "users.php";
  redirect_to($path);
