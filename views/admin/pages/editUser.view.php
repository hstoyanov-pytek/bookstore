<h1>Editing user: </h1>

<form method="POST" action="/admin/users/edit">
    <label for="name">Name</label>
    <input type="text" name="user_name" id="name" value="<?php echo e($user->user_name);?>"/>
    <input type="hidden" name="user_id" id="user_id" value="<?php echo e($user->user_id);?>"/>

    <select name="user_role">
        <?php foreach($roles as $role):?>
            <option value="<?php echo e($role->name);?>" <?php if($role->name === $user->user_role) echo "selected"; ?>><?php echo e($role->name);?></option>
        <?php endforeach; ?>
    </select>

    <br><br>
    <input type="submit" value="Submit">
</form>