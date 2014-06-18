<ul>
<?php foreach($figures as $fig): ?>
<li><a href="javascript:;" onclick="docsAjax.insertIntoEditor('[note:<?php echo $fig['id']; ?>]','<?php echo $rmc_config['editor_type']; ?>');"><?php echo $fig['desc']; ?></a></li>
<?php endforeach; ?>
<?php $nav->display(false); ?>
</ul>