<div class="docs-document">
    <?php if($standalone): ?>
    <div id="docs-resource-index">
        <ul class="list-unstyled docs-index">
        <?php $num = 1; foreach( $index as $section ): ?>

            <li style="padding-left: <?php echo $section['jump'] * 15; ?>px;" data-section="<?php echo $section['id']; ?>">
                <a href="<?php echo $section['link']; ?>" data-level="<?php echo $section['number']; ?>">
                    <strong><?php echo $section['number']; ?>.</strong>
                    <?php if($section['jump']==0): ?>
                        <strong><?php echo $section['title']; ?></strong>
                    <?php else: ?>
                        <?php echo $section['title']; ?>
                    <?php endif; ?>
                </a>
            </li>

        <?php $num++; endforeach; ?>
        </ul>

    </div>
    <?php endif; ?>

    <div id="docs-resource-content">

        <?php if( $xoopsModuleConfig['ajax'] ): ?>

            &nbsp;

        <?php else: ?>
            <?php include RMTemplate::get()->get_template('docs-header.php', 'module', 'docs'); ?>
            <div class="page-header">
                <h1><?php echo $res->getVar('title'); ?></h1>
            </div>

            <!-- Document Content -->
            <article>
                <?php foreach($sections as $sec): ?>
                    <?php include RMTemplate::get()->get_template('docs-item-section.php','module','docs'); ?>
                <?php endforeach; ?>

                <div class="clearfix"></div>

                <!-- /End Document content -->
                <hr>
                <!-- Navigation links -->
                <section class="document-navigation">
                    <ul class="pager">
                        <li class="previous">
                            <?php if(isset($prev_section)): ?>
                                <a href="<?php echo $prev_section['link']; ?>" title="<?php echo $prev_section['title']; ?>">&laquo; <?php _e('Previous', 'docs') ?></a>
                            <?php else: ?>
                                <a href="<?php echo $resource['link']; ?>"><?php _e('Table of Contents','docs'); ?></a>
                            <?php endif; ?>
                        </li>
                        <li class="next">
                            <?php if(isset($next_section)): ?>
                                <a href="<?php echo $next_section['link']; ?>" title="<?php echo $next_section['title']; ?>"><?php _e('Next', 'docs'); ?> &raquo;</a>
                            <?php else: ?>
                                <a href="<?php echo $resource['link']; ?>"><?php _e('Table of Contents','docs'); ?></a>
                            <?php endif; ?>
                        </li>
                    </ul>
                    <?php if(isset($prev_section) && isset($next_section)): ?>
                        <ul class="pager">
                            <li>
                                <a href="<?php echo $resource['link']; ?>"><?php _e('Table of Contents','docs'); ?></a>
                            </li>
                        </ul>
                    <?php endif; ?>

                </section>
                <hr>
                <?php if(!$standalone): ?>
                    <section class="rd-section-data row">
                        <div class="col-sm-6">
                            <?php if(isset($pdf_book_url)): ?><a href="<?php echo $pdf_book_url; ?>"><?php _e('Create PDF Book','docs'); ?></a><br /><?php endif; ?>
                            <?php if(isset($pdf_section_url)): ?><a href="<?php echo $pdf_section_url; ?>"><?php _e('Generate PDF','docs'); ?></a><br /><?php endif; ?>
                            <?php if(isset($print_book_url)): ?><a href="<?php echo $print_book_url; ?>"><?php _e('Print Book','docs'); ?></a><br /><?php endif; ?>
                            <?php if(isset($print_section_url)): ?><a href="<?php echo $print_section_url; ?>"><?php _e('Print Section','docs'); ?></a><br /><?php endif; ?>
                            <?php if(isset($publish_url)): ?>
                                <a href="<?php echo $publish_url; ?>"><?php _e('Create Document','docs'); ?></a>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-6 text-right">
                            <small class="help-block" style="margin: 0;">
                                <?php echo sprintf(__('Modified by last time at %s.','docs'), '<strong>'.RMTimeFormatter::get()->format( $last_modification, __( '%T% %d%, %Y%', 'docs' ) ).'</strong>'); ?><br />
                                <?php echo sprintf(__('Edited by %s.','docs'), '<a href="'.XOOPS_URL.'/userinfo.php?uid='.$last_author['id'].'">'.$last_author['name'].'</a>'); ?><br />
                                <?php echo sprintf(__("Read %s times.",'docs'), '<strong>'.$resource['reads'].'</strong>'); ?>
                            </small>
                        </div>
                    </section>
                    <hr>
                <?php endif; ?>
                <!-- /Navigation links -->

                <footer>
                    <!-- Notes and references -->
                    <?php include RMTemplate::get()->get_template('docs-notes-references.php','module','docs'); ?>
                    <!-- /End Notes and references -->
                </footer>
            </article>
        <?php endif; ?>
        <!--{xo-logger-output}-->
    </div>
</div>

