<h1> Authors: </h1>

<ul>
    <?php foreach ($authors as $author):?>
        <li><?php echo e($author->name);?></li>
    <?php endforeach; ?>
</ul>