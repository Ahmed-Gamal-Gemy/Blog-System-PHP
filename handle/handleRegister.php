<?php
session_start();
require_once "../config/db.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $phone = trim($_POST["phone"] ?? "");

    $errors = [];

    if (empty($name) || empty($email) || empty($password) || empty($phone)) {
        $errors[] = "You Must Fill All Data";
    }

    if (strlen($name) < 8) {
        $errors[] = "User Name Must Be At Least 8 Characters";
    }

    $validEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$validEmail) {
        $errors[] = "Email Is Invalid. Please Try Again";
    }


    if ($validEmail) {
        $query = "SELECT id FROM users WHERE email = ?";

        $stmt = mysqli_prepare($connection, $query);

        mysqli_stmt_bind_param($stmt, "s", $email);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Email Is Exist";
        }
        mysqli_stmt_close($stmt);
    }

    if (strlen($password) < 6) {
        $errors[] = "Password Must Be At Least 6 Charcters";
    }
    if (!ctype_digit($phone)) {
        $errors[] = "Phone Must Be Digits Only";
    }

    if (!empty($errors)) {
        $_SESSION["userName"] = $name;
        $_SESSION["email"] = $email;
        $_SESSION["phone"] = $phone;
        $_SESSION["errors"] = $errors;
        header("location:../register.php");
        exit();
    }

    $hash_password = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users(`name`,`email`,`password`,`phone`) VALUES (?,?,?,?)";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hash_password, $phone);

    $execute_Stats = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($execute_Stats) {
        $_SESSION["success"] = "Register Successfully";
        header("location:../login.php");
        exit();
    }
} else {
    header("location:../register.php");
    exit();
}