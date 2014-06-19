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
<div class="rd_widget_form">
    <form name="frmoptions" method="post" onsubmit="return false;">
    <label>
        <input type="checkbox" name="return" id="wreturn" value="1" checked="checked" onchange="$(this).is(':checked')?$('#secreturn').val(1):$('#secreturn').val(0);" />
        <?php _e('Save and return','docs'); ?>
    </label>
    <input type="submit" value="<?php _e('Save Section','docs'); ?>" onclick="$('#frm-section').submit();" />
    <input type="button" value="<?php _e('Discard Section','docs'); ?>" onclick="window.location.href = 'sections.php?id=<?php echo $res->id(); ?>';" />
    </form>
</div>
<?php
    $ret['content'] = ob_get_clean();
    return $ret;
}

/**
* Show the references created for a Document
*/
function rd_widget_references(){
    global $res, $rmc_config;
    
    $ret['title'] = __('Notes &amp; References','docs');
    $count = 0;
    $references = RDFunctions::references($res->id(), $count, '',0,6);

    $nav = new RMPageNav($count, 6, 1, 3);
    $nav->target_url('javascript:;" onclick="docsAjax.getNotes('.$res->id().',6,{PAGE_NUM},\'rd-wd-references\')');
    RMTemplate::get()->add_script('scripts.php?file=ajax.js', 'docs');

    ob_start();
?>
<div id="rd-wd-references">
    <ul>
    <?php
        if(count($references)<=0) _e('There are not references for this Document yet!','docs');
        foreach($references as $ref):
    ?>
        <li><a href="javascript:;" onclick="docsAjax.insertIntoEditor('[note:<?php echo $ref['id']; ?>]','<?php echo $rmc_config->editor_type; ?>');"><?php echo $ref['title']; ?></a></li>
    <?php
        endforeach;
    ?>
    </ul>
    <?php $nav->display(false); ?>
</div>
<?php
    $ret['content'] = ob_get_clean();
    return $ret;
    
}

/**
* Shows the figures for a Document
*/
function rd_widget_figures(){
    global $res, $rmc_config;
    
    $ret['title'] = __('Document Figures','docs');
    $count = 0;
    $figures = RDFunctions::figures($res->id(), $count, '',0,6);

    $nav = new RMPageNav($count, 6, 1, 3);
    $nav->target_url('javascript:;" onclick="docsAjax.getFigures('.$res->id().',6,{PAGE_NUM},\'rd-wd-figures\')');
    RMTemplate::get()->add_script('scripts.php?file=ajax.js', 'docs');

    ob_start();
?>
<div id="rd-wd-figures">
    <ul>
    <?php
        if(count($figures)<=0) _e('There are not figures for this Document yet!','docs');
        foreach($figures as $fig):
    ?>
        <li><a href="javascript:;" onclick="docsAjax.insertIntoEditor('[figure:<?php echo $fig['id']; ?>]','<?php echo $rmc_config->editor_type; ?>');"><?php echo $fig['title']; ?></a></li>
    <?php
        endforeach;
    ?>
    </ul>
    <?php $nav->display(false); ?>
</div>
<?php
    $ret['content'] = ob_get_clean();
    return $ret;
    
}

/**
* Show form to create new note
*/
function rd_widget_newnote(){
    global $xoopsSecurity;
    
    $id_res = RMHttpRequest::get( 'res', 'integer', 0 );
    if($id_res<=0) return null;
    $rtn['title'] = __('New Note','docs');
    ob_start();
?>
<form name="frmNewNote" id="frm-wnotes" method="post" action="notes.php">
    <div class="form-group">
        <label for="note-title"><?php _e('Title:','docs'); ?></label>
        <input type="text" name="title" id="note-title" value="" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="note-content"><?php _e('Content:','docs'); ?></label>
        <textarea name="reference" id="note-content" cols="45" rows="6" class="form-control" required></textarea>
    </div>
    <div class="form-group text-center">
        <button type="submit" class="btn btn-primary"><span class="fa fa-check"></span> <?php _e('Create Note','docs'); ?></button>
    </div>

    <input type="hidden" name="action" value="save">
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
    <input type="hidden" name="action" value="save">
    <input type="hidden" name="res" value="<?php echo $id_res; ?>">
    <?php RMEvents::get()->run_event('docs.notes.form.fields'); ?>

</form>

<?php
    $rtn['content'] = ob_get_clean();
    return $rtn;
}