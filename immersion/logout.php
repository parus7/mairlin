<?php
unset($_SESSION["user"]["user_id"]);
header("location: page_login.php");
