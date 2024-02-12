<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload the Composer autoload file
//require_once 'vendor/autoload.php';
require __DIR__ . '/../vendor/autoload.php';


// Now you can directly use HTMLPurifier without explicitly requiring it
$dirty_html = "<script>alert('XSS');</script>";
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
$clean_html = $purifier->purify($dirty_html);
echo $clean_html;
?>
