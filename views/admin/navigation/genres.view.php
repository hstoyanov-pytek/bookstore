<h1> Genres: </h1>

<ul>
    <?php foreach ($genres as $genre):?>
        <li><a href="/admin/genres?<?php echo http_build_query(['genre'=> $genre->name])?>"><?php echo e($genre->name);?></a></li>
    <?php endforeach; ?>
</ul>