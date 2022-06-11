<?php
session_start();


  unset($_SESSION["user"]);
  exit(header("Location: task_17.php")) ;