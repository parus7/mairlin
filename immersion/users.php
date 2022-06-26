<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
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
        <?php session_start(); ?>
        <?php
            // Warning!  Данные находятся в 3 разных таблицах.


            /**
            *	Parameters:
            *	
            *	Description:  Общий список пользователей 
            *                 Исполользуются 3 таблицы.
            *
            *	Return  value:    array
            **/
            function get_users_list() {
            $pdo= new PDO("mysql:host=localhost;dbname=marlin_pr1", "root", "");
            $sql = "SELECT * FROM ((`users` 
                    LEFT JOIN about_user ON users.id = about_user.id)  
                    LEFT JOIN users_social_links ON users.id = users_social_links.user_id)
                    ";
            // $sql = "SELECT * FROM `users` ";
            $statement = $pdo->prepare($sql);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
            };
        ?>

        <!-- Проверка авторизации -->

        <?php  if(empty($_SESSION["user"]["user_id"])) header("Location: page_login.php");  
        
        // Проверка роли и вывод кнопки -->
        $users_list = get_users_list();
        $role = "user";
        $button_visible = false;

        foreach($users_list as $user) {
        if($user["id"]==$_SESSION["user"]["user_id"] && $user["role"]=="admin" ) {
            $role = "admin";
            $button_visible = true;
           }
        } 
       ?>
        


        <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
            <a class="navbar-brand d-flex align-items-center fw-500" href="users.php"><img alt="logo" class="d-inline-block align-top mr-2" src="img/logo.png"> Учебный проект</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Главная <span class="sr-only">(current)</span></a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="page_login.php">Войти</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php#">Выйти</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main id="js-page-content" role="main" class="page-content mt-3">

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


            <div class="subheader">
                <h1 class="subheader-title">
                    <i class='subheader-icon fal fa-users'></i> Список пользователей
                </h1>
            </div>
            <div class="row">
                <div class="col-xl-12">

                    <!-- Обработчик вывода кнопки -->
                    <?php if($button_visible): ?>
                        <a class="btn btn-success" href="create_user.php">Добавить</a>
                    <?php endif?>

                    <div class="border-faded bg-faded p-3 mb-g d-flex mt-3">
                        <input type="text" id="js-filter-contacts" name="filter-contacts" class="form-control shadow-inset-2 form-control-lg" placeholder="Найти пользователя">
                        <div class="btn-group btn-group-lg btn-group-toggle hidden-lg-down ml-3" data-toggle="buttons">
                            <label class="btn btn-default active">
                                <input type="radio" name="contactview" id="grid" checked="" value="grid"><i class="fas fa-table"></i>
                            </label>
                            <label class="btn btn-default">
                                <input type="radio" name="contactview" id="table" value="table"><i class="fas fa-th-list"></i>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="js-contacts">


                <div class="col-xl-4">
                    <!-- вывод основного блока данных -->
                    <?php foreach($users_list as $user):?>
                        <?php !empty($user['img_src']) ? $img_src = $user["img_src"] : $img_src = "img/demo/avatars/avatar-m.png"; ?>

                        <div id="c_<?php echo $user["id"]; ?>" class="card border shadow-0 mb-g shadow-sm-hover" data-filter-tags="<?php echo strtolower($user["name"]); ?>">
                            <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                                <div class="d-flex flex-row align-items-center">
                                    <span class="status status-<?php echo $user["status"]; ?> mr-3">
                                        <span class="rounded-circle profile-image d-block " style="background-image:url('<?php echo $img_src; ?>'); background-size: cover;"></span>
                                    </span>
                                    <div class="info-card-text flex-1">
                                        <a href="page_profile.php" class="fs-xl text-truncate text-truncate-lg text-info d-inline-block" aria-expanded="false">
                                            <?php echo $user["name"]; ?>
                                        </a>

                                        <!-- Показываем эту часть записи только если пользователь автор или админ -->
                                        <?php if($user["id"]==$_SESSION["user"]["user_id"] || $role=="admin" ): ?>
                                            <div class="fs-xl text-truncate text-truncate-lg text-info d-inline-block" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fal fas fa-cog fa-fw d-inline-block ml-1 fs-md"></i>
                                                <i class="fal fa-angle-down d-inline-block ml-1 fs-md"></i>
                                            </div>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="edit.php?id=<?php print $user["id"]; ?>">
                                                    <i class="fa fa-edit"></i>
                                                Редактировать</a>
                                                <a class="dropdown-item" href="security.php?id=<?php print $user["id"]; ?>">
                                                    <i class="fa fa-lock"></i>
                                                Безопасность</a>
                                                <a class="dropdown-item" href="status.php?id=<?php print $user["id"]; ?>">
                                                    <i class="fa fa-sun"></i>
                                                Установить статус</a>
                                                <a class="dropdown-item" href="media.php?id=<?php print $user["id"]; ?>">
                                                    <i class="fa fa-camera"></i>
                                                    Загрузить аватар
                                                </a>
                                                <a href="delete_handler.php?id=<?php print $user["id"]; ?>" class="dropdown-item" onclick="return confirm('are you sure?');">
                                                    <i class="fa fa-window-close"></i>
                                                    Удалить
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <span class="text-truncate text-truncate-xl"><?php echo $user["profession"]; ?></span>
                                    </div>
                                    <button class="js-expand-btn btn btn-sm btn-default d-none" data-toggle="collapse" data-target="#c_<?php echo $user["id"]; ?> > .card-body + .card-body" aria-expanded="false">
                                        <span class="collapsed-hidden">+</span>
                                        <span class="collapsed-reveal">-</span>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0 collapse show">
                                <div class="p-3">
                                    <a href="tel:+13174562564" class="mt-1 d-block fs-sm fw-400 text-dark">
                                        <i class="fas fa-mobile-alt text-muted mr-2"></i> <?php echo $user["telephone"]; ?></a>
                                    <a href="mailto:oliver.kopyov@smartadminwebapp.com" class="mt-1 d-block fs-sm fw-400 text-dark">
                                        <i class="fas fa-mouse-pointer text-muted mr-2"></i><?php echo $user["email"]; ?></a>
                                    <address class="fs-sm fw-400 mt-4 text-muted">
                                        <i class="fas fa-map-pin mr-2"></i><?php echo $user["address"]; ?></address>
                                    <div class="d-flex flex-row">
                                        <a href="<?php !empty($user["vk_link"]) ? (print $user["vk_link"]) : (print `javascript:void(0);`); ?>" class="mr-2 fs-xxl" style="color:#4680C2">
                                            <i class="fab fa-vk"></i>
                                        </a>
                                        <a href="<?php !empty($user["telegram_link"]) ? (print $user["telegram_link"]) : (print `javascript:void(0);`); ?>" class="mr-2 fs-xxl" style="color:#38A1F3">
                                            <i class="fab fa-telegram"></i>
                                        </a>
                                        <a href="<?php !empty($user["instagram_link"]) ? (print $user["instagram_link"]) : (print `javascript:void(0);`); ?>" class="mr-2 fs-xxl" style="color:#E1306C">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>


                
            </div>
        </main>
     
        <!-- BEGIN Page Footer -->
        <footer class="page-footer" role="contentinfo">
            <div class="d-flex align-items-center flex-1 text-muted">
                <span class="hidden-md-down fw-700">2020 © Учебный проект</span>
            </div>
            <div>
                <ul class="list-table m-0">
                    <li><a href="intel_introduction.html" class="text-secondary fw-700">Home</a></li>
                    <li class="pl-3"><a href="info_app_licensing.html" class="text-secondary fw-700">About</a></li>
                </ul>
            </div>
        </footer>
        
    </body>

    <script src="js/vendors.bundle.js"></script>
    <script src="js/app.bundle.js"></script>
    <script>

        $(document).ready(function()
        {

            $('input[type=radio][name=contactview]').change(function()
                {
                    if (this.value == 'grid')
                    {
                        $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-g');
                        $('#js-contacts .col-xl-12').removeClassPrefix('col-xl-').addClass('col-xl-4');
                        $('#js-contacts .js-expand-btn').addClass('d-none');
                        $('#js-contacts .card-body + .card-body').addClass('show');

                    }
                    else if (this.value == 'table')
                    {
                        $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-1');
                        $('#js-contacts .col-xl-4').removeClassPrefix('col-xl-').addClass('col-xl-12');
                        $('#js-contacts .js-expand-btn').removeClass('d-none');
                        $('#js-contacts .card-body + .card-body').removeClass('show');
                    }

                });

                //initialize filter
                initApp.listFilter($('#js-contacts'), $('#js-filter-contacts'));
        });

    </script>
</html>