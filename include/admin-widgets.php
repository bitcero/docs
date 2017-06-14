<?php
// $Id: admin_widgets.php 869 2011-12-22 08:50:24Z i.bitcero $
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This file contains the functions for all widgets
* that will be shown in admin section
*/

load_mod_locale('docs');

/**
* Show the options widget
*/
function rd_widget_options(){
    global $res, $common, $xoopsSecurity;
    
    $ret['title'] = __('Section Options','docs');

    $id =$common->httpRequest()->get('sec', 'integer', 0);
    if($id > 0){
        $sec = new RDSection($id);
    }
    
    ob_start();
?>
<div class="docs-widget-form">
    <form name="frmoptions" method="post" onsubmit="return false;">

        <div class="form-group">
            <label>
                <input type="checkbox" name="single" id="single" value="on" onchange="$(this).is(':checked')?$('#secretsingle').val('on'):$('#secretsingle').val('off');" <?php echo $sec && $sec->single > 0 ? ' checked' : ''; ?>>
                <?php _e('Display as sinlge page','docs'); ?>
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="return" id="wreturn" value="1" checked="checked" onchange="$(this).is(':checked')?$('#secreturn').val(1):$('#secreturn').val(0);" />
                <?php _e('Save and return','docs'); ?>
            </label>
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="<?php _e('Save','docs'); ?>" onclick="$('#frm-section').submit();" />
            <input type="button" class="btn btn-default" value="<?php _e('Discard','docs'); ?>" onclick="window.location.href = 'sections.php?id=<?php echo $res->id(); ?>';" />
        </div>

    </form>
</div>
<?php
    $ret['content'] = ob_get_clean();
    return $ret;
}


function doc_widget_index()
{
    global $common, $res;

    $ret = [];
    $ret['title'] = __('Sections Index', 'docs');

    $id =$common->httpRequest()->get('sec', 'integer', 0);
    if($id > 0){
        $sec = new RDSection($id);
    }

    $index = [];
    RDFunctions::sections_tree_index(0, 0, $res, '', '', false, $index);

    ob_start(); ?>

    <div class="doc-widget-index">
        <small class="help-block">
            <?php _e('Click on any section listed below in order to edit it.', 'docs'); ?>
        </small>
        <ul>
            <?php foreach($index as $section): ?>
                <li<?php echo $section['id'] == $id ? ' class="active"' : ''; ?>>
                    <a href="sections.php?action=edit&sec=<?php echo $section['id']; ?>&id=<?php echo $res->id(); ?>">
                        <?php echo $section['number']; ?>.
                        <?php if($section['jump'] == 0): ?>
                            <strong><?php echo $section['title']; ?></strong>
                        <?php else: ?>
                            <?php echo $section['title']; ?>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php
    $ret['content'] = ob_get_clean();
    return $ret;
}