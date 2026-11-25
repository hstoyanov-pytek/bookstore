<div class="gallery-container">
    <?php foreach ($results as $result): ?>
    <div class="gallery-item">
    <a href="/book?<?php echo http_build_query(['id' => $result->book_id]);?>">
        <img src="<?php echo e(PROJECT_ROOT);?>uploads/<?php echo e($result->book_cover_name);?>">
        <h3><?php echo e($result->book_title);?></h3>
        <p>By: <?php echo e($result->book_author);?></p>
    </a>
    </div>
    <?php endforeach; ?>

    <div class="gallery-item">
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . PAGINATION_PATH; ?>
    </div>

</div>
