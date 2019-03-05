<?php include RMTemplate::get()->get_template('docs-header.php', 'module', 'docs'); ?>

<?php $nav->display(true); ?>

<?php if (!isset($by)): ?>
<h2 class="rd_title"><?php echo sprintf(__('Results for "%s"', 'docs'), $keyword); ?></h2>
<?php elseif ('created' === $by): ?>
<h2 class="rd_title">Recent Documents</h2>
<?php elseif ('reads' === $by): ?>
<h2 class="rd_title">Top Documents</h2>
<?php endif; ?>
<hr>
<?php
foreach ($resources as $res):
?>
<div class="panel panel-default document-search-item">
    <div class="panel-body">
        <?php if ('' != $res['image']): ?>
        <img src="<?php echo RMImageResizer::resize($res['image'], ['width' => 150, 'height' => 150])->url; ?>" class="thumbnail pull-left">
        <?php endif; ?>
        <h4><a href="<?php echo $res['link']; ?>"><?php echo $res['title']; ?></a></h4>
        <p><?php echo $res['desc']; ?></p>
        <small class="help-block">
        <?php echo sprintf(__('%s by %s.', 'docs'), RMTimeFormatter::get()->format($res['created'], __('Created on %M% %d%, %Y%.', 'docs')), '<a href="' . XOOPS_URL . '/userinfo.php?uid=' . $res['owner'] . '">' . $res['uname'] . '</a>'); ?>
        <?php echo sprintf(__('Viewed %s times.', 'docs'), '<strong>' . $res['reads'] . '</strong>'); ?>
        </small>
    </div>
</div>
<?php endforeach; ?>

<?php $nav->display(true); ?>
