<section id="document-description">
    <?php echo $res->getVar('description'); ?>
</section>
<hr>
<section id="document-toc">
    <?php if(!empty($toc)): ?>
        <strong>Contenido</strong>
        <ul class="list-unstyled" id="document-index-toc">
            <?php foreach($toc as $sec): ?>
                <li style="padding-left: <?php echo $sec['jump']*10; ?>px;"><a href="<?php echo $sec['link']; ?>"><?php echo $sec['number']; ?>. <?php echo $sec['title']; ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
<hr>

