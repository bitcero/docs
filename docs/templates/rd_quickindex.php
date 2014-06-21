<h1><?php echo $res->getVar('title'); ?></h1>
<h2><?php _e('Quick Index','docs'); ?></h2>

<div class="rd_quick_index">
	<ol>
	<?php foreach($qindex_sections as $sec): ?>
		<li>
            <h3><a href="<?php echo $sec['link']; ?>"><?php echo $sec['title']; ?></a></h3>
			<span class="description"><?php echo $sec['desc']; ?></span>
		</li>
	<?php endforeach; ?>
	</ol>
</div>
