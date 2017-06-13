<h1 class="cu-section-title"><?php _e('Content Review', 'docs'); ?></h1>

<div class="row">

    <div class="col-md-8">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $page->title; ?></h3>
            </div>
            <div class="panel-body detected-links">
                <?php echo preg_replace_callback( "/\[\[([^\[\]]+)\]\]/", function($m){
                    return '<strong class="link">' . $m[0] . '</strong>';
                }, $page->content ); ?>

            </div>
        </div>

    </div>

    <div class="col-md-4">

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><?php _e('Internal Links', 'docs'); ?></strong>
            </div>
            <div class="panel-body report">
                <?php if( empty( $reported ) ): ?>
                <span class="text-info">
                    <?php _e('Broken internal links was not found in this page.', 'docs'); ?>
                </span>
                <?php endif; ?>
                <?php if( !empty( $reported['docs'] ) ): ?>
                    <h4><?php _e('Missing documents', 'docs'); ?></h4>
                    <span class="help-block">
                        <?php _e('Internal links to following missing documents were detected:','docs'); ?>
                    </span>
                    <ul class="list-unstyled">
                        <?php foreach( $reported['docs'] as $doc ): ?>
                            <li><code><?php echo $doc; ?></code></li>
                        <?php endforeach; ?>
                    </ul>
                    <hr>
                <?php endif; ?>

                <?php if( !empty( $reported['pages'] ) ): ?>
                    <h4><?php _e('Missing pages', 'docs'); ?></h4>
                    <span class="help-block">
                        <?php _e('Internal links to following missing pages were detected:','docs'); ?>
                    </span>
                    <ul class="list-unstyled">
                        <?php foreach( $reported['pages'] as $page ): ?>
                        <li><code><?php echo $page; ?></code></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="panel-footer">
                <?php if( !empty( $reported ) ): ?>
                <h5><?php _e('What do you want to do?', 'docs'); ?></h5>
                <button type="button" class="btn btn-primary" onclick="window.location.href='sections.php?action=create-review&id=<?php echo $id; ?>&res=<?php echo $doc_id; ?>&return=<?php echo $return; ?>';"><?php _e('Create documents and pages', 'docs'); ?></button>
                <?php endif; ?>
                <button type="button" class="btn <?php echo empty( $resported ) ? 'btn-warning' : 'btn-default'; ?>" onclick="window.location.href='sections.php?<?php echo $return ? 'action=edit&sec=' . $id . '&id=' . $doc_id : 'id=' . $doc_id; ?>';"><?php _E('Continue','docs'); ?></button>
            </div>
        </div>

    </div>

</div>