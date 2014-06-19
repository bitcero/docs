<div class="row">
    <div class="col-sm-6 pull-right">
        <form action="notes.php" method="get">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" value="<?php echo isset($search) ? $search : ''; ?>" placeholder="Search for notes...">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-default"><span class="fa fa-search"></span></button>
                    </span>
                </div>
            </div>

            <input type="hidden" name="res" value="<?php echo $id_res; ?>" />
        </form>
    </div>
</div>
<h1 class="cu-section-title"><?php echo sprintf(__('Notes in %s','docs'), '&laquo;' . $res->getVar('title') . '&raquo;'); ?></h1>


    <form name="frmnotes" id="frm-notes" method="POST" action="notes.php">

    <div class="cu-bulk-actions">
        <div class="row">
            <div class="col-md-6">
                <select name="action" id="bulk-top" class="form-control">
                    <option value=""><?php _e('Bulk actions...','docs'); ?></option>
                    <option value="delete"><?php _e('Delete','docs'); ?></option>
                </select>
                <button type="button" id="the-op-top" class="btn btn-default" onclick="before_submit('frm-notes');"><?php _e('Apply', 'docs' ); ?></button>
            </div>
            <div class="col-md-6">
                <ul class="nav nav-pills">
                    <li>
                        <a href="resources.php"><?php _e('Choose another Document','docs'); ?></a>
                    </li>
                    <li>
                        <a href="notes.php?res=<?php echo $id_res; ?>"><?php _e('All Notes','docs'); ?></a>
                    </li>
                    <?php RMEvents::get()->run_event('docs.get.notes.options'); ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
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
    </div>


    <div class="cu-bulk-actions">

        <div class="row">
            <div class="col-md-6">
                <select name="actionb" id="bulk-bottom" class="form-control">
                    <option value=""><?php _e('Bulk actions...','docs'); ?></option>
                    <option value="delete"><?php _e('Delete','docs'); ?></option>
                </select>
                <button type="button" id="the-op-bottom" onclick="before_submit('frm-notes');" class="btn btn-default"><?php _e('Apply','docs'); ?></button>
            </div>
            <div class="col-md-6 text-right">
                <?php $nav->display(false); ?>
            </div>
        </div>

    </div>
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
    <input type="hidden" name="page" value="<?php echo $page; ?>" />
    <input type="hidden" name="search" value="<?php echo $search; ?>" />
    <input type="hidden" name="res" value="<?php echo $id_res; ?>" />
    </form>
