<div id="rd_res_search" class="pull-right">
    <form action="resources.php" method="get">
        <strong><?php _e('Search Documents:','docs'); ?></strong>
        <input type="text" name="query" value="<?php echo isset($query) ? $query : ''; ?>" class="form-control" />
    </form>
</div>
<?php if($query!=''): ?>
<h1 class="cu-section-title"><span style="background-position: left -32px;">&nbsp;</span><?php echo sprintf(__('Documents: results for "%s"','docs'), $query); ?></h1>
<?php else: ?>
<h1 class="cu-section-title"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Available Documents','docs'); ?></h1>
<?php endif; ?>

<form name="frm_resources" id="frm-resources" method="post" action="resources.php">
<div class="cu-bulk-actions">
    <div class="row">
        <div class="col-md-5">
            <select name="action" id="bulk-top" class="form-control">
                <option value=""><?php _e('Bulk actions...','docs'); ?></option>
                <option value="approve"><?php _e('Approve','docs'); ?></option>
                <option value="draft"><?php _e('Draft','docs'); ?></option>
                <option value="public"><?php _e('Set as public','docs'); ?></option>
                <option value="private"><?php _e('Set as private','docs'); ?></option>
                <option value="qindex"><?php _e('Enable quick index','docs'); ?></option>
                <option value="noqindex"><?php _e('Disable quick index','docs'); ?></option>
                <option value="delete"><?php _e('Delete','docs'); ?></option>
            </select>
            <input type="button" id="the-op-top" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-resources');" class="btn btn-default" />
            &nbsp; &nbsp;
            <a href="resources.php"><?php _e('Show All','docs'); ?></a>
        </div>
        <div class="col-md-7">
            <?php $nav->display(false); ?>
        </div>
    </div>
</div>

