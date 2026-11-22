<h3>Editing author: <?php echo e($author);?></h3>
<ul>
<?php foreach ($results as $result): ?>
    <li><?php echo e($result['book_title']); ?></li>
<?php endforeach; ?>
</ul>