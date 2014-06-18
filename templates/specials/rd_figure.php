<div<?php if($fig->getVar('attrs')!=''): echo " ".$fig->getVar('attrs'); endif; ?>>
    <?php if($fig->getVar('title')!=''): ?><div class="odd rd_figure_title"><?php echo $fig->getVar('title'); ?></div><?php endif; ?>
    <div class="rd_figure_content"><?php echo $fig->getVar('content'); ?></div>
    <div class="odd rd_figure_foot"><?php echo $fig->getVar('desc'); ?></div>
</div>