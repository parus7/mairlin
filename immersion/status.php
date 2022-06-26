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
</head>
<body>
    <?php 
    session_start(); 
    $get_id = $_GET["id"];
        /**
        *	Parameters:
        *		string = URL страницы при ошибке авторизации
        *	
        *	Description:  Проверка прав на действие требуется администратор или автор записи на странице
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

        //========  end function list ========//

        $err_page_url= "users.php";
        (isset($_GET["id"])) ? $get_id = $_GET["id"] : header("location: ". $err_page_url);
        

        $user = autoriztion4editing($get_id, $err_page_url);
        $user_id = $user["id"];
        
        // передаем в hanler id пользователя которого редактируем.
        $_SESSION["user_editing"] = $get_id;
    ?>


    <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
        <a class="navbar-brand d-flex align-items-center fw-500" href="users.html"><img alt="logo" class="d-inline-block align-top mr-2" src="img/logo.png"> Учебный проект</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Главная <span class="sr-only">(current)</span></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="page_login.html">Войти</a>
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
                <i class='subheader-icon fal fa-sun'></i> Установить статус
            </h1>

        </div>
        <form action="status_handler.php" method="post">
            <div class="row">
                <div class="col-xl-6">
                    <div id="panel-1" class="panel">
                        <div class="panel-container">
                            <div class="panel-hdr">
                                <h2>Установка текущего статуса</h2>
                            </div>
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- status -->
                                        <div class="form-group">
                                            <label class="form-label" for="example-select">Выберите статус</label>
                                            <select class="form-control" id="example-select" name="select_elem">
                                                <option>Онлайн</option>
                                                <option>Отошел</option>
                                                <option>Не беспокоить</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                        <button class="btn btn-warning">Set Status</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </form>
    </main>

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
</body>
</html>