<?php include RMTemplate::get()->get_template('rd_header.php', 'module', 'docs'); ?>
<?php $nav->display(true); ?>
<?php if(!isset($by)): ?>
<h1 class="rd_title"><?php echo sprintf(__('Results for "%s"','docs'), $keyword); ?></h1>
<?php elseif($by=='created'): ?>
<h1 class="rd_title">Recent Documents</h1>
<?php elseif($by=='reads'): ?>
<h1 class="rd_title">Top Documents</h1>
<?php endif; ?>

<?php foreach($resources as $res): ?>
<div class="rd_search_item">
    <h4><a href="<?php echo $res['link']; ?>"><?php echo $res['title']; ?></a></h4>
    <?php echo $res['desc']; ?>
    <span class="rd_info">
    <?php echo sprintf(__('%s by %s.', 'docs'), RMTimeFormatter::get()->format($res['created'], __('Created on %M% %d%, %Y%.','docs')), '<a href="'.XOOPS_URL.'/userinfo.php?uid='.$res['owner'].'">'.$res['uname'].'</a>'); ?>
    <?php echo sprintf(__('Viewed %s times.','docs'), '<strong>'.$res['reads'].'</strong>'); ?>
    </span>
</div>
<?php endforeach; ?>

<?php $nav->display(true); ?>