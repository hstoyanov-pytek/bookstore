<div class="gallery-container">
    <?php foreach ($results as $result): ?>
    <div class="gallery-item">
    <a href="/book?<?php echo http_build_query(['id' => $result->book_id]);?>">
        <img src="uploads/<?php echo e($result->book_cover_name);?>">
        <h3><?php echo e($result->book_title);?></h3>
        <p>By: <?php echo e($result->book_author);?></p>
    </a>
    </div>
    <?php endforeach; ?>

    <div class="gallery-item">
        <?php if($page > 1) : ?>
            <a href="?<?php echo http_build_query(['page'=> max(1, $page - 1)]) ?>">Previous</a>
        <?php endif;?>
        <a href="?<?php echo http_build_query(['page'=> $page + 1]) ?>">Next</a>
    </div>

</div>
