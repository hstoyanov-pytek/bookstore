<h3>Editing genre: <?php echo e($genre);?></h3>


<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Language</th>
        <th>Publisher</th>
        <th>Year published</th>
    </tr>
    <?php foreach ($results as $result): ?>
    <tr>
        <td><img src="../uploads/<?php echo e($result->book_cover_name);?>"></td>
        <td><?php echo e($result->book_id); ?></td>
        <td><?php echo e($result->book_title); ?></td>
        <td><?php echo e($result->book_author); ?></td>
        <td><?php echo e($result->book_languages); ?></td>
        <td><?php echo e($result->publisher); ?></td>
        <td><?php echo e($result->year_published); ?></td>
        <td><a href="/<?php echo e($_SESSION['role']); ?>/edit?<?php echo http_build_query(['book_id' => $result->book_id])?>">Edit</a></td>
        <td>
            <form method="POST" action="/<?php echo e($_SESSION['role']); ?>/delete?<?php echo http_build_query(['page' => 'delete']); ?>">
                <input type="hidden" name="id" value="<?php echo e($result->book_id);?>"/>
                <input type="hidden" name="page" value="<?php echo e("authors");?>"/>
                <input type="hidden" name="genre" value="<?php echo e($result->book_genres);?>"/>
                <input type="submit" value="<?php echo $result->book_available ? e("Disable") : e("Enable") ?>">
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . PAGINATION_PATH; ?>