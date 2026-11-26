<h3>Editing language: <?php echo e($language);?></h3>


<table>
    <tr>
        <th>Cover</th>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Genre</th>
        <th>Publisher</th>
        <th>Year published</th>
    </tr>
    <?php foreach ($results as $result): ?>
    <tr>
        <td><img src="<?php echo e(PROJECT_ROOT)?>uploads/<?php echo e($result->book_cover_name);?>"></td>
        <td><?php echo e($result->book_id); ?></td>
        <td><?php echo e($result->book_title); ?></td>
        <td><?php echo e($result->book_author); ?></td>
        <td><?php echo e($result->book_genres); ?></td>
        <td><?php echo e($result->publisher); ?></td>
        <td><?php echo e($result->year_published); ?></td>
        <td><a href="/admin/edit?<?php echo http_build_query(['book_id' => $result->book_id, 'nav' => $slug])?>">Edit</a></td>
        <?php if($_SESSION['role'] === "admin"):?>
        <td>
            <form method="POST" action="/admin/delete?<?php echo http_build_query(['page' => 'delete']); ?>">
                <input type="hidden" name="id" value="<?php echo e($result->book_id);?>"/>
                <input type="hidden" name="page" value="<?php echo e($slug);?>"/>
                <input type="hidden" name="language" value="<?php echo e($result->book_languages);?>"/>
                <input type="submit" value="<?php echo $result->book_available ? e("Disable") : e("Enable") ?>">
            </form>
        </td>
        <?php endif; ?>
    </tr>
    <?php endforeach; ?>
</table>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . PAGINATION_PATH; ?>
