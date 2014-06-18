<a name="<?php echo $sec['nameid']; ?>"></a>
<span class="rd_item_options">
    <?php if(!isset($not_show_top)): ?>&uarr; <a href="#rd_top"><?php _e('Top','docs'); ?></a><?php endif; ?>
    <?php if($sec['edit'] && isset($sec['editlink'])): ?>| <a href="<?php echo $sec['editlink']; ?>"><?php _e('Edit','docs'); ?></a><?php endif; ?>
</span>
<h<?php echo $sec['jump']<=5 ? $sec['jump']+1 : 6; ?>><?php echo $sec['number']; ?>. <?php echo $sec['title']; ?></h<?php echo $sec['jump']<=5 ? $sec['jump']+1 : 6; ?>>
<?php echo $sec['content']; ?>