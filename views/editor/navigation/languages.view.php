<h1> Languages: </h1>

<ul>
    <?php foreach ($languages as $language):?>
        <li><?php echo e($language->name);?></li>
    <?php endforeach; ?>
</ul>