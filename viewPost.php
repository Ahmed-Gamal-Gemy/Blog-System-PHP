<?php require_once 'inc/header.php';
require_once "config/db.php";

if (!isset($_GET["id"]) || empty($_GET["id"]) || !ctype_digit($_GET["id"])) {
    header("location:404.php");
    exit();
}

$post_id = (int)$_GET["id"];


$query = "SELECT posts.id ,title, image, body as descript , users.name as user FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = ? LIMIT 1";

$stmt = mysqli_prepare($connection, $query);

mysqli_stmt_bind_param($stmt, "i", $post_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("location:404.php");
    exit();
} else {
    $post = mysqli_fetch_assoc($result);
}

?>

<!-- Page Content -->
<div class="page-heading products-heading header-text">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-content">
                    <h4>new Post</h4>
                    <h2>add new personal post</h2>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="best-features about-features">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading">
                    <h2><?= htmlspecialchars($post["user"]) ?></h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="right-image">
                    <img src="assets/images/postImage/<?= htmlspecialchars($post["image"]) ?>" alt="image">
                </div>
            </div>
            <div class="col-md-6">
                <div class="left-content">
                    <h4><?= htmlspecialchars($post["title"]) ?></h4>
                    <p><?= htmlspecialchars($post["descript"]) ?></p>

                    <div class="d-flex justify-content-center">
                        <a href="editPost.php?id=<?= htmlspecialchars($post["id"]) ?>" class="btn btn-success mr-3 ">
                            edit post</a>

                        <a href="deletePost.php?id=<?= htmlspecialchars($post["id"]) ?>" class="btn btn-danger ">
                            delete post</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'inc/footer.php' ?>