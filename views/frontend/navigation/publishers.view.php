<h1> Publishers: </h1>

<ul>
    <?php foreach ($publishers as $publisher):?>
        <li><?php echo e($publisher->name);?></li>
    <?php endforeach; ?>
</ul>