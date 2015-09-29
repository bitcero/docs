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
    global $res, $xoopsUser, $xoopsSecurity;
    
    $ret['title'] = __('Section Options','docs');
    
    ob_start();
?>
<div class="docs-widget-form">
    <form name="frmoptions" method="post" onsubmit="return false;">
        <div class="form-group">
            <label>
                <input type="checkbox" name="return" id="wreturn" value="1" checked="checked" onchange="$(this).is(':checked')?$('#secreturn').val(1):$('#secreturn').val(0);" />
                <?php _e('Save and return','docs'); ?>
            </label>
        </div>

        <div class="form-group">
            <input type="submit" value="<?php _e('Save Section','docs'); ?>" onclick="$('#frm-section').submit();" />
            <input type="button" value="<?php _e('Discard Section','docs'); ?>" onclick="window.location.href = 'sections.php?id=<?php echo $res->id(); ?>';" />
        </div>

    </form>
</div>
<?php
    $ret['content'] = ob_get_clean();
    return $ret;
}
