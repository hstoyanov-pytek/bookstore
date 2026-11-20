<h1>Login to Pytek bookstore</h1>

<?php if(!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error):?>
            <li>
                <p><?php echo e($error);?></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="/login">

    <label for="email">Email</label>
    <input type="text" name="email" value="<?php echo e(@$_POST['email']);?>">

    <label for="password">Password</label>
    <input type="password" name="password">

    <br>
    <input type="submit" value="Login!">
</form>