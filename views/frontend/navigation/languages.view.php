<h1> Languages: </h1>

<ul>
    <?php foreach ($languages as $language):?>
        <li><a href="/languages?<?php echo http_build_query(['language'=> $language->name])?>"><?php echo e($language->name);?></a></li>
    <?php endforeach; ?>
</ul>