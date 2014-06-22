<?php include RMTemplate::get()->get_template('docs-header.php', 'module', 'docs'); ?>

<h1><?php $edit ? _e('Edit Page','docs') : _e('New Page','docs'); ?></h1>


<form name="frmPage" id="frm-page" method="post" action="<?php echo RMUris::current_url(); ?>">
    <div class="form-group">
        <div class="input-group">
            <label class="input-group-addon"><?php _e('Document','docs'); ?></label>
            <span class="form-control"><strong><?php echo $res->getVar('title'); ?></strong></span>
        </div>
    </div>

    <div class="form-group">
        <input type="text" name="title" id="sec-title" value="<?php echo $edit ? $section->getVar('title') : ''; ?>" class="form-control input-lg" placeholder="<?php _e('Page title', 'docs'); ?>">
    </div>

    <div class="form-group">
        <?php echo $editor->render(); ?>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-sm-6">
                <label for="sec-parent"><?php _e('Parent','docs'); ?></label>
                <select name="parent" id="sec-parent" class="form-control">
                    <option value=""<?php echo $edit && $section->getVar('parent')<=0 ? ' selected="selected"' : ''; ?>><?php _e('Select parent...','docs'); ?></option>
                    <?php foreach($sections as $sec): ?>
                        <option value="<?php echo $sec['id']; ?>"<?php echo $edit && $section->getVar('parent')==$sec['id'] ? ' selected="selected"' : ''; ?>><?php echo str_repeat('&#151;', $sec['jump']); echo $sec['title']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-6">
                <label for="sec-order"><?php _e('Display order','docs'); ?></label>
                <input type="text" name="order" value="<?php echo $edit ? $section->getVar('order') : ''; ?>" class="form-control">
            </div>
        </div>

    </div>

    <hr>

    <h3><?php _e('Custom Fields','docs'); ?></h3>
    <table id="metas-container" class="table table-striped<?php echo !$edit || (!isset($sec) && !$sec->metas()) ? ' hidden' : ''; ?>" cellspacing="0" width="100%" />
    <tr class="head">
        <td width="30%"><strong><?php _e('Name','docs'); ?></strong></td>
        <td><strong><?php _e('Value','docs'); ?></strong></td>
    </tr>
    <?php if($edit || (isset($section) && $section->metas())): ?>
        <?php $i=0;
        foreach($section->metas() as $key => $value): ?>
            <tr>
                <td valign="top">
                    <input type="text" name="metas[<?php echo $i; ?>][key]" id="meta-key-<?php echo $i; ?>" value="<?php echo $key; ?>" class="form-control">
                    <a href="#" onclick="$(this).parents('tr').remove(); return false;"><?php _e('Remove', 'docs'); ?></a>
                </td>
                <td><textarea name="metas[<?php echo $i; ?>][value]" id="metas[<?php echo $i; ?>][value]" class="form-control"><?php echo $value; ?></textarea></td>
            </tr>
            <?php $i++;
        endforeach; ?>
    <?php endif; ?>
    </table>

    <div class="form-group">
        <label><strong><?php _e('Add new field:','docs'); ?></strong></label>
        <table class="table" />
        <tr align="center">
            <th width="30%"><?php _e('Name','docs'); ?></th>
            <th><?php _e('Value','docs'); ?></th>
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
                    <input type="text" name="meta_name" id="meta-name" value="" class="form-control" placeholder="<?php _e('Custom field name...', 'docs'); ?>">
                    <a href="#" class="rd_show_metaname"><?php _e('Enter New','docs'); ?></a>
                    <a href="#" class="rd_hide_metaname" style="display: none;"><?php _e('Cancel','docs'); ?></a>
                <?php else: ?>
                    <input type="text" name="meta_name" id="meta-name" value="" class="form-control" placeholder="<?php _e('Custom field name...', 'docs'); ?>">
                <?php endif; ?>
            </td>
            <td valign="top">
                <label class="error" style="display: none;" id="error-metavalue"><?php _e('Please provide a value for this meta field','docs'); ?></label>
                <textarea name="meta_value" id="meta-value" class="form-control" placeholder="<?php _e('Custom field value...', 'docs'); ?>"></textarea>
            </td>
        </tr>
        <tr class="odd">
            <td colspan="2">
                <button type="button" id="rd-addmeta" class="btn btn-info"><?php _e('Add custom field','docs'); ?></button>
            </td>
        </tr>
        </table>
        <small class="help-block"><?php _e('Custom fields can be used to add extra metadata to a post that you can use in your theme.','docs'); ?></small>
    </div>


    <!-- Extra fields -->
    <?php RMEvents::get()->run_event('docs.sections.form.fields', $edit ? $sec : null); ?>
    <!-- End Extra Fields -->

    <div class="form-group">
        <label class="checkbox-inline"><input type="radio" name="return" value="1" checked="checked" /> <?php _e('Save and return to content','docs'); ?></label>
        <label class="checkbox-inline"><input type="radio" name="return" value="2" /> <?php _e('Save and return to form','docs'); ?></label>
        <label class="checkbox-inline"><input type="radio" name="return" value="3" /> <?php _e('Save and return to list','docs'); ?></label>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-lg"><?php $edit ? _e('Save Changes','docs') : _e('Save Page','docs'); ?></button>
        <button type="button" class="btn btn-default btn-lg" onclick="history.go(-1);"><?php _e('Cancel','docs'); ?></button>
    </div>
    


<input type="hidden" name="action" value="<?php echo $edit ? 'saveedit' : 'save'; ?>" />
<?php if($edit): ?><input type="hidden" name="id" value="<?php echo $id; ?>" /><?php endif; ?>
<input type="hidden" name="res" value="<?php echo $res->id(); ?>" />
</form>

