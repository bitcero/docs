<a name="rd_top"></a>
<!-- Table of Contents -->
<?php include RMTEmplate::get()->get_template('rd_resindextoc.php', 'module', 'docs'); ?>
<!-- /Table of Contents -->

<!-- Document Content -->
<?php foreach($toc as $sec): ?>
    <?php include RMTemplate::get()->get_template('rd_item.php','module','docs'); ?>
<?php endforeach; ?>
<!-- /End Document content -->

<div class="rd_nav_links">&nbsp;</div>
<div class="rd_section_data">
    <p class="left">
        <?php if(isset($pdf_book_url) && $pdf_book_url!=''): ?><a href="<?php echo $pdf_book_url; ?>"><?php _e('Create PDF Book','docs'); ?></a><br /><?php endif; ?>
        <?php if(isset($print_book_url) && $print_book_url!=''): ?><a href="<?php echo $print_book_url; ?>"><?php _e('Print Book','docs'); ?></a><br /><?php endif; ?>
        <?php if(isset($publish_url)): ?>
        <a href="<?php echo $publish_url; ?>"><?php _e('Create Document','docs'); ?></a>
        <?php endif; ?>
    </p>
    <p class="right">
        <?php echo sprintf(__('Published at %s.','docs'), '<strong>'.formatTimestamp($toc[0]['created'],'l').'</strong>'); ?><br />
        <?php echo sprintf(__('Modified by last time at %s.','docs'), '<strong>'.formatTimestamp($res->getVar('modified'),'l').'</strong>'); ?><br />
        <?php echo sprintf(__('Edited by %s.','docs'), '<a href="'.XOOPS_URL.'/userinfo.php?uid='.$last_author['id'].'">'.$last_author['name'].'</a>'); ?><br />
        <?php echo sprintf(__("Read %s times.",'docs'), '<strong>'.$res->getVar('reads').'</strong>'); ?>
    </p>
</div>

<!-- Notes and references -->
<?php include RMTemplate::get()->get_template('rd_notes_and_refs.php','module','docs'); ?>
<!-- /End Notes and references -->

<!-- Comments -->
<h3><?php _e('Comments','docs'); ?></h3>
<?php echo $xoopsTpl->fetch(RMCPATH."/templates/rmc_comments_display.html"); ?>
<?php echo $xoopsTpl->fetch(RMCPATH."/templates/rmc_comments_form.html"); ?>
<!-- End Comments -->