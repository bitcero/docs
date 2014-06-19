<div class="row">
    <div class="col-sm-8">
        <h1 class="cu-section-title"><?php echo sprintf(__('Figures in %s','docs'), '&laquo;' . $res->getVar('title') . '&raquo;' ); ?></h1>
    </div>
    <div class="col-sm-4">
        <form action="figures.php" method="get">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" value="<?php echo isset($search) ? $search : ''; ?>" placeholder="Search for figures...">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-default"><span class="fa fa-search"></span></button>
                    </span>
                </div>
            </div>

            <input type="hidden" name="res" value="<?php echo $id_res; ?>" />
        </form>
    </div>
</div>

<form name="frmfigs" id="frm-figures" method="POST" action="figures.php">
    <div class="cu-bulk-actions">

        <div class="row">
            <div class="col-sm-5">
                <select name="action" id="bulk-top" class="form-control">
                    <option value=""><?php _e('Bulk actions...','docs'); ?></option>
                    <option value="delete"><?php _e('Delete','docs'); ?></option>
                </select>
                <button type="button" class="btn btn-default" id="the-op-top" onclick="before_submit('frm-figures');"><?php _e('Apply','docs'); ?></button>
            </div>

            <div class="col-sm-7">
                <ul class="nav nav-pills">
                    <li>
                        <a href="resources.php"><span class="fa fa-filter"></span> <?php _e('Choose another Document','docs'); ?></a>
                    </li>
                    <li>
                        <a href="figures.php?res=<?php echo $id_res; ?>"><span class="fa fa-list"></span> <?php _e('All Figures','docs'); ?></a>
                    </li>
                    <li>
                        <a href="?action=new&amp;res=<?php echo $id_res; ?>"><span class="fa fa-plus"></span> <?php _e('New Figure','docs'); ?></a>
                    </li>
                    <?php RMEvents::get()->run_event('docs.get.figures.options'); ?>
                </ul>
            </div>
        </div>

    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-figures").toggleCheckboxes(":not(#checkall)");' /></th>
                <th width="20"><?php _e('ID','docs'); ?></th>
                <th><?php _e('Title','docs'); ?></th>
                <th><?php _e('Description','docs'); ?></th>
            </tr>
            </thead>
            <tfoot>
            <tr align="center">
                <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-figures").toggleCheckboxes(":not(#checkall)");' /></th>
                <th width="20"><?php _e('ID','docs'); ?></th>
                <th><?php _e('Title','docs'); ?></th>
                <th><?php _e('Description','docs'); ?></th>
            </tr>
            </tfoot>
            <tbody>
            <?php if(empty($figures)): ?>
                <tr class="text-center">
                    <td colspan="4"><?php _e('There are not figures created for this Document.','docs'); ?></td>
                </tr>
            <?php endif; ?>
            <?php foreach($figures as $fig): ?>
                <tr valign="top">
                    <td class="text-center"><input type="checkbox" name="ids[]" value="<?php echo $fig['id']; ?>" id="item-<?php echo $fig['id']; ?>" /></td>
                    <td class="text-center"><?php echo $fig['id']; ?></td>
                    <td><a id="figure<?php echo $fig['id']; ?>"></a>
                        <strong><?php echo $fig['title']; ?></strong>
                <span class="cu-item-options">
                    <a href="?action=edit&amp;res=<?php echo $id_res; ?>&amp;id=<?php echo $fig['id']; ?>"><?php _e('Edit','docs'); ?></a> |
                    <a href="javascript:;" onclick="rd_check_delete(<?php echo $fig['id']; ?>, 'frm-figures');"><?php _e('Delete','docs'); ?></a>
                </span>
                    </td>
                    <td><?php echo $fig['text']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="cu-bulk-actions">
        <div class="row">
            <div class="col-md-5">
                <select name="actionb" id="bulk-bottom" class="form-control">
                    <option value=""><?php _e('Bulk actions...','docs'); ?></option>
                    <option value="delete"><?php _e('Delete','docs'); ?></option>
                </select>
                <button type="button" class="btn btn-default" id="the-op-bottom" onclick="before_submit('frm-figures');"><?php _e('Apply','docs'); ?></button>
            </div>
            <div class="col-md-7 text-right">
                <?php $nav->display(false); ?>
            </div>
        </div>

    </div>
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
    <input type="hidden" name="page" value="<?php echo $page; ?>" />
    <input type="hidden" name="search" value="<?php echo $search; ?>" />
    <input type="hidden" name="res" value="<?php echo $id_res; ?>" />
    </form>