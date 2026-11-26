<?php if(!empty($errors)): ?>
<ul>
    <?php foreach ($errors as $error):?>
     <li>
         <p><?php echo e($error);?></p>
     </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
<h1>Register to the Pytek bookstore</h1>

<form method="POST" action="/register">
    <label for="email">Email</label>
    <input type="text" name="email" value="<?php echo e(@$_POST['email']);?>">

    <label for="name">Name</label>
    <input type="text" name="name" value="<?php echo e(@$_POST['name']);?>">

    <label for="password">Password</label>
    <input type="password" name="password">

    <label for="TOS">By checking this you agree to the terms and conditions</label>
    <input type="checkbox" name="TOS"/>

    <br><br>
    <input type="submit" value="Register!">
</form>