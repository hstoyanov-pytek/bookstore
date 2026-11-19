<ul>
<?php foreach($availableResults as $result): ?>
    <li>
        <a href="/book?<?php echo http_build_query(['book_id' => $result->id]); ?>"><?php echo e($result->title); ?></a>
    </li>
<?php endforeach; ?>
</ul>
<a href="/create">Create entry</a>
<table>
    <tr>
        <td>Book Cover</td>
        <td>Book ID</td>
        <td>Book Title</td>
        <td>Author</td>
        <td>Genre</td>
        <td>Language</td>
        <td>Publisher</td>
        <td>Year Published</td>
    </tr>
<?php foreach($results as $result):?>
    <tr>
        <td><img src="uploads/<?php echo e($result->book_cover_name);?>" alt="<?php echo e($result->book_title); ?>book cover"></td>
        <td><?php echo e($result->book_id); ?></td>
        <td><?php echo e($result->book_title); ?></td>
        <td><?php echo e($result->book_author); ?></td>
        <td><?php echo e($result->book_genres); ?></td>
        <td><?php echo e($result->book_languages); ?></td>
        <td><?php echo e($result->publisher); ?></td>
        <td><?php echo e($result->year_published); ?></td>
        <td><a href="/edit?<?php echo http_build_query(['book_id' => $result->book_id])?>">Edit</a></td>
        <td>
            <form method="POST" action="/delete?<?php echo http_build_query(['page' => 'delete']); ?>">
                <input type="hidden" name="id" value="<?php echo e($result->book_id);?>"/>
                <input type="submit" value="<?php echo $result->book_available ? e("Disable") : e("Enable") ?>">
            </form>
        </td>
    </tr>
<?php endforeach; ?>
</table>
