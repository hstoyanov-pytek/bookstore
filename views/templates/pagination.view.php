<?php if($page > 1): ?>
    <a href="<?php echo $previousHref; ?>"> Previous </a>
<?php endif; ?>
<?php if($page < $totalPages): ?>
    <a href="<?php echo $nextHref; ?>"> Next </a>
<?php endif; ?>
