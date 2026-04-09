<?php

session_start();

$lang = isset($_SESSION["lang"]) ? $_SESSION["lang"] : "en";

if ($lang == "ar") {
    require_once "lang/language_ar.php";
} else {
    require_once "lang/language_en.php";
}

$dir = $lang == "ar" ? "rtl" : "ltr";

if (isset($_SESSION["user_id"])) {
    // do something here
} else {
    header("location:login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" dir="<?= $dir ?>">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap"
        rel="stylesheet">

    <title>Blog</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!--

    TemplateMo 546 Sixteen Clothing

    https://templatemo.com/tm-546-sixteen-clothing

    -->

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-sixteen.css">
    <link rel="stylesheet" href="assets/css/owl.css">

</head>

<body>



    <!-- Header -->
    <header class="padding-0">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <h2> <em><?= $language["Blog"] ?></em></h2>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                    aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="index.php"><?= $language["AllPosts"] ?>
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="addPost.php"><?= $language["AddPost"] ?></a>
                        </li>
                        <?php if ($lang == "ar") : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="inc/lang.php?lang=en">English</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="inc/lang.php?lang=ar">العربية</a>
                            </li>
                        <?php endif ?>
                        <?php if (isset($_SESSION["user_id"])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="handle/handlelogout.php"><?= $language["Logout"] ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><?= $_SESSION["email"]; ?></a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="register.php">Register</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>