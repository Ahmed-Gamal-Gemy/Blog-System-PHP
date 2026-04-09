<?php require_once 'inc/header.php';
if (empty($_GET["id"]) || !isset($_GET["id"])) {
    header("location:404.php");
    exit();
}

require_once "config/db.php";
$post_id = (int)$_GET["id"];

$query = "SELECT id,title,image,body FROM posts WHERE id = ? ";

$stmt = mysqli_prepare($connection, $query);

mysqli_stmt_bind_param($stmt, "i", $post_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

mysqli_stmt_close($stmt);

if (mysqli_num_rows($result) === 0) {
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
                    <h4>Edit Post</h4>
                    <h2>edit your personal post</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container w-50 ">
    <?php require_once "inc/errors.php";
    require_once "inc/success.php"; ?>
    <div class="d-flex justify-content-center">
        <h3 class="my-5">edit Post</h3>
    </div>

    <form method="POST" action="handle/handleEditPost.php?id=<?= $post["id"] ?>" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= $post["title"]; ?>">
        </div>
        <div class="mb-3">
            <label for="body" class="form-label">Body</label>
            <textarea class="form-control" id="body" name="body" rows="5"><?= $post["body"] ?></textarea>
        </div>
        <div class="mb-3">
            <label for="body" class="form-label">image</label>
            <input type="file" class="form-control-file" id="image" name="image">
        </div>
        <img src="assets/images//postImage/<?php echo $post['image'] ?>" alt="" width="100px" name="old">
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>


<?php require_once 'inc/footer.php' ?>