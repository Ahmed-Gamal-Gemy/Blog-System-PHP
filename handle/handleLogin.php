<?php

session_start();

require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    $errors = [];

    if (!empty($email) && !empty($password)) {
        $query = "SELECT id,name,password,email FROM users WHERE email = ? LIMIT 1";
        $stmt = mysqli_prepare($connection, $query);

        mysqli_stmt_bind_param($stmt, "s", $email);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);


        mysqli_stmt_close($stmt);

        if (mysqli_num_rows($result) == 0) {
            $errors[] = "Invalid Email Or Password";
        } else {
            $user = mysqli_fetch_assoc($result);
            $verify = password_verify($password, $user["password"]);

            if ($verify) {
                $userID = $user["id"];
                $_SESSION["success"] = "You Are Login Now";
                $_SESSION["user_id"] = $userID;
                $_SESSION["email"] = $user["email"];
                header(header: "location: ../index.php");
                exit();
            } else {
                $errors[] = "Invalid Email Or Password";
            }
        }
    } else {
        $errors[] = "Email And Password Are Required";
        $_SESSION["email"] = $email;
    }

    if (!empty($errors)) {
        $_SESSION["errors"] = $errors;

        header("location:../login.php");
        exit();
    }
} else {
    header("location:../login.php");
    exit();
}