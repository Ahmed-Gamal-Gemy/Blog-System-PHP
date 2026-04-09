<?php

$localhost = "localhost";
$username = "root";
$password = "";
$db_name = "blog";
$connection = mysqli_connect($localhost, $username, $password, $db_name);

if (!$connection) {
    echo "Connection Error";
    exit();
}