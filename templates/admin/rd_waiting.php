<script type="text/javascript">
    var rd_select_message = '<?php _e('Select at least one element before to do this action!','docs'); ?>';
    var rd_message = '<?php _e('Do you really wish to delete selected items?','docs'); ?>';
</script>
<h1 class="cu-section-title"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Waiting Content','docs'); ?></h1>
<div class="descriptions">
    <?php _e('Next are the edited sections waiting for approve and revision. Please, be carefull with content to prevent spam or another inconvenniences.','docs'); ?>
</div>

<form name="frmEdits" id="frm-edits" method="post" action="edits.php">
<div class="rd_loptions">
    <?php $nav->display(false); ?>
    <select name="action" id="bulk-top">
        <option value=""><?php _e('Bulk actions...','docs'); ?></option>
        <option value="approve"><?php _e('Approve','docs'); ?></option>
        <option value="delete"><?php _e('Delete','docs'); ?></option>
    </select>
    <input type="button" id="the-op-top" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-edits');" />
    <?php RMEvents::get()->run_event('docs.get.edits.options'); ?>
</div>
<table class="outer" cellspacing="1" width="100%">
    <thead>
	<tr class="head" align="center">
		<th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-edits").toggleCheckboxes(":not(#checkall)");' /></th>
		<th align="left"><?php _e('Section','docs'); ?></th>
		<th align="left"><?php _e('New Title','docs') ?></th>
		<th><?php _e('Modified','docs'); ?></th>
		<th><?php _e('Edited by','docs'); ?></th>
	</tr>
    </thead>
    <tfoot>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" id="checkall2" onclick='$("#frm-edits").toggleCheckboxes(":not(#checkall2)");' /></th>
        <th align="left"><?php _e('Section','docs'); ?></th>
        <th align="left"><?php _e('New Title','docs') ?></th>
        <th><?php _e('Modified','docs'); ?></th>
        <th><?php _e('Edited by','docs'); ?></th>
    </tr>
    </tfoot>
    <tbody>
    <?php if(empty($sections)): ?>
    <tr class="even">
        <td colspan="5" align="center"><?php _e('There are not waiting contents at this time','docs'); ?></td>
    </tr>
    <?php endif; ?>
	<?php foreach($sections as $edit): ?>
		<tr class="<?php echo tpl_cycle("even,odd"); ?>" align="center" valign="top">
			<td><input type="checkbox" name="ids[]" id="item-<?php echo $edit['id']; ?>" value="<?php echo $edit['id']; ?>" /></td>
			<td align="left">
                <a href="<?php echo $edit['section']['link']; ?>"><?php echo $edit['section']['title']; ?></a>
            </td>
			<td align="left">
                <a href="?action=review&amp;id=<?php echo $edit['id']; ?>"><?php echo $edit['title']; ?></a>
                <span class="cu-item-options">
            <a href="?action=review&amp;id=<?php echo $edit['id']; ?>"><?php _e('Review','docs'); ?></a> |
                <a href="?action=approve&amp;ids[]=<?php echo $edit['id']; ?>"><?php _e('Approve','docs'); ?></a> |
                <a href="?action=edit&amp;id=<?php echo $edit['id']; ?>"><?php _e('Edit','docs'); ?></a> |
                <a href="javascript:;" onclick="return exmConfirmMsg('<{$edit.msg}>');"><?php _e('Delete','docs'); ?></a>
            </span>
            </td>
			<td><?php echo $edit['date']; ?></td>
			<td><?php echo $edit['uname']; ?></td>
		</tr>
	<?php endforeach; ?>
    </tbody>
</table>
<div class="rd_loptions">
    <?php $nav->display(false); ?>
    <select name="actionb" id="bulk-bottom">
        <option value=""><?php _e('Bulk actions...','docs'); ?></option>
        <option value="approve"><?php _e('Approve','docs'); ?></option>
        <option value="delete"><?php _e('Delete','docs'); ?></option>
    </select>
    <input type="button" id="the-op-bottom" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-edits');" />
    <?php RMEvents::get()->run_event('docs.get.edits.options'); ?>
</div>
</form>