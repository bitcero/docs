<!-- Document Content -->
<article class="docs-content-article">
    <?php include RMTemplate::get()->get_template('docs-header.php', 'module', 'docs'); ?>
    <div class="docs-content-wrapper">
        <div class="docs-content-inner">
            <section id="section-<?php echo $section->id(); ?>" class="doc-section is-header">
                <header>
                    <a name="<?php echo $section->nameid; ?>"></a>
                    <?php /*h<?php echo $level <= 6 ? $level : 6; ?> class="section-title"><?php echo $number; ?> <?php echo $section->title; ?></h<?php echo $level <= 6 ? $level : 6; ?>*/?>
                    <h1 class="section-title">
                        <?php //echo $number; ?>
                        <?php echo $section->title; ?>
                        <?php if($isEditor): ?>
                        <a rel="edit" href="<?php echo XOOPS_URL; ?>/modules/docs/admin/sections.php?action=edit&amp;sec=<?php echo $section->id_sec; ?>&amp;id=<?php echo $res->id(); ?>">
                            <small>[ <?php _e('Edit', 'docs'); ?> ]</small>
                        </a>
                        <?php endif; ?>
                    </h1>
                </header>

                <?php echo $section->content; ?>
            </section>

            <?php if($subSections): ?>
                <?php foreach($subSections as $sub): ?>
                    <section id="section-<?php echo $sub['id_sec']; ?>" class="doc-section is-header">
                        <header>
                            <a name="<?php echo $sub['nameid']; ?>"></a>
                            <?php /*h<?php echo $level <= 6 ? $level : 6; ?> class="section-title"><?php echo $number; ?> <?php echo $sub->title; ?></h<?php echo $level <= 6 ? $level : 6; ?>*/?>
                            <h<?php echo $sub['level']; ?> class="section-title">
                                <?php echo $sub['title']; ?>
                                <?php if($isEditor): ?>
                                    <a rel="edit" href="<?php echo XOOPS_URL; ?>/modules/docs/admin/sections.php?action=edit&amp;sec=<?php echo $sub['id_sec']; ?>&amp;id=<?php echo $res->id(); ?>">
                                        <small>[ <?php _e('Edit', 'docs'); ?>] </small>
                                    </a>
                                <?php endif; ?>
                            </h<?php echo $sub['level']; ?>>
                        </header>

                        <?php echo $sub['content']; ?>
                    </section>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="clearfix"></div>

            <!-- /End Document content -->

            <footer>
                <!-- Notes and references -->
                <?php include RMTemplate::get()->get_template('docs-notes-references.php','module','docs'); ?>
                <!-- /End Notes and references -->
            </footer>
        </div>
    </div>

    <!-- Navigation links -->
    <section class="document-navigation">

        <a class="previous"><span class="fa fa-angle-left"></span></a>
        <a class="next"><span class="fa fa-angle-right"></span></a>

    </section>
    <!-- /Navigation links -->

</article>