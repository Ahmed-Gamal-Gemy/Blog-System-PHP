<?php



if (!empty($_SESSION["errors"])):
?>
<?php foreach ($_SESSION["errors"] as $errors): ?>
<div class="alert alert-danger" role="alert">
    <?= $errors ?>
</div>
<?php endforeach; ?>

<?php unset($_SESSION["errors"]);
endif; ?>