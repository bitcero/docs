<div class="odd document-toc-section">
    <strong><?php _e('Contenido','docs'); ?></strong>
    <ul class="list-unstyled">
    <?php foreach($toc as $sec): ?>
        <li style="padding-left: <?php echo $sec['jump']*10; ?>px;"><a href="#<?php echo $sec['nameid']; ?>"><?php echo $sec['number']; ?>. <?php echo $sec['title']; ?></a></li>
    <?php endforeach; ?>
    </ul>
</div>
<div class="clearfix"></div>