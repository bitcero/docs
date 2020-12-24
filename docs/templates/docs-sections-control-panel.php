<?php include RMTemplate::getInstance()->get_template('docs-header.php', 'module', 'docs'); ?>
<form name="frmsec" method="POST" action="<?php echo RMUris::current_url(); ?>">

    <ul class="nav nav-pills pull-right">
        <li><a class="addpage" href="<?php echo $new_link; ?>"><?php _e('New Page', 'docs'); ?></a></li>
    </ul>

    <h3><?php _e('Existing Pages', 'docs'); ?></h3>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr align="center">
                <th><?php _e('Title', 'docs'); ?></th>
                <th class="text-center"><?php _e('ID', 'docs'); ?></th>
                <th class="text-center"><?php _e('Order', 'docs'); ?></th>
                <th class="text-center"><?php _e('Options', 'docs'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($sections as $sec): ?>
                <tr align="center"  class="<?php echo tpl_cycle('even,odd'); ?>">
                    <td align="left" style="padding-left: <?php echo $sec['jump'] * 10 + 8; ?>px;"><a href="<?php echo $sec['link']; ?>"><?php echo $sec['number']; ?>. <?php echo $sec['title']; ?></a></td>
                    <td><?php echo $sec['id']; ?></td>
                    <td><input type="text" name="orders[<?php echo $sec['id']; ?>]" id="orders[<?php echo $sec['id']; ?>]" size="5" value="<?php echo $sec['order']; ?>" class="form-control input-sm text-center"></td>
                    <td>
                        <a href="<?php echo $sec['editlink']; ?>"><?php _e('Edit', 'docs'); ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5">
                    <button type="submit" onclick="document.forms['frmsec'].action.value='changeorder';" class="btn btn-primary"><?php _e('Save Orders', 'docs'); ?></button>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>

<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input type="hidden" name="action" value="">
<input type="hidden" name="id" value="<?php echo $id; ?>">
</form>
