<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <title>
            Подготовительные задания к курсу
        </title>
        <meta name="description" content="Chartist.html">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
        <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="css/vendors.bundle.css">
        <link id="appbundle" rel="stylesheet" media="screen, print" href="css/app.bundle.css">
        <link id="myskin" rel="stylesheet" media="screen, print" href="css/skins/skin-master.css">
        <link rel="stylesheet" media="screen, print" href="css/statistics/chartist/chartist.css">
        <link rel="stylesheet" media="screen, print" href="css/miscellaneous/lightgallery/lightgallery.bundle.css">

        <link rel="stylesheet" media="screen, print" href="css/fa-solid.css">
        <link rel="stylesheet" media="screen, print" href="css/fa-brands.css">
        <link rel="stylesheet" media="screen, print" href="css/fa-regular.css">
    </head>
    <body class="mod-bg-1 mod-nav-link ">
        <?php  //Хотел спросить надо так же упрощать данные как в видео или нет.
        $people = [
            [
            "tags_name" => "oliver kopyov",
            "status" => "success",
            "avatar_img" => "img/demo/avatars/avatar-b.png",
            "name" => "Oliver Kopyov",
            "profession" => "IT Director, Gotbootstrap Inc.",
            "tel_href" => "+13174562564",
            "tel" => " +1 317-456-2564",
            "email" => "oliver.kopyov@smartadminwebapp.com",
            "address" => "15 Charist St, Detroit, MI, 48212, USA",
            ],
            [
            "tags_name" => "sesha gray",
            "status" => "warning",
            "avatar_img" => "img/demo/avatars/avatar-c.png",
            "name" => "Sesha Gray",
            "profession" => "Project Manager, Gotbootstrap Inc.",
            "tel_href" => "+13174562564",
            "tel" => " +1 313-461-1347",
            "email" => "sesha.gray@smartadminwebapp.com",
            "address" => " 134 Hamtrammac, Detroit, MI, 48314, USA",
            ],
            [
            "tags_name" => "dr john cook",
            "status" => "danger",
            "avatar_img" => "img/demo/avatars/avatar-e.png",
            "name" => "Dr. John Cook PhD",
            "profession" => "Human Resources, Gotbootstrap Inc.",
            "tel_href" => "+13174562564",
            "tel" => "+1 313-779-1347",
            "email" => "john.cook@smartadminwebapp.com",
            "address" => " 55 Smyth Rd, Detroit, MI, 48341, USA",
            ],
            [
            "tags_name" => "jim ketty",
            "status" => "success",
            "avatar_img" => "img/demo/avatars/avatar-k.png",
            "name" => "Jim Ketty",
            "profession" => "Staff Orgnizer, Gotbootstrap Inc",
            "tel_href" => "+13174562564",
            "tel" => "+1 313-779-3314",
            "email" => "jim.ketty@smartadminwebapp.com",
            "address" => "134 Tasy Rd, Detroit, MI, 48212, USA",
            ],
            [
            "tags_name" => "oliver kopyov",
            "status" => "success",
            "avatar_img" => "img/demo/avatars/avatar-b.png",
            "name" => "Oliver Kopyov",
            "profession" => "IT Director, Gotbootstrap Inc.",
            "tel_href" => "+13174562564",
            "tel" => " +1 317-456-2564",
            "email" => "oliver.kopyov@smartadminwebapp.com",
            "address" => "15 Charist St, Detroit, MI, 48212, USA",
            ],
            [
            "tags_name" => "sesha gray",
            "status" => "warning",
            "avatar_img" => "img/demo/avatars/avatar-c.png",
            "name" => "Sesha Gray",
            "profession" => "Project Manager, Gotbootstrap Inc.",
            "tel_href" => "+13174562564",
            "tel" => " +1 313-461-1347",
            "email" => "sesha.gray@smartadminwebapp.com",
            "address" => " 134 Hamtrammac, Detroit, MI, 48314, USA",
            ],
            [
            "tags_name" => "dr john cook",
            "status" => "danger",
            "avatar_img" => "img/demo/avatars/avatar-e.png",
            "name" => "Dr. John Cook PhD",
            "profession" => "Human Resources, Gotbootstrap Inc.",
            "tel_href" => "+13174562564",
            "tel" => "+1 313-779-1347",
            "email" => "john.cook@smartadminwebapp.com",
            "address" => " 55 Smyth Rd, Detroit, MI, 48341, USA",
            ],
            [
            "tags_name" => "jim ketty",
            "status" => "success",
            "avatar_img" => "img/demo/avatars/avatar-k.png",
            "name" => "Jim Ketty",
            "profession" => "Staff Orgnizer, Gotbootstrap Inc",
            "tel_href" => "+13174562564",
            "tel" => "+1 313-779-3314",
            "email" => "jim.ketty@smartadminwebapp.com",
            "address" => "134 Tasy Rd, Detroit, MI, 48212, USA",
            ],
    
        ];
        ?>
        <main id="js-page-content" role="main" class="page-content">
            <div class="row">
                <div class="col-xl-12">
                    <div class="border-faded bg-faded p-3 mb-g d-flex">
                        <input type="text" id="js-filter-contacts" name="filter-contacts" class="form-control shadow-inset-2 form-control-lg" placeholder="Filter contacts">
                        <div class="btn-group btn-group-lg btn-group-toggle hidden-lg-down ml-3" data-toggle="buttons">
                            <label class="btn btn-default active waves-effect waves-themed">
                                <input type="radio" name="contactview" id="grid" checked="" value="grid"><i class="fas fa-table"></i>
                            </label>
                            <label class="btn btn-default waves-effect waves-themed">
                                <input type="radio" name="contactview" id="table" value="table"><i class="fas fa-th-list"></i>
                            </label>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row js-list-filter" id="js-contacts">
    <?php foreach($people as $person): ?>

        
            <div class="col-xl-4">
                <div id="c_1" class="card border shadow-0 mb-g shadow-sm-hover" data-filter-tags="<?php echo $person["tags_name"]?> ">
                    <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                        <div class="d-flex flex-row align-items-center">
                            <span class="status status-<?php echo $person["status"]?> mr-3">
                                <span class="rounded-circle profile-image d-block " style="background-image:url('<?php echo $person["avatar_img"]?>'); background-size: cover;"></span>
                            </span>
                            <div class="info-card-text flex-1">
                                <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-info" data-toggle="dropdown" aria-expanded="false">
                                    <?php echo $person["name"]?>
                                    <i class="fal fa-angle-down d-inline-block ml-1 fs-md"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Send Email</a>
                                    <a class="dropdown-item" href="#">Create Appointment</a>
                                    <a class="dropdown-item" href="#">Block User</a>
                                </div>
                                <span class="text-truncate text-truncate-xl"><?php echo $person["profession"]?></span>
                            </div>
                            <button class="js-expand-btn btn btn-sm btn-default d-none waves-effect waves-themed" data-toggle="collapse" data-target="#c_1 > .card-body + .card-body" aria-expanded="false">
                                <span class="collapsed-hidden">+</span>
                                <span class="collapsed-reveal">-</span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0 collapse show">
                        <div class="p-3">
                            <a href="tel:+13174562564" class="mt-1 d-block fs-sm fw-400 text-dark">
                                <i class="fas fa-mobile-alt text-muted mr-2"></i> +1 317-456-2564</a>
                            <a href="mailto:<?php echo $person["email"]?>" class="mt-1 d-block fs-sm fw-400 text-dark">
                                <i class="fas fa-mouse-pointer text-muted mr-2"></i> <?php echo $person["email"]?></a>
                            <address class="fs-sm fw-400 mt-4 text-muted">
                                <i class="fas fa-map-pin mr-2"></i><?php echo $person["address"]?></address>
                            <div class="d-flex flex-row">
                                <a href="javascript:void(0);" class="mr-2 fs-xxl" style="color:#3b5998">
                                    <i class="fab fa-facebook-square"></i>
                                </a>
                                <a href="javascript:void(0);" class="mr-2 fs-xxl" style="color:#38A1F3">
                                    <i class="fab fa-twitter-square"></i>
                                </a>
                                <a href="javascript:void(0);" class="mr-2 fs-xxl" style="color:#0077B5">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <?php  endforeach; ?>




            </div>
        </main>
        

        <script src="js/vendors.bundle.js"></script>
        <script src="js/app.bundle.js"></script>
        <script src="js/miscellaneous/lightgallery/lightgallery.bundle.js"></script>
        <script>
            // default list filter
            initApp.listFilter($('#js_default_list'), $('#js_default_list_filter'));
            // custom response message
            initApp.listFilter($('#js-list-msg'), $('#js-list-msg-filter'));
            //accordion filter
            initApp.listFilter($('#js_list_accordion'), $('#js_list_accordion_filter'));
            // nested list filter
            initApp.listFilter($('#js_nested_list'), $('#js_nested_list_filter'));
            //init navigation 
            initApp.buildNavigation($('#js_nested_list'));

            $(document).ready(function()
            {


                var $initScope = $('#js-lightgallery');
                if ($initScope.length)
                {
                    $initScope.justifiedGallery(
                    {
                        border: -1,
                        rowHeight: 150,
                        margins: 8,
                        waitThumbnailsLoad: true,
                        randomize: false,
                    }).on('jg.complete', function()
                    {
                        $initScope.lightGallery(
                        {
                            thumbnail: true,
                            animateThumb: true,
                            showThumbByDefault: true,
                        });
                    });
                };
                $initScope.on('onAfterOpen.lg', function(event)
                {
                    $('body').addClass("overflow-hidden");
                });
                $initScope.on('onCloseAfter.lg', function(event)
                {
                    $('body').removeClass("overflow-hidden");
                });

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
