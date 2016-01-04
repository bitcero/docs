<!-- Document Content -->
<article class="docs-content-article">
    <?php include RMTemplate::get()->get_template('docs-header.php', 'module', 'docs'); ?>
    <div class="docs-content-wrapper">
        <div class="docs-content-inner">
            <section id="section-<?php echo $section->id(); ?>" class="doc-section is-header">
                <header>
                    <a name="<?php echo $section->nameid; ?>"></a>
                    <?php /*h<?php echo $level <= 6 ? $level : 6; ?> class="section-title"><?php echo $number; ?> <?php echo $section->title; ?></h<?php echo $level <= 6 ? $level : 6; ?>*/?>
                    <h1 class="section-title"><?php echo $number; ?> <?php echo $section->title; ?></h1>
                </header>

                <?php echo $section->content; ?>
            </section>

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