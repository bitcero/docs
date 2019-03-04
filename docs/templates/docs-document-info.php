<div class="row">

    <div class="col-sm-6 col-md-4">

        <?php if ('' != $res->image): ?>
            <img src="<?php echo RMImageResizer::resize($res->image, ['width' => 600, 'height' => 800, 'quality' >= 100 ])->url; ?>" class="img-responsive book-cover">
        <?php endif; ?>

    </div>

    <div class="col-sm-6 col-md-8">
        <h1 class="book-title"><?php echo $res->title; ?></h1>
        <p class="owner"><?php echo sprintf(__('by %s', 'docs'), '<a href="' . XOOPS_URL . '/userinfo.php?uid=' . $res->owner . '">' . $owner->name . '</a>'); ?></p>

        <?php if ($editors): ?>
            <p class="contributors">
                <?php _e('Contributors:', 'docs'); ?>
                <?php $total = count($editors); $i = 1; foreach ($editors as $uid => $editor): ?>
                    <?php echo $i > 1 && $i <= $total ? __('and', 'docs') : ''; ?>
                    <a href="<?php echo XOOPS_URL; ?>/userinfo.php?uid=<?php echo $uid; ?>"><?php echo $editor; ?></a><?php echo $i < $total - 1 ? ',' : ''; ?>
                    <?php $i++; endforeach; ?>
            </p>
        <?php endif; ?>

        <div class="tagline"><?php echo $res->description; ?></div>

        <hr>

        <?php if ($toc): ?>
            <a href="<?php echo $toc[0]['link']; ?>" class="btn btn-primary btn-lg"><?php _e('Read Document', 'docs'); ?></a>
        <?php endif; ?>

        <?php if ($xoopsUser && $xoopsUser->uid() == $res->owner): ?>
            <a href="<?php echo RDURL; ?>/edit-book/<?php echo $res->id(); ?>/" class="btn btn-primary btn-lg" title="<?php _e('Edit Document', 'docs'); ?>"><span class="fa fa-gear"></span></a>
        <?php endif; ?>

        <div class="meta">
            <div class="column">
                <label><?php _e('Created:', 'docs'); ?></label>
                <?php echo RMTimeFormatter::get()->format($res->created, '%d% %T%, %Y%'); ?>
            </div>
            <div class="column">
                <label><?php _e('Updated:', 'docs'); ?></label>
                <?php echo RMTimeFormatter::get()->format($res->modified, '%d% %T%, %Y%'); ?>
            </div>
            <div class="column">
                <label><?php _e('Chapters:', 'docs'); ?></label>
                <?php echo count($quick_index); ?>
            </div>
            <div class="column">
                <label><?php _e('License:', 'docs'); ?></label>
                <?php echo $res->license; ?>
            </div>
        </div>

    </div>

</div>
