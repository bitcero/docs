<ul>
<?php foreach($references as $ref): ?>
<li><a href="javascript:;" onclick="docsAjax.insertIntoEditor('[note:<?php echo $ref['id']; ?>]','<?php echo $rmc_config['editor_type']; ?>');"><?php echo $ref['title']; ?></a></li>
<?php endforeach; ?>
<?php $nav->display(false); ?>
</ul>