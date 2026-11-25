<h1>Users: </h1>

<table>
    <tr>
        <td>Name</td>
        <td>Email</td>
        <td>Role</td>
    </tr>
    <?php foreach($results as $result): ?>
    <tr>
        <td><?php echo e($result->user_name);?></td>
        <td><?php echo e($result->user_email);?></td>
        <td><?php echo e($result->user_role);?></td>
        <td><a href="/admin/users/edit?<?php echo http_build_query(['id' => $result->user_id]);?>"> Edit </a></td>
        <td>
            <form method="POST" action="/admin/users/delete">
                <input type="hidden" name="id" value="<?php echo e($result->user_id);?>"/>
                <input type="submit" value="Delete">
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . PAGINATION_PATH; ?>
