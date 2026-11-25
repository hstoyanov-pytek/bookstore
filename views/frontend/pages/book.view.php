<h1>Book: <?php echo e($result->book_title); ?></h1>
<img src="<?php echo e(PROJECT_ROOT);?>uploads/<?php echo e($result->book_cover_name);?>" alt="Book cover">
<p> Author: <?php echo e($result->book_author); ?></p>
<p> Publisher: <?php echo e($result->publisher); ?></p>
<p> Year published: <?php echo e($result->year_published); ?></p>
<p> Genres: <?php echo e($result->book_genres); ?></p>
<p> Language: <?php echo e($result->book_languages); ?></p>
<p> Available: <?php echo e($result->book_available? "True" : "False"); ?></p>
