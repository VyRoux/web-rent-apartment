<?php
define('CAN_ACCESS', true);
require_once '../config.php'; // Pastikan path ini benar

session_start();
session_unset();
session_destroy();

header("location: " . BASE_URL . "index.php");
exit();
?>