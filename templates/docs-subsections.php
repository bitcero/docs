<?php if(!empty($sections)): ?>
<ul class="subsections">
    <?php foreach( $sections as $section ): ?>

        <li data-section="<?php echo $section['id']; ?>">
            <a
                href="<?php echo $section['link']; ?>"
                data-level="<?php echo $section['number']; ?>">
                <?php if($section['jump']==0): ?>
                    <strong><?php echo $section['title']; ?></strong>
                <?php else: ?>
                    <?php echo $section['title']; ?>
                <?php endif; ?>
            </a>

            <?php if(array_key_exists('sections', $section)){

                $common->template()->assign('sections', $section['sections']);
                $common->template()->assign('common', $common);
                $common->template()->display('docs-subsections.php', 'module', 'docs');

            } ?>

        </li>

    <?php endforeach; ?>
</ul>
<?php endif; ?>
