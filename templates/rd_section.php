<a name="rd_top"></a>
<?php include RMTemplate::get()->get_template('rd_header.php', 'module', 'docs'); ?>
<h1 class="title"><?php echo $res->getVar('title'); ?></h1>

<!-- Document Content -->
<?php foreach($sections as $sec): ?>
    <?php include RMTemplate::get()->get_template('rd_item.php','module','docs'); ?>
<?php endforeach; ?>
<!-- /End Document content -->

<!-- Navigation links -->
<div class="rd_nav_links">
    <?php if(isset($prev_section)): ?>
    <a href="<?php echo $prev_section['link']; ?>" class="left">&laquo; <?php echo $prev_section['title']; ?></a>
    <?php else: ?>
    <a href="<?php echo $resource['link']; ?>" class="left"><?php _e('Table of Contents','docs'); ?></a>
    <?php endif; ?>
    
    <?php if(isset($next_section)): ?>
    <a href="<?php echo $next_section['link']; ?>" class="right"><?php echo $next_section['title']; ?> &raquo;</a>
    <?php else: ?>
    <a href="<?php echo $resource['link']; ?>" class="right"><?php _e('Table of Contents','docs'); ?></a>
    <?php endif; ?>
    
    <?php if(isset($prev_section) && isset($next_section)): ?>
    <span class="rd_toc"><a href="<?php echo $resource['link']; ?>"><?php _e('Table of Contents','docs'); ?></a></span>
    <?php endif; ?>
</div>
<?php if(!$standalone): ?>
<div class="rd_section_data">
    <p class="left">
        <?php if(isset($pdf_book_url)): ?><a href="<?php echo $pdf_book_url; ?>"><?php _e('Create PDF Book','docs'); ?></a><br /><?php endif; ?>
        <?php if(isset($pdf_section_url)): ?><a href="<?php echo $pdf_section_url; ?>"><?php _e('Generate PDF','docs'); ?></a><br /><?php endif; ?>
        <?php if(isset($print_book_url)): ?><a href="<?php echo $print_book_url; ?>"><?php _e('Print Book','docs'); ?></a><br /><?php endif; ?>
        <?php if(isset($print_section_url)): ?><a href="<?php echo $print_section_url; ?>"><?php _e('Print Section','docs'); ?></a><br /><?php endif; ?>
        <?php if(isset($publish_url)): ?>
        <a href="<?php echo $publish_url; ?>"><?php _e('Create Document','docs'); ?></a>
        <?php endif; ?>
    </p>
    <p class="right">
        <?php echo sprintf(__('Published at %s.','docs'), '<strong>'.formatTimestamp($sections[0]['created'],'l').'</strong>'); ?><br />
        <?php echo sprintf(__('Modified by last time at %s.','docs'), '<strong>'.formatTimestamp($last_modification,'l').'</strong>'); ?><br />
        <?php echo sprintf(__('Edited by %s.','docs'), '<a href="'.XOOPS_URL.'/userinfo.php?uid='.$last_author['id'].'">'.$last_author['name'].'</a>'); ?><br />
        <?php echo sprintf(__("Read %s times.",'docs'), '<strong>'.$resource['reads'].'</strong>'); ?>
    </p>
</div>
<?php endif; ?>
<!-- /Navigation links -->

<!-- Notes and references -->
<?php include RMTemplate::get()->get_template('rd_notes_and_refs.php','module','docs'); ?>
<!-- /End Notes and references -->

<!-- Comments -->
<h3><?php _e('Comments','docs'); ?></h3>
<?php echo $xoopsTpl->fetch(RMCPATH."/templates/rmc_comments_display.html"); ?>
<?php echo $xoopsTpl->fetch(RMCPATH."/templates/rmc_comments_form.html"); ?>
<!-- End Comments -->
