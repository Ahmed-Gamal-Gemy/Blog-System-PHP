<?php

session_start();

require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $postID = (int)$_GET["id"];

    $newTitle = trim($_POST["title"] ?? "");
    $newDescription = trim($_POST["body"] ?? "");
    $newImage = $_FILES["image"];
    $errors = [];

    // check if user didn't insert any data

    if (empty($newTitle) || empty($newDescription)) {
        $errors[] = "You Must Fill All Inputs";
    }

    // get old data for post from db

    $query = "SELECT title , body , image FROM posts WHERE id = ? AND user_id = ?";

    $stmt = mysqli_prepare($connection, $query);

    mysqli_stmt_bind_param($stmt, "is", $postID, $_SESSION["user_id"]);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    mysqli_stmt_close($stmt);

    $oldPost = mysqli_fetch_assoc($result);

    $oldTitle = $oldPost["title"];
    $oldDescription = $oldPost["body"];
    $oldImage = $oldPost["image"];

    // check if title and body and image are  same

    $titleChanged = $newTitle !== $oldTitle;
    $descriptionChanged = $newDescription !== $oldDescription;
    $imageChanged = $newImage["error"] === 0;

    if (!$titleChanged && !$descriptionChanged && !$imageChanged) {
        $errors[] = "You Didn't Change AnyThing";
        $_SESSION["errors"] = $errors;
        header("location:../editPost.php?id=$postID");
        exit();
    }

    $imageNewName = $oldImage;

    if ($imageChanged) {
        $imageExtension = strtolower(pathinfo($newImage["name"], PATHINFO_EXTENSION));
        $imageSize = $newImage["size"];
        $extensionsAllowed = ["jpeg", "png", "jpg", "webp", "jfif"];
        $imageNewName = uniqid() . "." . $imageExtension;

        if ($imageSize > (4 * 1024 * 1024)) {
            $errors[] = "Image Size Must Be Less Than 4MB";;
        }
        if (!in_array($imageExtension, $extensionsAllowed)) {
            $errors[] = "Image Extension Must Be jpg, jpeg, png, webp, jfif";
        }
    }

    if (!empty($errors)) {
        $_SESSION["errors"] = $errors;
        header("location:../editPost.php?id=$postID");
        exit();
    }

    if (empty($errors)) {

        $query = "UPDATE posts set title = ? , body = ? , image = ? , user_id = ? WHERE id = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssii", $newTitle, $newDescription, $imageNewName, $_SESSION["user_id"], $postID);
        $executeResult = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($executeResult) {

            if ($imageChanged) {
                move_uploaded_file($newImage["tmp_name"], "../assets/images/postimage/" . $imageNewName);
                unlink("../assets/images/postimage/" . $oldImage);
            }

            $_SESSION["success"] = "Updated Successfully";
            header("location:../editPost.php?id=$postID");
            exit();
        }
    }
} else {
    header("location: ../editPost.php");
    exit();
}