<form>
    <input type="submit" value="Filter">
<div class="grid">
    <div class="filter" name="authorFilter">
    <ul class="no-bullets">
        <?php foreach ($this->filterAuthors as $author):?>
            <li>
                <input type="checkbox" name="<?php echo e($author->name); ?>">
                <label for="<?php echo e($author->name); ?>"><?php echo e($author->name);?> (<?php echo e($author->count);?>)</label>
            </li>
        <?php endforeach; ?>
    </ul>
    </div>

    <div class="filter" name="languageFilter">
    <ul class="no-bullets">
        <?php foreach ($this->filterLanguages as $language):?>
            <li>
                <input type="checkbox" name="<?php echo e($language->name); ?>">
                <label for="<?php echo e($language->name); ?>"><?php echo e($language->name);?> (<?php echo e($language->count);?>)</label>
            </li>
        <?php endforeach; ?>
    </ul>
    </div>

    <div class="filter" name="publisherFilter">
    <ul class="no-bullets">
        <?php foreach ($this->filterPublishers as $publisher):?>
            <li>
                <input type="checkbox" name="<?php echo e($publisher->name); ?>">
                <label for="<?php echo e($publisher->name); ?>"><?php echo e($publisher->name);?> (<?php echo e($publisher->count);?>)</label>
            </li>
        <?php endforeach; ?>
    </ul>
    </div>

    <div class="filter" name="genreFilter">
    <ul class="no-bullets">
        <?php foreach ($this->filterGenres as $genre):?>
            <li>
                <input type="checkbox" name="<?php echo e($genre->name); ?>">
                <label for="<?php echo e($genre->name); ?>"><?php echo e($genre->name);?> (<?php echo e($genre->count);?>)</label>
            </li>
        <?php endforeach; ?>
    </ul>
    </div>
</div>
</form>