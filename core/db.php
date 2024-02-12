<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost'; // or your database host, e.g., a remote server
$dbname = 'mydatabase'; // your database name
$username = 'newuser'; // your database username
$password = 'password'; // your database password
$charset = 'utf8mb4';

// DSN (Data Source Name) string
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// Options for PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Establishing a connection
try {
    $conn = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
