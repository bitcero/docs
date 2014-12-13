<div class="jumbotron docs-document-info">
    <div class="row">

        <div class="col-md-5">
            <?php if( $res->image != '' ): ?>
            <img src="<?php echo RMImageResizer::resize( $res->image, array('width' => 600, 'height' => 800, 'quality' >= 100 ) )->url; ?>" class="img-thumbnail">
            <?php endif; ?>
        </div>

        <div class="col-md-7">
            <h1><?php echo $res->title; ?></h1>
            <p class="owner"><?php echo sprintf( __('by %s', 'docs'), '<a href="' . XOOPS_URL . '/userinfo.php?uid=' . $res->owner . '">' . $owner->name . '</a>' ); ?></p>
            <?php if( $editors ): ?>
            <p class="contributors">
                <?php _e('Contributors:', 'docs'); ?>
                <?php $total = count( $editors ); $i = 1; foreach ( $editors as $uid => $editor ): ?>
                    <?php echo $i == $total ? __('and', 'docs') : ''; ?>
                    <a href="<?php echo XOOPS_URL; ?>/userinfo.php?uid=<?php echo $uid; ?>"><?php echo $editor; ?></a><?php echo $i < $total - 1 ? ',' : ''; ?>
                <?php $i++;endforeach; ?>
            </p>
            <?php endif; ?>
            <p class="tagline"><?php echo $res->tagline; ?></p>
            <hr>
            <?php if( $toc ): ?>
            <a href="<?php echo $toc[0]['link']; ?>" class="btn btn-default btn-lg"><?php _e('Read Document', 'docs'); ?></a>
            <?php endif; ?>
        </div>

    </div>
</div>