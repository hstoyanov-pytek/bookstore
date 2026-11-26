<?php
if(!empty($totalPages) && $page > $totalPages)
{
    $page = $totalPages;
    //regex built with the help of https://www.phpliveregex.com/#tab-preg-replace
    $uri = $_SERVER['REQUEST_URI'];
    $uri = preg_replace('/page=[0-9]+/', "page={$page}", $uri);

    header("Location: ". $uri);
}
?>

<?php if($page > 1): ?>
    <a href="<?php echo $previousHref; ?>"> Previous </a>
<?php endif; ?>
<?php if($page > 0 || $page < $totalPages): ?>
    <form method="GET" action="">
        <label for="page">Page:</label>
        <input type="number" name="page" id="page" value="<?php echo e($page);?>">

        <input type="submit" value="Go">
    </form>
<?php endif; ?>
<?php if($page < $totalPages): ?>
    <a href="<?php echo $nextHref; ?>"> Next </a>
<?php endif; ?>
