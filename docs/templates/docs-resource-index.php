<?php include RMTemplate::get()->get_template('docs-header.php', 'module', 'docs'); ?>

<div class="page-header">
    <h1 class="title"><?php echo $res->getVar('title'); ?></h1>
</div>

<?php include RMTemplate::get()->get_template( 'docs-document-info.php', 'module', 'docs' ); ?>

<!-- Table of Contents -->
<?php include RMTEmplate::get()->get_template('docs-resource-toc.php', 'module', 'docs'); ?>
<!-- /Table of Contents -->

<h3><?php _e('Comments','docs'); ?></h3>
<?php echo $xoopsTpl->fetch(RMCPATH."/templates/rmc-comments-display.html"); ?>
<?php echo $xoopsTpl->fetch(RMCPATH."/templates/rmc-comments-form.html"); ?>
<!-- End Comments -->

