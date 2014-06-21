<?php if($display): ?>
    
    <table class="outer" cellspacing="0" width="100%">
        <tr class="<?php echo tpl_cycle("even,odd"); ?>">
            <?php 
            $i = 0;
            foreach($resources as $res): 
                if($i>=$cols):
            ?>
                    </tr><tr class="<?php echo tpl_cycle("even,odd"); ?>">
            <?php $i = 0; endif; ?>
            <td valign="top" style="width: <?php echo floor(100/$cols); ?>%">
                <strong><a href="<?php echo $res['link']; ?>"><?php echo $res['title']; ?></a></strong><br />
                <?php echo $res['desc']; ?>
            </td>
            <?php $i++; endforeach; ?>
        </tr>
    </table>

<?php else: ?>

    <ul class="resource_index">
        <?php foreach($resources as $res): ?>
        <li>
                <strong><a href="<?php echo $res['link']; ?>"><?php echo $res['title']; ?></a></strong><br />
                <?php echo $res['desc']; ?>
        </li>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>