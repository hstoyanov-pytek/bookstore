<h1>Editing book: </h1>

<form method="POST" action="/admin/edit?<?php echo http_build_query(['book_id' => e($result->book_id)])?>" enctype="multipart/form-data">

    <label for="title">Title: </label>
    <input type="text" id="title" name="title" value="<?php echo e($result->book_title);?>"/>

    <label for="publisher">Publisher: </label>
    <select name="publisher" id="publisher">
        <?php foreach($publishers as $publisher):?>
            <option value="<?php echo e($publisher->name);?>" <?php echo $publisher->name === $result->publisher ? e("selected") : "";?>> <?php echo e($publisher->name);?></option>
        <?php endforeach; ?>
    </select>
<!--    <input type="text" id="publisher" name="publisher" value="--><?php //echo e($result->publisher);?><!--"/>-->

    <label for="year_published">Year published: </label>
    <input type="text" id="year_published" name="year_published" value="<?php echo e($result->year_published); ?>"/>

    <label for="author">Author: </label>
        <select name="author" id="author">
            <?php foreach($authors as $author): ?>
                <option value="<?php echo e($author->name); ?>" <?php echo $author->name === $result->book_author ? e("selected") : "" ;?>><?php echo e($author->name);?></option>
            <?php endforeach;?>
        </select>

    <label for="genre">Genre: </label>
    <select name="genre" id="genre">
        <?php foreach($genres as $genre): ?>
            <option value="<?php echo e($genre->name);?>" <?php echo $genre->name === $result->book_genres ? e("selected") : "";?>><?php echo e($genre->name);?></option>
        <?php endforeach;?>
    </select>

    <label for="language">Language: </label>
    <select name="language" id="language">
        <?php foreach($languages as $language): ?>
            <option value="<?php echo e($language->name); ?>" <?php echo $language->name === $result->book_languages ? e("selected") : ""; ?>><?php echo e($language->name);?></option>
        <?php endforeach;?>
    </select>

    <label for="available">Available:</label>
    <input type="checkbox" value="true" name="available" id="available"
           <?php echo $result->book_available ? e("checked") : ""; ?>/>

    <br>
    <label for="cover">Cover Image</label>
    <input type="file" id="cover" name="cover"/>

    <br>
    <input type="submit" value="Submit">
</form>