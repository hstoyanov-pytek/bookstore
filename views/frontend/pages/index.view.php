<ul>
<?php foreach($availableResults as $result): ?>
    <li>
        <a href="index.php?<?php echo http_build_query(['page' => 'book','book_id' => $result->id]); ?>"><?php echo e($result->title); ?></a>
    </li>
<?php endforeach; ?>
</ul>
<a href="index.php?<?php echo http_build_query(['page'=>'create'])?>">Create entry</a>
<table>
<?php foreach($results as $result):?>
    <tr>
        <td><?php echo e($result->title); ?></td>
        <td><a href="index.php?<?php echo http_build_query(['page' => 'edit', 'book_id' => $result->id])?>">Edit</a></td>
        <td>
            <form method="POST" action="index.php?<?php echo http_build_query(['page' => 'delete']); ?>">
                <input type="hidden" name="id" value="<?php echo e($result->id);?>"/>
                <input type="submit" value="Delete">
            </form>
        </td>
    </tr>
<?php endforeach; ?>
</table>
