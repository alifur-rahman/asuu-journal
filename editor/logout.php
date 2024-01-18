<?php
session_start();
$rootDir = "asuu-journal";
unset($_SESSION["username"]);
unset($_SESSION["role"]);
header("Location:/" . $rootDir . "/login");
?>