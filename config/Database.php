<?php

$host = "localhost";
$dbname = "id22319804_datshoes";
$username = "id22319804_rizqynurf";
$password = "Fauzella171.";

$mysqli = new mysqli(
    $host,
    $username,
    $password,
    $dbname,
);

if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;