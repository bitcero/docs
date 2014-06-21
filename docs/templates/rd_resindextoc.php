<?php include RMTemplate::get()->get_template('rd_header.php', 'module', 'docs'); ?>
<div class="page-header">
    <h1 class="title"><?php echo $res->getVar('title'); ?></h1>
</div>

<?php echo $res->getVar('description'); ?>

<?php if(!empty($toc)): ?>
    <strong>Contenido:</strong>
<ul id="rd-toc">
<?php foreach($toc as $sec): ?>
    <li><a href="<?php echo $sec['link']; ?>"><strong><?php echo $sec['number']; ?></strong>. <?php echo $sec['title']; ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>


