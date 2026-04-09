<?php

session_start();

require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $postTitle = trim($_POST["title"] ?? "");
    $postDescription = trim($_POST["body"] ?? "");
    $image = $_FILES["image"];
    $imageName = $image["name"];
    $imageTmpName = $image["tmp_name"];
    $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
    $imageSizeBytes = $image["size"];
    $imageError = $image["error"];
    $extensionsAllowed = ["jpeg", "png", "jpg", "webp", "jfif"];
    $uploadPath = "../assets/images/postimage/";
    $errors = [];

    if (empty($postTitle) || empty($postDescription)) {
        $errors[] = "You Must Fill All Inputs";
    }
    // check duplicate post

    $query = "SELECT id FROM posts WHERE title = ? AND user_id = ? LIMIT 1";
    $stmt = mysqli_prepare($connection, $query);

    mysqli_stmt_bind_param($stmt, "si", $postTitle, $_SESSION["user_id"]);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    mysqli_stmt_close($stmt);

    if (mysqli_num_rows($result) > 0) {
        $errors[] = "The Post Already Exists in The User Posts";
    }

    if ($imageError === 4) {
        $errors[] = "Image Is Required";
    }
    if ($imageError === 0) {
        if ($imageSizeBytes > (4 * 1024 * 1024)) {
            $errors[] = "Image Size Must Be Less Than 4MB";
        }
        if (!in_array($imageExtension, $extensionsAllowed)) {
            $errors[] = "Image Extension Must Be jpg, jpeg, png, webp, jfif";
        }
    }

    if (!empty($errors)) {
        $_SESSION["errors"] = $errors;
        $_SESSION["title"] = $postTitle;
        $_SESSION["description"] = $postDescription;
        header("location:../addPost.php");
        exit();
    }

    $imageNewName = uniqid() . time() . "." . $imageExtension;
    $query = "INSERT INTO posts (`title`,`image`,`body`,`user_id`) VALUES (?,?,?,?)";

    $stmt = mysqli_prepare($connection, $query);

    mysqli_stmt_bind_param($stmt, "sssi", $postTitle, $imageNewName, $postDescription, $_SESSION["user_id"]);

    $executeStats = mysqli_stmt_execute($stmt);
    if ($executeStats) {
        move_uploaded_file($imageTmpName, $uploadPath . $imageNewName);
        $_SESSION["success"] = "Inserted Successfully";
        header("location:../addPost.php");
        exit();
    }
} else {
    header("location:../addPost.php");
    exit();
}