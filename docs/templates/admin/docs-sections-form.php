<script type="text/javascript">
$(document).ready(function(){
    $("#frm-section").validate({
        messages: {
            title: "<?php _e('You must provide a title for this section!','docs'); ?>"
        }
    });
});
</script>
<h1 class="cu-section-title"><span style="background-position: left -32px;">&nbsp;</span><?php $edit ? _e('Edit Section','docs') : _e('Create Section','docs'); ?></h1>
<form name="formSection" method="post" action="sections.php" id="frm-section">
<div id="rd-form-container" class="form">
    <input type="text" size="50" name="title" id="sectitle" value="<?php echo $edit ? $sec->getVar('title') : ''; ?>" class="required form-control input-lg" />
    <?php if($edit): ?>
    <div id="section-url">
        <strong>Permalink:</strong> <?php echo $sec->permalink(1); ?>
        <a href="<?php echo $sec->permalink(); ?>" target="_blank" class="btn btn-success btn-xs pull-right">
            <?php _e('View content', 'docs'); ?>
            <span class="fa fa-external-link"></span>
        </a>
    </div>
    <?php else: ?>
    <div class="info"><?php _e('Remember to save this section in order to activate all options.','docs'); ?></div>
    <?php endif; ?>
    <?php echo $editor->render(); ?>
    <br />
    <table width="100%" cellspacing="0" class="table table-bordered">
        <tr>
            <td>
            <div>
                <div class="th"><?php _e('Section Author:','docs'); ?></div>
                <div class="even" style="text-align: center;">
                    <?php echo $usrfield->render(); ?>
                </div>
            </div>
            </td>
            <td>
                <table class="table">
                    <tr><th align="left" colspan="3"><?php _e('Sections Options','docs'); ?></th></tr>
                    <tr class="even section_options">
                        <td>
                            <label for="sec-parent"><?php _e('Parent section:','docs'); ?></label>
                            <select name="parent" id="sec-parent" class="form-control">
                                <option value=""><?php _e('Select...','docs'); ?></option>
                                <?php foreach($sections as $k): ?>
                                <option value="<?php echo $k['id_sec']; ?>"<?php if(isset($sec) && $sec->getVar('parent')==$k['id_sec']): ?> selected="selected"<?php elseif(isset($parent) && $parent==$k['id_sec']): ?> selected="selected"<?php endif; ?>><?php echo str_repeat('&#8212;', $k['saltos']).' '.$k['title']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                             <label for="sec-order"><?php _e('Display order:','docs'); ?></label>
                             <input class="form-control" type="text" size="5" id="sec-order" name="order" value="<?php echo isset($sec) ? $sec->getVar('order') : $order++; ?>" />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br />
    <div class="cu-box">
        <div class="box-header">
            <span class="fa fa-caret-up box-handler"></span>
            <h3><?php _e('Custom Fields','docs'); ?></h3>
        </div>
        <div class="box-content">
            <table id="metas-container" class="table<?php echo !$edit || (!isset($sec) && !$sec->metas()) ? ' rd_hidden' : ''; ?>" cellspacing="0" width="100%" />
            <tr class="head">
                <td width="30%"><?php _e('Name','docs'); ?></td>
                <td><?php _e('Value','docs'); ?></td>
            </tr>
            <?php if($edit || (isset($sec) && $sec->metas())): ?>
                <?php $i=0;
                foreach($sec->metas() as $key => $value): ?>
                    <tr>
                        <td valign="top">
                            <input class="form-control" type="text" name="metas[<?php echo $i; ?>][key]" id="meta-key-<?php echo $i; ?>" value="<?php echo $key; ?>" />
                            <a href="javascript:;" onclick="$(this).parents('tr').remove();"><?php _e('Remove','docs'); ?></a>
                        </td>
                        <td><textarea class="form-control" name="metas[<?php echo $i; ?>][value]" id="metas[<?php echo $i; ?>][value]"><?php echo $value; ?></textarea></td>
                    </tr>
                    <?php $i++;
                endforeach; ?>
            <?php endif; ?>
            </table><br />
            <label><strong><?php _e('Add new field:','docs'); ?></strong></label>
            <table class="outer" cellspacing="0" />
            <tr class="head" align="center">
                <td width="30%"><?php _e('Name','docs'); ?></td>
                <td><?php _e('Value','docs'); ?></td>
            </tr>
            <tr class="even">
                <td valign="top">
                    <label class="error" style="display: none;" id="error-metaname">Please, select or specify a new meta name</label>
                    <?php if(!empty($meta_names)): ?>
                        <select name="meta_name_sel" id="meta-name-sel" class="form-control">
                            <option value="" selected="selected"><?php _e('- Select -','docs'); ?></option>
                            <?php foreach ($meta_names as $name): ?>
                                <option value="<?php echo $name; ?>"><?php echo $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="meta_name" id="meta-name" value="" class="form-control" />
                        <a href="javascript:;" class="rd_show_metaname"><?php _e('Enter New','docs'); ?></a>
                        <a href="javascript:;" class="rd_hide_metaname" style="display: none;"><?php _e('Cancel','docs'); ?></a>
                    <?php else: ?>
                        <input type="text" name="meta_name" id="meta-name" value="" class="form-control" />
                    <?php endif; ?>
                </td>
                <td valign="top">
                    <label class="error" style="display: none;" id="error-metavalue"><?php _e('Please provide a value for this meta field','docs'); ?></label>
                    <textarea name="meta_value" id="meta-value" class="form-control"></textarea>
                </td>
            </tr>
            <tr class="odd">
                <td colspan="2">
                    <input type="button" id="rd-addmeta" value="<?php _e('Add custom field','docs'); ?>" class="btn btn-info" />
                </td>
            </tr>
            </table>
        </div>
        <div class="box-footer">
            <?php _e('Custom fields can be used to add extra metadata to a post that you can use in your theme.','docs'); ?>
        </div>
    </div>
    <!-- Extra fields -->
    <?php RMEvents::get()->run_event('docs.sections.form.fields', $edit ? $sec : null); ?>
    <!-- End Extra Fields -->
</div>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="return" id="secreturn" value="1" />
<input type="hidden" name="nameid" id="nameid" value="<?php echo $edit ? $sec->getVar('nameid') : ''; ?>" />
<input type="hidden" name="action" value="<?php echo $edit ? 'saveedit' : 'save'; ?>" />
<?php if($edit): ?><input type="hidden" name="id_sec" value="<?php echo $id_sec; ?>" /><?php endif; ?>
</form>