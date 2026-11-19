<h1> Genres: </h1>

<ul>
    <?php foreach ($genres as $genre):?>
        <li><?php echo e($genre->name);?></li>
    <?php endforeach; ?>
</ul>