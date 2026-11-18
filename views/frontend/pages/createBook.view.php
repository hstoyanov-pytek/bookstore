<h1>Create a book entry: </h1>

<form method="POST" action="index.php?<?php echo http_build_query(['page' => 'create'])?>">

    <label for="title">Title: </label>
    <input type="text" id="title" name="title" value=""/>

    <label for="author">Author: </label>
<!--    <select name="author" id="author">-->
<!--        --><?php //foreach($authors as $author): ?>
<!--            <option value="--><?php //echo e($author->name); ?><!--">--><?php //echo e($author->name);?><!--</option>-->
<!--        --><?php //endforeach;?>
<!--    </select>-->
    <input type="text" name="author" id="author" value=""/>

    <label for="genre">Genre: </label>
    <select name="genre" id="genre">
        <?php foreach($genres as $genre): ?>
            <option value="<?php echo e($genre->name); ?>"><?php echo e($genre->name);?></option>
        <?php endforeach;?>
    </select>

    <label for="language">Language: </label>
    <select name="language" id="language">
        <?php foreach($languages as $language): ?>
            <option value="<?php echo e($language->name); ?>"><?php echo e($language->name);?></option>
        <?php endforeach;?>
    </select>

    <label for="publisher">Publisher: </label>
    <input type="text" id="publisher" name="publisher" value=""/>

    <label for="year_published">Year published: </label>
    <input type="date" id="year_published" name="year_published" value="<?php echo date("Y-m-d)");?>"/>

    <br>
    <input type="submit">
</form>
