<form method="POST" action="<?php if($_SESSION['permissions'] > 100) echo e("/admin") ;?>/filter">
    <input type="submit" value="Filter">
    <a href="/">Clear</a>
<div class="grid">
    <div class="filter">
        <ul class="no-bullets">
            <?php foreach($this->filterAuthors as $i => $author):?>
                <?php if($author->count > 0):?>
                <li>
                    <?php $name = "author" . e($i);?>
                    <input type="checkbox"
                           name="<?php echo e($name); ?>"
                           value="<?php echo e($author->name); ?>"
                            <?php if(array_key_exists($name, $rawFilters)): ?> checked  <?php endif; ?>/>

                    <label for="author<?php echo e($i)?>"><?php echo e($author->name);?> (<?php echo e($author->count);?>)</label>
                </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="filter">
    <ul class="no-bullets">
        <?php foreach($this->filterLanguages as $i => $language):?>
            <?php if($language->count > 0):?>
            <li>
                <?php $name = "language" . e($i);?>
                <input type="checkbox"
                       name="<?php echo e($name);?>"
                       value="<?php echo e($language->name); ?>"
                        <?php if(array_key_exists($name, $rawFilters)): ?> checked  <?php endif; ?>/>

                <label for="language<?php echo e($i);?>"><?php echo e($language->name);?> (<?php echo e($language->count);?>)</label>
            </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    </div>

    <div class="filter">
    <ul class="no-bullets">
        <?php foreach($this->filterPublishers as $i => $publisher):?>
            <?php if($publisher->count > 0):?>
            <li>
                <?php $name = "publisher" . e($i);?>
                <input type="checkbox"
                       name="<?php echo e($name); ?>"
                       value="<?php echo e($publisher->name); ?>"
                        <?php if(array_key_exists($name, $rawFilters)): ?> checked  <?php endif; ?>/>
                <label for="publisher<?php echo e($i);?>"><?php echo e($publisher->name);?> (<?php echo e($publisher->count);?>)</label>
            </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    </div>

    <div class="filter">
    <ul class="no-bullets">
        <?php foreach ($this->filterGenres as $i => $genre):?>
            <?php if($genre->count > 0):?>
            <li>
                <?php $name = "genre" . e($i);?>
                <input type="checkbox"
                       name="<?php echo e($name); ?>"
                       value="<?php echo e($genre->name); ?>"
                        <?php if(array_key_exists($name, $rawFilters)): ?> checked  <?php endif; ?>/>
                <label for="genre<?php echo e($i);?>"><?php echo e($genre->name);?> (<?php echo e($genre->count);?>)</label>
            </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    </div>
</div>
</form>