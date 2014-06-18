<table class="outer" cellspacing="0" width="100%">
<tr class="even resources_list">
<?php 
    $i = 0;
    foreach($resources as $res): 
        if ($i>1): 
            $i = 0; ?>
        </tr><tr class="<?php echo tpl_cycle("odd,even"); ?> resources_list">
    <?php endif;
?>
    <td class="even"><a href="?id=<?php echo $res['id']; ?>"><?php echo $res['title']; ?></a></td>
<?php $i++; endforeach; ?>
</tr>
<tr class="foot">
    <td colspan="2"><?php echo $nav->render(false); ?></td>
</tr>
</table>