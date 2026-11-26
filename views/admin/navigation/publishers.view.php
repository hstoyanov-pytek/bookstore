<h1> Publishers: </h1>

<ul>
    <?php foreach ($publishers as $publisher):?>
        <li><a href="/admin/publishers?<?php echo http_build_query(['publisher'=>$publisher->name]);?>"><?php echo e($publisher->name);?></a></li>
    <?php endforeach; ?>
</ul>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . PAGINATION_PATH; ?>