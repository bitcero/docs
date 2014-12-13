<?php if( !isset( $standalone ) || !$standalone): ?>
<div id="rd-header" class="row">
    <div class="col-md-8 col-lg-9">
        <ul class="list-inline">
            <li>
                <a href="<?php echo RDURL; ?>"><?php echo $xoopsModule->name(); ?></a>
            </li>
            <li>
                <a href="<?php echo RDFunctions::make_link('explore', array('by'=>'recent')); ?>"><?php _e('Recent Documents','docs'); ?></a>
            </li>
            <li>
                <a href="<?php echo RDFunctions::make_link('explore', array('by'=>'top')); ?>"><?php _e('Top Documents','docs'); ?></a>
            </li>
        </ul>
    </div>
    <div class="col-md-4 col-lg-3">
        <form name="frmsearch" method="get" action="<?php echo RDFunctions::make_link('search'); ?>">
            <div class="input-group input-group-sm">
                <input type="text" name="keyword" size="20" class="form-control" />
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-default"><?php _e('Search','docs'); ?></button>
                </span>
            </div>
        </form>
    </div>
</div>
<?php else: ?>

    <div class="docs-content-header">
            <a href="#" class="btn pull-left toggle-summary">
                <span class="fa fa-align-justify"></span>
            </a>
            <a href="<?php echo RDURL; ?>" class="btn pull-left" title="<?php echo $xoopsModule->name(); ?>">
                <span class="fa fa-th"></span>
            </a>
            <a href="<?php echo $res->permalink(); ?>" class="btn pull-left" title="<?php _e('Back to document','docs'); ?>">
                <span class="fa fa-book"></span>
            </a>
    </div>

<?php endif; ?>