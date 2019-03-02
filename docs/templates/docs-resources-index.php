<ul class="resource_index">
    <?php foreach ($resources as $res): ?>
        <li>
            <strong><a href="<?php echo $res['link']; ?>"><?php echo $res['title']; ?></a></strong><br />
            <?php echo $res['desc']; ?>
        </li>
    <?php endforeach; ?>
</ul>
