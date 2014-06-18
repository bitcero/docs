<?php if(!$standalone): ?>
<div id="rd-header">
    <a href="<?php echo RDURL; ?>"><?php echo $xoopsModule->name(); ?></a> |
    <a href="<?php echo RDFunctions::make_link('explore', array('by'=>'recent')); ?>"><?php _e('Recent Documents','docs'); ?></a> |
    <a href="<?php echo RDFunctions::make_link('explore', array('by'=>'top')); ?>"><?php _e('Top Documents','docs'); ?></a>
    <div class="right">
        <form name="frmsearch" method="get" action="<?php echo RDFunctions::make_link('search'); ?>">
            <input type="text" name="keyword" size="20" />
            <input type="submit" value="<?php _e('Search','docs'); ?>" />
        </form>
    </div>
</div>
<?php endif; ?>