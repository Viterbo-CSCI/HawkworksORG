<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Uncomment and modify the following according to your admin flag/session setup
/*
if (!$_SESSION["isAdmin"]) {
    header("location: index.php"); // Or any other non-admin page
    exit;
}
*/
