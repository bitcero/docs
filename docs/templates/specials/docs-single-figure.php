<div class="docs-figure <?php echo 'left' == $fig->getVar('align') ? 'left' : ('right' == $fig->align ? 'right' : 'center'); ?>" style="width: <?php echo $fig->getVar('size') . 'px'; ?>">
    <div class="thumbnail">
        <?php if ('image' == $fig->type):?>
            <?php
            $image = new RMImage();
            $image->load_from_params($fig->getVar('content', 'e'));
            ?>
            <img src="<?php echo $image->get_by_size($fig->size); ?>" title="<?php echo $fig->title; ?>">
        <?php else: ?>
            <?php echo $fig->content; ?>
        <?php endif; ?>
        <div class="caption<?php echo 'content' == $fig->type ? ' caption-content' : ''; ?>">
            <?php if ('image' == $fig->type):?>
                <?php echo $fig->getVar('desc'); ?>
            <?php else: ?>
                <strong><?php echo $fig->title; ?></strong>
            <?php endif; ?>
        </div>
    </div>
</div>