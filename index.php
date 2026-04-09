<?php
require_once 'inc/header.php';

require_once "config/db.php";


// create pagination

$paginationQuery = "SELECT COUNT(id) as total FROM posts";

$result = mysqli_query($connection, $paginationQuery);

$postsCount = mysqli_fetch_assoc($result)["total"];

$page = $_GET["page"] ?? 1;

if (!ctype_digit($page) || $page < 1 || !isset($page)) {
    header("location:index.php?page=1");
    exit();
}


$limit = 5;

$offset = ($page - 1) * $limit;

$pagesCount = ceil($postsCount / $limit);


if ($page > $pagesCount) {
    header("location:index.php?page=$pagesCount");
    exit();
}





// fetch posts

$query = "SELECT posts.id, title, image, body as description , users.name as user FROM posts JOIN users on posts.user_id = users.id LIMIT $limit OFFSET $offset";

$result = mysqli_query($connection, $query);

if ($result) {
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $posts = null;
}



?>
<!-- Page Content -->
<!-- Banner Starts Here -->
<div class="banner header-text">
    <div class="owl-banner owl-carousel">
        <div class="banner-item-01">
            <div class="text-content">
                <!-- <h4>Best Offer</h4> -->
                <!-- <h2>New Arrivals On Sale</h2> -->
            </div>
        </div>
        <div class="banner-item-02">
            <div class="text-content">
                <!-- <h4>Flash Deals</h4> -->
                <!-- <h2>Get your best products</h2> -->
            </div>
        </div>
        <div class="banner-item-03">
            <div class="text-content">
                <!-- <h4>Last Minute</h4> -->
                <!-- <h2>Grab last minute deals</h2> -->
            </div>
        </div>
    </div>
</div>
<!-- Banner Ends Here -->

<div class="latest-products">
    <?php require_once "inc/success.php" ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading">
                    <h2>Latest Posts</h2>
                    <!-- <a href="products.html">view all products <i class="fa fa-angle-right"></i></a> -->
                </div>
            </div>
            <?php if ($posts != null): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-4">
                        <div class="product-item">
                            <a href="#"><img src="assets/images/postImage/<?php echo $post["image"] ?>" alt="image"></a>
                            <div class="down-content">
                                <a href="#">
                                    <h4><?= $post["title"]; ?></h4>
                                </a>
                                <h6><?= $post["user"] ?></h6>
                                <p> <?= $post["description"] ?></p>
                                <div class="d-flex justify-content-end">
                                    <a href="viewPost.php?id=<?= $post["id"] ?>" class="btn btn-info "> view</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-md-4">
                    <div class="product-item">
                        <div class="down-content">
                            <h4>No Posts Found</h4>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center mt-4">
                    <!-- Previous -->
                    <li class="page-item <?= $page == 1 ? "disabled" : ""; ?>">
                        <a class="page-link" href="index.php?page=<?= $page - 1 ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $pagesCount; $i++): ?>
                        <!-- Pages -->
                        <li class="page-item <?= $i == $page ? "active" : "" ?>">
                            <a class="page-link" href="<?= "index.php?page=$i" ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <!-- Next -->
                    <li class="page-item <?= $page == $pagesCount ? "disabled" : "" ?>">
                        <a class="page-link" href="index.php?page=<?= $page + 1 ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>



<?php require_once 'inc/footer.php' ?>