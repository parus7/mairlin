<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Профиль пользователя</title>
    <meta name="description" content="Chartist.html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-brands.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-regular.css">
</head>
    <body class="mod-bg-1 mod-nav-link">
        <?php 
        session_start(); 
        if(empty($_SESSION["user"]["user_id"])) header("Location: page_login.php"); 
        
        // Warning!  Данные находятся в 3 разных таблицах.
    
        /**
        *	Parameters:
        *		int = $id
        *	
        *	Description:  поиск пользователя по uid
        *
        *	Return  value:  array
        **/
        function get_user_by_id($user_id) {
        $pdo = new PDO("mysql:host=localhost;dbname=marlin_pr1", "root", "");
        $sql = "SELECT * FROM ((`users` 
                LEFT JOIN about_user ON users.id = about_user.id) 
                LEFT JOIN users_social_links ON users.id = users_social_links.user_id) 
                WHERE users.id = :metka
                ";
        $statement = $pdo->prepare($sql);
        $statement->execute(['metka' => $user_id]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
        };
        //========  end functions list ========//



        $user_id = $_SESSION["user"]["user_id"];

        $user = get_user_by_id($user_id);
        
        $email = $user["email"];
        $role = $user["role"];
        $status = $user["status"];
        $name = $user["name"];
        $profession = $user["profession"];
        $telephone =$user["telephone"];
        $address = $user["address"];
        $vk_link = $user["vk_link"];
        $telegram_link = $user["telegram_link"];
        $instagram_link = $user["instagram_link"];
        //  Устанавливаем аватар по умолчению.
        if(!isset($user['img_src'])) $user['img_src'] = "img\demo\avatars\avatar-m.png";
        $img_src = $user["img_src"];
        ?>



        <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
            <a class="navbar-brand d-flex align-items-center fw-500" href="#"><img alt="logo" class="d-inline-block align-top mr-2" src="img/logo.png"> Учебный проект</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item ">
                        <a class="nav-link" href="users.php">Главная</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Войти</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Выйти</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main id="js-page-content" role="main" class="page-content mt-3">
            <div class="subheader">
                <h1 class="subheader-title">
                    <i class='subheader-icon fal fa-user'></i> <?php echo $name ?>
                </h1>
            </div>

            <!-- Предупредительное сообщение -->
            <?php if(!empty($_SESSION["danger"])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION["danger"]; ?>
                    <?php unset($_SESSION["danger"]); ?>
                </div>
            <?php endif; ?>
            <!-- Сообщение об успешном результате -->
            <?php if(!empty($_SESSION["success"])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION["success"]; ?>
                    <?php unset($_SESSION["success"]); ?>
                </div>
            <?php endif; ?>

            <div class="row">
              <div class="col-lg-6 col-xl-6 m-auto">
                    <!-- profile summary -->
                    <div class="card mb-g rounded-top">
                        <div class="row no-gutters row-grid">
                            <div class="col-12">
                                <div class="d-flex flex-column align-items-center justify-content-center p-4">
                                    <img src="<?php echo $img_src ?>" class="rounded-circle shadow-2 img-thumbnail" alt="">
                                    <h5 class="mb-0 fw-700 text-center mt-3">
                                        <?php echo $name ?> 
                                        <small class="text-muted mb-0"><?php echo $profession ?></small>
                                    </h5>
                                    <div class="mt-4 text-center demo">
                                        <a href="<?php !empty($user["instagram_link"]) ? (print $user["instagram_link"]) : (print `javascript:void(0);`); ?>" class="fs-xl" style="color:#C13584">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                        <a href="<?php !empty($user["vk_link"]) ? (print $user["vk_link"]) : (print `javascript:void(0);`); ?>" class="fs-xl" style="color:#4680C2">
                                            <i class="fab fa-vk"></i>
                                        </a>
                                        <a href="<?php !empty($user["telegram_link"]) ? (print $user["telegram_link"]) : (print `javascript:void(0);`); ?>" class="fs-xl" style="color:#0088cc">
                                            <i class="fab fa-telegram"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 text-center">
                                    <a href="tel:+13174562564" class="mt-1 d-block fs-sm fw-400 text-dark">
                                        <i class="fas fa-mobile-alt text-muted mr-2"></i><?php echo $telephone ?></a>
                                    <a href="mailto:oliver.kopyov@marlin.ru" class="mt-1 d-block fs-sm fw-400 text-dark">
                                        <i class="fas fa-mouse-pointer text-muted mr-2"></i><?php echo $email ?></a>
                                    <address class="fs-sm fw-400 mt-4 text-muted">
                                        <i class="fas fa-map-pin mr-2"></i> <?php echo $address ?>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
               </div>
            </div>
        </main>
    </body>

    <script src="js/vendors.bundle.js"></script>
    <script src="js/app.bundle.js"></script>
    <script>

        $(document).ready(function()
        {

        });

    </script>
</html>