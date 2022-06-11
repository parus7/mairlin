<?php
session_start();

$_SESSION['count_event'] += 1 ;
header("Location: task_15.php");