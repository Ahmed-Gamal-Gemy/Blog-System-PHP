<?php

session_start();
require_once "config/db.php";

if (!isset($_GET["id"]) || empty($_GET["id"]) || !ctype_digit($_GET["id"])) {
    header("location:404.php");
    exit();
}

$postID = $_GET["id"];

// validate if the post id is not exist

$query = "SELECT id, image FROM posts where id = ? LIMIT 1";

$stmt = mysqli_prepare($connection, $query);

mysqli_stmt_bind_param($stmt, "s", $postID);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

mysqli_stmt_close($stmt);

if (mysqli_num_rows($result) == 0) {
    header("location:404.php");
    exit();
}

$postImage = mysqli_fetch_assoc($result)["image"];
$imagePath = "assets/images/postimage/" . $postImage;


// delete post from db

$query = "DELETE FROM posts WHERE id = ?";

$stmt = mysqli_prepare($connection, $query);

mysqli_stmt_bind_param($stmt, "s", $postID);

$executeResult =  mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
if ($executeResult) {
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
    $_SESSION["success"] = "Deleted Successfully";
    header("location:index.php");
    exit();
}