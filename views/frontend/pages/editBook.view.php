<h1>Editing book: </h1>

<form method="POST" action="index.php?<?php echo http_build_query(['page' => 'edit', 'book_id' => e($result->id)])?>">

    <label for="title">Title: </label>
    <input type="text" id="title" name="title" value="<?php echo e($result->book_title);?>"/>

    <label for="publisher_id">Publisher: </label>
    <input type="text" id="publisher_id" name="publisher_id" value="<?php echo e($result->publisher);?>"/>

    <label for="year_published">Year published: </label>
    <input type="text" id="year_published" name="year_published" value="<?php echo e($result->year_published); ?>"/>

    <label for="author">Author: </label>
        <select name="author" id="author">
            <?php foreach($authors as $author): ?>
                <option value="<?php echo e($author->name); ?>"><?php echo e($result->book_author);?></option>
            <?php endforeach;?>
        </select>

    <label for="genre">Genre: </label>
    <select name="genre" id="genre">
        <?php foreach($genres as $genre): ?>
            <option value="<?php echo e($genre->name); ?>"><?php echo e($result->book_genres);?></option>
        <?php endforeach;?>
    </select>

    <label for="language">Language: </label>
    <select name="language" id="language">
        <?php foreach($languages as $language): ?>
            <option value="<?php echo e($language->name); ?>"><?php echo e($result->book_languages);?></option>
        <?php endforeach;?>
    </select>

    <label for="available">Available:</label>
    <input type="checkbox" value="true" name="available" id="available"
           <?php echo $result->book_available ? e("checked") : ""; ?>/>

    <br>
    <input type="submit">
</form>