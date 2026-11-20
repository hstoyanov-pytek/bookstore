<h1> admin/ Authors: </h1>

<ul>
    <?php foreach ($authors as $author):?>
        <li><a href="#"><?php echo e($author->name);?></a></li>
    <?php endforeach; ?>
</ul>