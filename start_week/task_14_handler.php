<?php 

session_start();

if($_POST['textFrm']=="") {
  unset($_SESSION['outText']);
  header("Location: task_14.php");
}

$_SESSION['outText'] = $_POST['textFrm'];

header("Location: task_14.php");