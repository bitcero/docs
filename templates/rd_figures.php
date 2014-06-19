<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $xoops_langcode; ?>" lang="<?php echo $xoops_langcode; ?>">
<head>
    <meta http-equiv="content-type" content="text/html; charset=<?php echo $xoops_charset; ?>" />
    <meta http-equiv="content-language" content="<?php echo $xoops_langcode; ?>" />
    <title><?php _e('Notes and References','docs'); ?> &raquo; <?php echo $xoops_sitename; ?></title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <?php if ($rmc_config->editor_type == 'tiny'): ?>
        <script type="text/javascript" src="<?php echo XOOPS_URL; ?>/modules/rmcommon/api/editors/tinymce/tiny_mce_popup.js"></script>
    <?php elseif($rmc_config->editor_type=='xoops'): ?>
        <script type="text/javascript" src="<?php echo XOOPS_URL; ?>/modules/rmcommon/api/editors/exmcode/editor-popups.js"></script>
    <?php endif; ?>
    <!-- RMTemplateHeader -->
    <style type="text/css">
        body{
            background:#FFF;
            margin: 10px;

        }
        .outer{
            width: 100%;
        }
    </style>
    <?php if(defined('DF_LOCATION') && DF_LOCATION=='form'): ?>
    <script type="text/javascript">
    <?php include 'include/js/figures-form.js'; ?>
    </script>
    <?php endif; ?>
    <script type="text/javascript">
    $(document).ready(function(){
        setTimeout('closeMessages()',10000);
    });
    function closeMessages(){
        var infos = $(".errorMsg");
        if (infos.length>0)
            $(".errorMsg").slideUp('slow');
                
        var infos = $(".infoMsg");
        if (infos.length>0)
            $(".infoMsg").slideUp('slow');
                
    }
    </script>
</head>
<body>
<?php foreach($rmc_messages as $message): ?>
<div class="<?php if($message['level']): ?>errorMsg<?php else: ?>infoMsg<?php endif; ?>">
    <?php echo html_entity_decode($message['text']); ?>
</div>
<?php endforeach; ?>

<?php if(defined('DF_LOCATION') && DF_LOCATION=='list'): ?>
<div id='nav'>
 <form name="frm" method="post" action="./figures.php">
 <table class="table" cellspacing="1" width="100%">
    <tr>
	    <td>
            <div class="input-group" style="width: 200px;">
                <input type="text" name="search" size="15" value="<?php echo $search; ?>" class="form-control">
                <span class="input-group-btn">
                    <button name="sbtsearch" class="btn btn-default" type="submit"><span class="fa fa-search"></span>&nbsp;</button>
                </span>
            </div>
            <input name="id" value="<?php echo $id; ?>" type="hidden" />
            <input name="page" value="<?php echo $page; ?>" type="hidden" />
	    </td>
        <td align="center">
            <?php $nav->render(false); echo $nav->get_showing(); ?>
        </td>
            <td align="right" class="options_top">
            <ul>
                <li>
                    <a href="javascript:;" id="option-top"><?php _e('Options','docs'); ?></a>
                    <ul>
                        <?php foreach($options as $opt): ?>
                        <li><a title="<?php echo $opt['tip']; ?>" href="<?php echo $opt['href']; ?>"<?php if($opt['attrs']!=''): echo $opt['attrs']; endif; ?>><?php echo $opt['title']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
            </td>
    </tr>
    </table>
  </form>
</div>

