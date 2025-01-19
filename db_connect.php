<?php
$host = "db00998.mysql.database.azure.com";
$port = "3306";      // Docker mapped port
$dbname = "videodb";
$username = "nazir";
$password = "Nazir00998";       // Your password if applicable

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; // Uncomment for debugging
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
