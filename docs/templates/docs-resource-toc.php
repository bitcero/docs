<section id="document-toc">
    <?php if (!empty($toc)): ?>
        <strong><?php _e('Table of Contents', 'docs'); ?></strong>
        <ol class="list-unstyled" id="document-index-toc">
            <?php foreach ($toc as $sec): ?>
                <li style="padding-left: <?php echo($sec['jump'] * 15); ?>px;">
                    <?php echo $sec['number']; ?>.
                    <a href="<?php echo $sec['link']; ?>">
                        <?php if (0 == $sec['jump']): ?>
                            <strong><?php echo $sec['title']; ?></strong>
                        <?php else: ?>
                            <?php echo $sec['title']; ?>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>
</section>
<hr>

