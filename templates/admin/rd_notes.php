<div id="rd_res_search">
    <form action="notes.php" method="get">
        <strong><?php _e('Search Notes:','docs'); ?></strong>
        <input type="text" name="search" value="<?php echo isset($search) ? $search : ''; ?>" />
        <input type="hidden" name="res" value="<?php echo $id_res; ?>" />
    </form>
</div>
<h1 class="cu-section-title rd_titles"><span style="background-position: left -32px;">&nbsp;</span><?php echo sprintf(__('Notes in %s','docs'), $res->getVar('title')); ?></h1>


    <form name="frmnotes" id="frm-notes" method="POST" action="notes.php">
    <div class="rd_loptions">
        <?php $nav->display(false); ?>
        <select name="action" id="bulk-top">
            <option value=""><?php _e('Bulk actions...','docs'); ?></option>
            <option value="delete"><?php _e('Delete','docs'); ?></option>
        </select>
        <input type="button" id="the-op-top" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-notes');" />
        &nbsp;&nbsp;
        <strong><a href="resources.php"><?php _e('Choose another Document','docs'); ?></a></strong> |
        <a href="notes.php?res=<?php echo $id_res; ?>"><?php _e('All Notes','docs'); ?></a>
        <?php RMEvents::get()->run_event('docs.get.notes.options'); ?>
    </div>
    <table width="100%" cellspacing="1" class="outer">
        <thead>
	    <tr class="head" align="center">
		    <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-notes").toggleCheckboxes(":not(#checkall)");' /></th>
		    <th width="20"><?php _e('ID','docs'); ?></th>
		    <th align="left"><?php _e('Title','docs'); ?></th>
            <th align="left"><?php _e('Content','docs'); ?></th>
	    </tr>
        </thead>
        <tfoot>
        <tr class="head" align="center">
            <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-notes").toggleCheckboxes(":not(#checkall)");' /></th>
            <th width="20"><?php _e('ID','docs'); ?></th>
            <th align="left"><?php _e('Title','docs'); ?></th>
            <th align="left"><?php _e('Content','docs'); ?></th>
        </tr>
        </tfoot>
        <tbody>
        <?php if(empty($notes)): ?>
        <tr class="even" align="center">
            <td colspan="4"><?php _e('There are not notes created for this Document.','docs'); ?></td>
        </tr>
        <?php endif; ?>
	    <?php foreach($notes as $note): ?>
	    <tr class="<?php echo tpl_cycle('even,odd'); ?>" valign="top">
		    <td align="center"><input type="checkbox" name="ids[]" value="<?php echo $note['id']; ?>" id="item-<?php echo $note['id']; ?>" /></td>
		    <td align="center"><?php echo $note['id']; ?></td>
		    <td><a id="note<?php echo $note['id']; ?>"></a>
                <strong><?php echo $note['title']; ?></strong>
                <span class="cu-item-options">
                    <a href="?action=edit&amp;res=<?php echo $id_res; ?>&amp;id=<?php echo $note['id']; ?>"><?php _e('Edit','docs'); ?></a> |
                    <a href="javascript:;" onclick="rd_check_delete(<?php echo $note['id']; ?>, 'frm-notes');"><?php _e('Delete','docs'); ?></a>
                </span>
            </td>
		    <td><?php echo $note['text']; ?></td>
	    </tr>
	    <?php endforeach; ?>
        </tbody>
    </table>
    <div class="rd_loptions">
        <?php $nav->display(false); ?>
        <select name="actionb" id="bulk-bottom">
            <option value=""><?php _e('Bulk actions...','docs'); ?></option>
            <option value="delete"><?php _e('Delete','docs'); ?></option>
        </select>
        <input type="button" id="the-op-bottom" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-notes');" />
    </div>
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
    <input type="hidden" name="page" value="<?php echo $page; ?>" />
    <input type="hidden" name="search" value="<?php echo $search; ?>" />
    <input type="hidden" name="res" value="<?php echo $id_res; ?>" />
    </form>
