<?php include RMTemplate::get()->get_template('docs-document-info.php', 'module', 'docs'); ?>

<hr>

<h3><?php _e('Quick Index', 'docs'); ?></h3>

<div class="rd_quick_index">
	<ol>
	<?php foreach ($qindex_sections as $sec): ?>
		<li>
            <h4><a href="<?php echo $sec['link']; ?>"><?php echo $sec['title']; ?></a></h4>
			<span class="help-block"><?php echo $sec['desc']; ?></span>
		</li>
	<?php endforeach; ?>
	</ol>
</div>

<hr>

<!-- Comments -->
<h3><?php _e('Comments', 'docs'); ?></h3>
<?php echo $xoopsTpl->fetch(RMCPATH."/templates/rmc-comments-display.html"); ?>
<?php echo $xoopsTpl->fetch(RMCPATH."/templates/rmc-comments-form.html"); ?>
<!-- End Comments -->