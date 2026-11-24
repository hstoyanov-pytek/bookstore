<h1> Authors: </h1>

<ul>
    <?php foreach ($authors as $author):?>
        <li><a href="/authors?<?php echo http_build_query(['author'=> $author->name])?>"><?php echo e($author->name);?></a></li>
    <?php endforeach; ?>
</ul>