<form name="form1" id="frm-figures" method="POST" action="figures.php">
<table class="table table-striped">
    <thead>
    <tr>
        <th colspan="4"><?php _e('Existing Figures','docs'); ?></th>
    </tr>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" name="checkall" id="checkall" onchange="$('#frm-figures').toggleCheckboxes(':not(#checkall)');"/></th>
        <th><?php _e('Id','docs'); ?></th>
        <th><?php _e('Description','docs'); ?></th>
        <th><?php _e('Options','docs'); ?></th>
    </tr>
    </thead>
	<tbody>
    <?php foreach($figures as $figure): ?>
        <tr align="center" class="<?php echo tpl_cycle("even,odd"); ?>">
            <td width="20"><input type="checkbox" name="figs[]" value="<?php echo $figure['id']; ?>" /></td>
            <td><?php echo $figure['id']; ?></td>
            <td align="left"><a href="javascript:;" onclick="editor.insertFigure(<?php echo $figure['id']; ?>);"><?php echo $figure['desc']; ?></a></td>
            <td>
                <a href="#" class="btn btn-info btn-sm" onclick="editor.insertFigure(<?php echo $figure['id']; ?>);"><?php _e('Insert','docs'); ?></a>
                <a class="btn btn-default btn-sm" href="./figures.php?action=edit&amp;id=<?php echo $id; ?>&amp;fig=<?php echo $figure['id']; ?>&amp;page=<?php echo $page; ?>&amp;search=<?php echo $search; ?>"><?php _e('Edit','docs'); ?></a></td>
        </tr>
    <?php endforeach; ?>
	</tbody>

    <tfoot>
    <tr>
        <td colspan="4">
            <?php $nav->display(false); ?>
        </td>
    </tr>
    <tr>
        <td colspan="4" class="text-right">
            <button type="submit" class="btn btn-warning" onclick="return confirm('<?php _e('Do you really wish to delete selected figures?','docs'); ?>');"><?php _e('Delete','docs'); ?></button>
            <button type="button" class="btn btn-danger" onclick="editor.close();"><?php _e('Close Window','docs'); ?></button>
        </td>
    </tr>
    </tfoot>
</table>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input name="action" type="hidden" value="delete" />
<input name="id" value="<?php echo $id; ?>" type="hidden" />
<input name="page" value="<?php echo $page; ?>" type="hidden" />
<input name="search" value="<?php echo $search; ?>" type="hidden" />
</form>
<div id="resources-list" title="<?php _e('Select Document','docs'); ?>"><img src="images/wait.gif" class="image_waiting" alt="<?php _e('Wait a second...','docs'); ?>" /></div>
<?php else: ?>

<h3><?php $edit ? _e('Edit Figure','docs') : _e('Create Figure','docs'); ?></h3>
    <hr>
<div id="form-figures">
    <form name="frmfig" id="frm-figs" method="post" accept="figures.php">
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><?php _e('Document:','docs'); ?></span>
            <span class="form-control">
                <strong><?php echo $resource->getVar('title'); ?></strong>
            </span>
            </div>
        </div>
        <div class="form-group">
            <label for="title"><?php _e('Title:','docs'); ?></label>
            <input type="text" name="title" id="title" size="50" value="<?php echo $edit ? $fig->getVar('title','e') : ''; ?>" class="form-control">
        </div>

        <div class="form-group">
            <label for="desc"><?php _e('Description:','docs'); ?></label>
            <input type="text" name="desc" id="desc" size="50" value="<?php echo $edit ? $fig->getVar('desc','e') : ''; ?>" class="form-control">
            <span class="error_desc error"><?php _e('Description is a required attribute!','docs'); ?></span>
        </div>

        <?php echo $editor->render(); ?>
        <span class="error_content error"><?php _e('You must input the content for this figure!','docs'); ?></span>

        <div class="form-group">
            <label for="attrs"><?php _e('Atributes:','docs'); ?></label>
            <input type="text" name="attrs" id="attrs" value="<?php echo $edit ? $fig->getVar('attrs','e') : htmlspecialchars($xoopsModuleConfig['attrs']); ?>" class="form-control">
            <small class="help-block"><?php _e('Here you can specify another atributes to include with the figure. You can specify a css class name, or an id for figure.','docs'); ?></small>
        </div>

        <input type="hidden" name="action" value="<?php echo $edit ? 'saveedit' : 'save'; ?>" />
        <input type="hidden" name="page" value="<?php echo $page; ?>" />
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <?php if($edit): ?>
        <input type="hidden" name="id_fig" value="<?php echo $id_fig; ?>" />
        <?php endif; ?>
        <?php echo $xoopsSecurity->getTokenHTML(); ?>

        <div class="form-group text-center">
            <button type="button" onclick="history.go(-1);" class="btn btn-default"><?php _e('Cancel','docs'); ?></button>
            <button type="submit" class="btn btn-primary"><?php $edit ? _e('Save Changes','docs') : _e('Create Figure','docs'); ?></button>
        </div>
    </form>
</div>
<?php endif; ?>
</body>

</html>
