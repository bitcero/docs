<div style="overflow: hidden; margin-bottom: 10px;">
    <div class="odd rd_toc_section">
    <h3><?php _e('Contenido','docs'); ?></h3>
    <ul>
    <?php foreach($toc as $sec): ?>
        <li style="padding-left: <?php echo $sec['jump']*10; ?>px;"><a href="#<?php echo $sec['nameid']; ?>"><strong><?php echo $sec['number']; ?></strong> <?php echo $sec['title']; ?></a></li>
    <?php endforeach; ?>
    </ul>
    </div>
</div>