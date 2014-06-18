<?php include RMTemplate::get()->get_template('rd_header.php', 'module', 'docs'); ?>

<h1><?php $edit ? _e('Edit Page','docs') : _e('New Page','docs'); ?></h1>

<div class="form_container">
<form name="frmPage" id="frm-page" method="post" action="<?php echo RMFunctions::current_url(); ?>">
<label><?php _e('Document','docs'); ?></label>
<span><?php echo $res->getVar('title'); ?></span>
<label for="sec-title"><?php _e('Title','docs'); ?></label>
<input type="text" name="title" id="sec-title" value="<?php echo $edit ? $section->getVar('title') : ''; ?>" />
<?php echo $editor->render(); ?>
<label for="sec-parent"><?php _e('Parent','docs'); ?></label>
<select name="parent" id="sec-parent">
    <option value=""<?php echo $edit && $section->getVar('parent')<=0 ? ' selected="selected"' : ''; ?>><?php _e('Select parent...','docs'); ?></option>
    <?php foreach($sections as $sec): ?>
    <option value="<?php echo $sec['id']; ?>"<?php echo $edit && $section->getVar('parent')==$sec['id'] ? ' selected="selected"' : ''; ?>><?php echo str_repeat('&#151;', $sec['jump']); echo $sec['title']; ?></option>
    <?php endforeach; ?>
</select>
<label for="sec-order"><?php _e('Display order','docs'); ?></label>
<input type="text" name="order" value="<?php echo $edit ? $section->getVar('order') : ''; ?>" />

<div class="outer">
        <h3><?php _e('Custom Fields','docs'); ?></h3>
        <div class="even">
        <table id="metas-container" class="outer<?php echo !$edit || (!isset($sec) && !$sec->metas()) ? ' rd_hidden' : ''; ?>" cellspacing="0" width="100%" />
            <tr class="head">
                <td width="30%"><?php _e('Name','docs'); ?></td>
                <td><?php _e('Value','docs'); ?></td>
            </tr>
            <?php if($edit || (isset($section) && $section->metas())): ?>
            <?php $i=0;
                foreach($section->metas() as $key => $value): ?>
                <tr class="<?php echo tpl_cycle("even,odd"); ?>">
                    <td valign="top"><input type="text" name="metas[<?php echo $i; ?>][key]" id="meta-key-<?php echo $i; ?>" value="<?php echo $key; ?>" /></td>
                    <td><textarea class="rd_large" name="metas[<?php echo $i; ?>][value]" id="metas[<?php echo $i; ?>][value]"><?php echo $value; ?></textarea></td>
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
                    <select name="meta_name_sel" id="meta-name-sel">
                        <option value="" selected="selected"><?php _e('- Select -','docs'); ?></option>
                        <?php foreach ($meta_names as $name): ?>
                        <option value="<?php echo $name; ?>"><?php echo $name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="meta_name" id="meta-name" value="" class="rd_large" style="display: none; width: 95%;" />
                    <a href="javascript:;" class="rd_show_metaname"><?php _e('Enter New','docs'); ?></a>
                    <a href="javascript:;" class="rd_hide_metaname" style="display: none;"><?php _e('Cancel','docs'); ?></a>
                    <?php else: ?>
                    <input type="text" name="meta_name" id="meta-name" value="" class="rd_large" style="width: 95%;" />
                    <?php endif; ?>
                </td>
                <td valign="top">
                    <label class="error" style="display: none;" id="error-metavalue"><?php _e('Please provide a value for this meta field','docs'); ?></label>
                    <textarea name="meta_value" id="meta-value" class="rd_large"></textarea>
                </td>
            </tr>
            <tr class="odd">
                <td colspan="2">
                    <input type="button" id="rd-addmeta" value="<?php _e('Add custom field','docs'); ?>" />
                </td>
            </tr>
        </table>
        <label><?php _e('Custom fields can be used to add extra metadata to a post that you can use in your theme.','docs'); ?></label>
        </div>
    </div>
    <!-- Extra fields -->
    <?php RMEvents::get()->run_event('docs.sections.form.fields', $edit ? $sec : null); ?>
    <!-- End Extra Fields -->

<label><input type="radio" name="return" value="1" checked="checked" /> <?php _e('Save and return to content','docs'); ?></label>
<label><input type="radio" name="return" value="2" /> <?php _e('Save and return to form','docs'); ?></label>
<label><input type="radio" name="return" value="3" /> <?php _e('Save and return to list','docs'); ?></label>
    
<input type="submit" value="<?php $edit ? _e('Save Changes','docs') : _e('Save Page','docs'); ?>" />
<input type="button" value="<?php _e('Cancel','docs'); ?>" onclick="history.go(-1);" />
<input type="hidden" name="action" value="<?php echo $edit ? 'saveedit' : 'save'; ?>" />
<?php if($edit): ?><input type="hidden" name="id" value="<?php echo $id; ?>" /><?php endif; ?>
<input type="hidden" name="res" value="<?php echo $res->id(); ?>" />
</form>
</div>