<div class="table-responsive">

    <table class="table" width="100%" cellspacing="1">
        <thead>
        <tr>
            <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-resources").toggleCheckboxes(":not(#checkall)");' /></th>
            <th width="30"><?php _e('ID','docs'); ?></th>
            <th align="left"><?php _e('Title','docs'); ?></th>
            <th><?php _e('Owner','docs'); ?></th>
            <th><?php _e('Created','docs'); ?></th>
            <th><?php _e('Sections','docs'); ?></th>
            <th><?php _e('Notes','docs'); ?></th>
            <th><?php _e('Figures','docs'); ?></th>
            <th><?php _e('Attributes','docs'); ?></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th width="20"><input type="checkbox" id="checkall2" onclick='$("#frm-resources").toggleCheckboxes(":not(#checkall2)");' /></th>
            <th width="30"><?php _e('ID','docs'); ?></th>
            <th align="left"><?php _e('Title','docs'); ?></th>
            <th><?php _e('Owner','docs'); ?></th>
            <th><?php _e('Created','docs'); ?></th>
            <th><?php _e('Sections','docs'); ?></th>
            <th><?php _e('Notes','docs'); ?></th>
            <th><?php _e('Figures','docs'); ?></th>
            <th><?php _e('Attributes','docs'); ?></th>
        </tr>
        </tfoot>
        <tbody>
        <?php if( empty( $resources ) ): ?>
            <tr>
                <td class="text-center" colspan="9">
                    <span class="label label-info"><?php _e('There are not documents created yet!', 'docs'); ?></span>
                </td>
            </tr>
        <?php endif; ?>
        <?php foreach($resources as $res): ?>
            <tr align="center" valign="top">
                <td><input type="checkbox" name="ids[]" value="<?php echo $res['id']; ?>" id="item-<?php echo $res['id']; ?>" /></td>
                <td><strong><?php echo $res['id']; ?></strong></td>
                <td align="left">
                    <a href="./sections.php?id=<?php echo $res['id']; ?>" ><?php echo $res['title']; ?></a>
                    <?php if(!$res['approved']): ?>[Draft]<?php endif; ?>
                    <span class="cu-item-options">
                    <a href="./resources.php?action=edit&amp;id=<?php echo $res['id']; ?>&amp;page=<?php echo $page; ?>" ><?php _e('Edit','docs'); ?></a>
                    | <a href="javascript:;" onclick="rd_check_delete(<?php echo $res['id']; ?>,'frm-resources');"><?php _e('Delete','docs'); ?></a>
                    | <?php if(!$res['featured']): ?><a href="./resources.php?action=recommend&amp;id=<?php echo $res['id']; ?>&amp;page=<? echo $page; ?>"><?php _e('Featured','docs'); ?><?php else: ?><a href="./resources.php?action=norecommend&amp;id=<?php echo $res['id']; ?>&amp;page=<?php echo $page; ?>"><?php _e('Not featured','docs'); ?><?php endif; ?></a>
                </span>
                </td>
                <td align="center"><a href="<?php echo XOOPS_URL; ?>/userinfo.php?uid=<?php echo $res['owner']; ?>"><?php echo $res['owname']; ?></a></td>
                <td><?php echo $res['created']; ?></td>
                <td>
                    <strong><a href="sections.php?id=<?php echo $res['id']; ?>" title="<?php _e('View Sections','docs'); ?>"><?php echo $res['sections']; ?></a></strong>
                <span class="cu-item-options">
                    <a href="./sections.php?id=<?php echo $res['id']; ?>"><?php _e('Sections','docs'); ?></a>
                </span>
                </td>
                <td>
                    <strong><a href="notes.php?res=<?php echo $res['id']; ?>" title="<?php _e('View Notes','docs'); ?>"><?php echo $res['notes']; ?></a></strong>
                <span class="cu-item-options">
                    <a href="notes.php?res=<?php echo $res['id']; ?>"><?php _e('View Notes','docs'); ?></a>
                </span>
                </td>
                <td>
                    <strong><a href="figures.php?res=<?php echo $res['id']; ?>" title="<?php _e('View Figures','docs'); ?>"><?php echo $res['figures']; ?></a></strong>
                <span class="cu-item-options">
                    <a href="figures.php?res=<?php echo $res['id']; ?>"><?php _e('View Figures','docs'); ?></a>
                </span>
                </td>
                <td>
                    <?php if($res['featured']): ?><img src="../images/featured.png" border="0" title="<?php _e('Featured','docs'); ?>" alt="<?php _e('Featured','docs'); ?>" /><?php endif; ?>
                    <?php if($res['approved']): ?><img src="../images/approved.png" border="0" title="<?php _e('Approved','docs'); ?>" alt="<?php _e('Approved','docs'); ?>" /><?php endif; ?>
                    <?php if($res['public']): ?><img src="../images/public.png" border="0" title="<?php _e('Published','docs'); ?>" alt="<?php _e('Published','docs'); ?>" /><?php endif; ?>
                    <?php if($res['quick']): ?><img src="../images/quick.png" border="0" title="<?php _e('Quick Index','docs'); ?>" alt="<?php _e('Quick Index','docs'); ?>" /><?php endif; ?>
                </td>
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
                <option value="approve"><?php _e('Approve','docs'); ?></option>
                <option value="draft"><?php _e('Draft','docs'); ?></option>
                <option value="public"><?php _e('Set as public','docs'); ?></option>
                <option value="private"><?php _e('Set as private','docs'); ?></option>
                <option value="qindex"><?php _e('Enable quick index','docs'); ?></option>
                <option value="noqindex"><?php _e('Disable quick index','docs'); ?></option>
                <option value="delete"><?php _e('Delete','docs'); ?></option>
            </select>
            <input type="button" id="the-op-bottom" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-resources');" class="btn btn-default" />
        </div>

        <div class="col-md-7">
            <?php $nav->display(false); ?>
        </div>

    </div>

</div>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input type="hidden" name="page" value="<?php echo $page; ?>" />
</form>
