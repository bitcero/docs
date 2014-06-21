<section id="section-<?php echo $sec['id']; ?>" class="doc-section<?php echo $sec['jump']==0 ? ' is-header' : ''; ?>">
    <header class="row">
        <div class="col-md-8 col-lg-9">
            <a name="<?php echo $sec['nameid']; ?>"></a>
            <h<?php echo $sec['jump']<=5 ? $sec['jump']+2 : 6; ?> class="section-title"><?php echo $sec['title']; ?></h<?php echo $sec['jump']<=5 ? $sec['jump']+1 : 6; ?>>
        </div>
        <div class="col-md-4 col-lg-3 text-right hidden-sm hidden-xs">
            <span class="rd_item_options">
                <?php if(!isset($not_show_top)): ?>&uarr; <a href="#rd_top"><?php _e('Top','docs'); ?></a><?php endif; ?>
                <?php if($sec['edit'] && isset($sec['editlink'])): ?>| <a href="<?php echo $sec['editlink']; ?>"><?php _e('Edit','docs'); ?></a><?php endif; ?>
            </span>
        </div>
    </header>

    <?php echo $sec['content']; ?>
</section>
