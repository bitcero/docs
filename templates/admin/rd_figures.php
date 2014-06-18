<div id="rd_res_search">
    <form action="figures.php" method="get">
        <strong><?php _e('Search Figures:','docs'); ?></strong>
        <input type="text" name="search" value="<?php echo isset($search) ? $search : ''; ?>" />
        <input type="hidden" name="res" value="<?php echo $id_res; ?>" />
    </form>
</div>
<h1 class="cu-section-title rd_titles"><span style="background-position: left -32px;">&nbsp;</span><?php echo sprintf(__('Figures in %s','docs'), $res->getVar('title')); ?></h1>

<form name="frmfigs" id="frm-figures" method="POST" action="figures.php">
    <div class="rd_loptions">
        <?php $nav->display(false); ?>
        <select name="action" id="bulk-top">
            <option value=""><?php _e('Bulk actions...','docs'); ?></option>
            <option value="delete"><?php _e('Delete','docs'); ?></option>
        </select>
        <input type="button" id="the-op-top" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-figures');" />
        &nbsp;&nbsp;
        <strong><a href="resources.php"><?php _e('Choose another Document','docs'); ?></a></strong> |
        <a href="figures.php?res=<?php echo $id_res; ?>"><?php _e('All Figures','docs'); ?></a> |
        <a href="?action=new&amp;res=<?php echo $id_res; ?>"><?php _e('New Figure','docs'); ?></a>
        <?php RMEvents::get()->run_event('docs.get.figures.options'); ?>
    </div>
    <table width="100%" cellspacing="1" class="outer">
        <thead>
        <tr class="head" align="center">
            <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-figures").toggleCheckboxes(":not(#checkall)");' /></th>
            <th width="20"><?php _e('ID','docs'); ?></th>
            <th align="left"><?php _e('Title','docs'); ?></th>
            <th align="left"><?php _e('Description','docs'); ?></th>
        </tr>
        </thead>
        <tfoot>
        <tr class="head" align="center">
            <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-figures").toggleCheckboxes(":not(#checkall)");' /></th>
            <th width="20"><?php _e('ID','docs'); ?></th>
            <th align="left"><?php _e('Title','docs'); ?></th>
            <th align="left"><?php _e('Description','docs'); ?></th>
        </tr>
        </tfoot>
        <tbody>
        <?php if(empty($figures)): ?>
        <tr class="even" align="center">
            <td colspan="4"><?php _e('There are not figures created for this Document.','docs'); ?></td>
        </tr>
        <?php endif; ?>
        <?php foreach($figures as $fig): ?>
        <tr class="<?php echo tpl_cycle('even,odd'); ?>" valign="top">
            <td align="center"><input type="checkbox" name="ids[]" value="<?php echo $fig['id']; ?>" id="item-<?php echo $fig['id']; ?>" /></td>
            <td align="center"><?php echo $fig['id']; ?></td>
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
    <div class="rd_loptions">
        <?php $nav->display(false); ?>
        <select name="actionb" id="bulk-bottom">
            <option value=""><?php _e('Bulk actions...','docs'); ?></option>
            <option value="delete"><?php _e('Delete','docs'); ?></option>
        </select>
        <input type="button" id="the-op-bottom" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-figures');" />
    </div>
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
    <input type="hidden" name="page" value="<?php echo $page; ?>" />
    <input type="hidden" name="search" value="<?php echo $search; ?>" />
    <input type="hidden" name="res" value="<?php echo $id_res; ?>" />
    </form>