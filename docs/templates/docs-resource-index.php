<?php $no_content = true; include RMTemplate::get()->get_template('docs-header.php', 'module', 'docs'); ?>

<?php if(!$standalone): ?>

    <div class="document-info">
        <?php include RMTemplate::get()->get_template( 'docs-document-info.php', 'module', 'docs' ); ?>
    </div>

    <div class="row">
        <div class="col-sm-6">

            <h3><?php _e('Quick Index','docs'); ?></h3>

            <div class="rd_quick_index">
                <ol>
                    <?php foreach($quick_index as $sec): ?>
                        <li>
                            <h4><a href="<?php echo $sec['link']; ?>"><?php echo $sec['title']; ?></a></h4>
                            <span class="help-block"><?php echo $sec['desc']; ?></span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>

        </div>
        <div class="col-sm-5 col-sm-offset-1">
            <!-- Table of Contents -->
            <?php include RMTEmplate::get()->get_template('docs-resource-toc.php', 'module', 'docs'); ?>
            <!-- /Table of Contents -->
        </div>
    </div>

    <h3><?php _e('Comments','docs'); ?></h3>
    <?php echo $xoopsTpl->fetch(RMCPATH."/templates/rmc-comments-display.html"); ?>
    <?php echo $xoopsTpl->fetch(RMCPATH."/templates/rmc-comments-form.html"); ?>
    <!-- End Comments -->

<?php else: ?>

    <div class="container standalone-container">
        <?php include RMTemplate::get()->get_template( 'docs-document-info.php', 'module', 'docs' ); ?>
        <hr>

        <div class="row">
            <div class="col-sm-6">

                <h3><?php _e('Quick Index','docs'); ?></h3>

                <div class="rd_quick_index">
                    <ol>
                        <?php foreach($quick_index as $sec): ?>
                            <li>
                                <h4><a href="<?php echo $sec['link']; ?>"><?php echo $sec['title']; ?></a></h4>
                                <span class="help-block"><?php echo $sec['desc']; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>

            </div>
            <div class="col-sm-5 col-sm-offset-1">
                <!-- Table of Contents -->
                <?php include RMTEmplate::get()->get_template('docs-resource-toc.php', 'module', 'docs'); ?>
                <!-- /Table of Contents -->
            </div>
        </div>

        <hr>

        <h3><?php _e('Comments','docs'); ?></h3>
        <?php echo $xoopsTpl->fetch(RMCPATH."/templates/rmc-comments-display.html"); ?>
        <?php echo $xoopsTpl->fetch(RMCPATH."/templates/rmc-comments-form.html"); ?>
        <!-- End Comments -->

    </div>

<?php endif; ?>

