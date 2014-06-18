<?php include RMTemplate::get()->get_template('rd_header.php', 'module', 'docs'); ?>
<h1 class="title"><?php echo $res->getVar('title'); ?></h1>
<p class="rd_res_description"><?php echo $res->getVar('description'); ?></p>

<h2><?php _e('Table of Contents','docs'); ?></h2>

<?php if(!empty($toc)): ?>
<ul id="rd-toc">
<?php foreach($toc as $sec): ?>
    <li><a href="<?php echo $sec['link']; ?>"><strong><?php echo $sec['number']; ?></strong>. <?php echo $sec['title']; ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>


