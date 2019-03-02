<?php include RMTemplate::get()->get_template('docs-header.php', 'module', 'docs'); ?>
<a name="rd_top"></a>

<div class="page-header">
    <h1 class="title"><?php echo $res->getVar('title'); ?></h1>
</div>

<article>

    <!-- Table of Contents -->
    <?php include RMTEmplate::get()->get_template('docs-resource-toc.php', 'module', 'docs'); ?>
    <!-- /Table of Contents -->

    <!-- Document Content -->
    <?php foreach ($toc as $sec): ?>
        <?php include RMTemplate::get()->get_template('docs-item-section.php', 'module', 'docs'); ?>
    <?php endforeach; ?>
    <!-- /End Document content -->

    <div class="clearfix"></div>

    <?php if (!$standalone): ?>
    <hr>
    <section class="rd-section-data row">
        <div class="col-sm-6">
            <small>
                <?php if (isset($pdf_book_url) && $pdf_book_url!=''): ?><a href="<?php echo $pdf_book_url; ?>"><?php _e('Create PDF Book', 'docs'); ?></a><br /><?php endif; ?>
                <?php if (isset($print_book_url) && $print_book_url!=''): ?><a href="<?php echo $print_book_url; ?>"><?php _e('Print Book', 'docs'); ?></a><br /><?php endif; ?>
                <?php if (isset($publish_url)): ?>
                <a href="<?php echo $publish_url; ?>"><?php _e('Create Document', 'docs'); ?></a>
                <?php endif; ?>
            </small>
        </div>
        <div class="col-sm-6 text-right">
            <small class="help-block" style="margin: 0;">
                <?php echo sprintf(__('Published at %s.', 'docs'), '<strong>'.RMTimeFormatter::get()->format($toc[0]['created'], __('%T% %d%, %Y%', 'docs')).'</strong>'); ?><br />
                <?php echo sprintf(__('Modified by last time at %s.', 'docs'), '<strong>'.RMTimeFormatter::get()->format($res->getVar('modified'), __('%T% %d%, %Y%', 'docs')).'</strong>'); ?><br />
                <?php echo sprintf(__('Edited by %s.', 'docs'), '<a href="'.XOOPS_URL.'/userinfo.php?uid='.$last_author['id'].'">'.$last_author['name'].'</a>'); ?><br />
                <?php echo sprintf(__("Read %s times.", 'docs'), '<strong>'.$res->getVar('reads').'</strong>'); ?>
            </small>
        </div>
    </section>
    <?php endif; ?>

    <!-- Notes and references -->
    <hr>
    <?php include RMTemplate::get()->get_template('docs-notes-references.php', 'module', 'docs'); ?>
    <!-- /End Notes and references -->
</article>
<hr>
<!-- Comments -->
<h4><?php _e('Comments', 'docs'); ?></h4>
<?php echo $xoopsTpl->fetch(RMCPATH."/templates/rmc-comments-display.html"); ?>
<hr>
<?php echo $xoopsTpl->fetch(RMCPATH."/templates/rmc-comments-form.html"); ?>
<!-- End Comments -->