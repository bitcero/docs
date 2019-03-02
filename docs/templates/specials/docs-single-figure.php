<div class="docs-figure <?php echo $fig->getVar('align')=='left' ? 'left' : ($fig->align == 'right' ? 'right' : 'center'); ?>" style="width: <?php echo $fig->getVar('size') . 'px'; ?>">
    <div class="thumbnail">
        <?php if ($fig->type == 'image'):?>
            <?php
            $image = new RMImage();
            $image->load_from_params($fig->getVar('content', 'e'));
            ?>
            <img src="<?php echo $image->get_by_size($fig->size); ?>" title="<?php echo $fig->title; ?>">
        <?php else: ?>
            <?php echo $fig->content; ?>
        <?php endif; ?>
        <div class="caption<?php echo $fig->type == 'content' ? ' caption-content' : ''; ?>">
            <?php if ($fig->type == 'image'):?>
                <?php echo $fig->getVar('desc'); ?>
            <?php else: ?>
                <strong><?php echo $fig->title; ?></strong>
            <?php endif; ?>
        </div>
    </div>
</div>