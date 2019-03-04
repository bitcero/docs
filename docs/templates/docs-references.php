<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $xoops_langcode; ?>" lang="<?php echo $xoops_langcode; ?>">
<head>
	<meta http-equiv="content-type" content="text/html; charset=<?php echo $xoops_charset; ?>">
	<meta http-equiv="content-language" content="<?php echo $xoops_langcode; ?>">
	<title><?php _e('Notes & References', 'docs'); ?> &raquo; <?php echo $xoops_sitename; ?></title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <?php if ('tiny' == $rmc_config->editor_type): ?>
    <script type="text/javascript" src="<?php echo XOOPS_URL; ?>/modules/rmcommon/api/editors/tinymce/tiny_mce_popup.js"></script>
    <?php elseif ('xoops' == $rmc_config->editor_type): ?>
    <script type="text/javascript" src="<?php echo XOOPS_URL; ?>/modules/rmcommon/api/editors/exmcode/editor-popups.js"></script>
    <?php endif; ?>
</head>
<body>
<?php foreach ($rmc_messages as $message): ?>
<div class="<?php if ($message['level']): ?>errorMsg<?php else: ?>infoMsg<?php endif; ?>">
    <?php echo html_entity_decode($message['text']); ?>
</div>
<?php endforeach; ?>
<div id='nav'>
 <form name="frm" method="POST" action="./references.php">
 <table class="table">
        <tr class="even">
	    <td>

            <div class="input-group" style="width: 200px;">
                <input type="text" name="search" class="form-control" size="15" value="<?php echo $search; ?>">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit"><span class="fa fa-search"></span>&nbsp;</button>
                </span>
            </div>

        <input name="id" value="<?php echo $id; ?>" type="hidden">
        <input name="section" value="<?php echo $id_sec; ?>" type="hidden">
        <input name="page" value="<?php echo $page; ?>" type="hidden">    
	    </td>
        <td align="center" valign="middle">
            <?php $nav->render(false); echo $nav->get_showing(); ?>
        </td>
            <td align="right" class="options_top">
            <ul>
                <li>
                    <a href="javascript:;" id="option-top"><?php _e('Options', 'docs'); ?></a>
                    <ul>
                        <?php foreach ($options as $opt): ?>
                        <li><a title="<?php echo $opt['tip']; ?>" href="<?php echo $opt['href']; ?>"<?php if ('' != $opt['attrs']): echo $opt['attrs']; endif; ?>><?php echo $opt['title']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
            </td>
        </tr>
    </table>
  </form>
</div>

<form name="frmref" id="frm-notes" method="POST" action="references.php">
<table class='table table-striped'>
    <thead>
    <tr>
        <th colspan="4"><?php _e('Existing notes and references', 'docs'); ?></th>
    </tr>
    <tr class="head" align="center">
        <th><input type="checkbox" name="checkall" id="checkall" onchange="$('#frm-notes').toggleCheckboxes(':not(#checkall)');"></th>
        <th><?php _e('ID', 'docs'); ?></th>
        <th><?php _e('Title', 'docs'); ?></th>
        <th><?php _e('Options', 'docs'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($references)): ?>
        <tr clss="even" align="center">
            <td colspan="4">
                <span class="text-warning"><?php _e('No notes was not found.', 'docs'); ?></span>
                <?php if ($id <= 0): ?>
                    <br>
                    <a href="javascript:;" onclick="docsAjax.getSectionsList(1);"><?php _e('Select Document first', 'docs'); ?></a>
                <?php else: ?>
                    <br>
                    <a href="javascript:;" onclick="docsAjax.displayForm();"><?php _e('Create new note', 'docs'); ?></a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endif; ?>
    </tbody>

	<?php foreach ($references as $ref): ?>
	<tr align="center" valign="top" class="<?php echo tpl_cycle('even,odd'); ?>">
		<td width="20" align="center"><input type="checkbox" name="refs[]" value="<?php echo $ref['id']; ?>"></td>
		<td><?php echo $ref['id']; ?></td>
		<td align="left">
            <strong><a href="javascript:;" onclick="editor.insertNote(<?php echo $ref['id']; ?>);"><?php echo $ref['title']; ?></a><br></strong>
            <?php echo $ref['content']; ?>
        </td>
		<td nowrap="nowrap"><a href="javascript:;" onclick="editor.insertNote(<?php echo $ref['id']; ?>);" class="btn btn-info btn-sm"><?php _e('Insert', 'docs'); ?></a>
		<a href="javascript:;" onclick="docsAjax.editNote(<?php echo $ref['id']; ?>);" class="btn btn-default btn-sm"><?php _e('Edit', 'docs'); ?></a></td>
	</tr>
	<?php endforeach; ?>
	<tr class="foot">
		<td colspan="4">
            <button class="btn btn-danger" type="submit" onclick="return confirm('<?php _e('Do you really wish to delet selected notes?', 'docs'); ?>');"><?php _e('Delete', 'docs'); ?></button>
            <button class="btn btn-warning" type="button" onclick="editor.close();"><?php _e('Close', 'docs'); ?></button>
		<?php $nav->display(false); ?></td>
	</tr>
</table>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input name="action" type="hidden" value="delete">
<input name="id" value="<?php echo $id; ?>" type="hidden">
<input name="page" value="<?php echo $page; ?>" type="hidden">
<input name="search" value="<?php echo $search; ?>" type="hidden">
</form>

<?php echo $other_content; ?>

<div id="resources-list" title="<?php _e('Select Document', 'docs'); ?>"><img src="images/wait.gif" class="image_waiting" alt="<?php _e('Wait a second...', 'docs'); ?>"></div>
<div id="resources-form" title="<?php _e('Create Note', 'docs'); ?>">
<form name="frmRefs" id="frm-notes-add" method="post" action="references.php">
<label><?php _e('Title:', 'docs'); ?></label>
<input type="text" name="title" id="note-title" value="" class="required">
<label><?php _e('Content:', 'docs'); ?></label>
<textarea name="reference" id="note-content" cols="45" rows="6" class="required"></textarea>
<input type="hidden" name="action" value="save">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="search" value="<?php echo $search; ?>">
<input type="hidden" name="name" value="<?php echo rmc_server_var($_GET, 'name', ''); ?>">
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
<img src="images/wait.gif" class="image_waiting" alt="<?php _e('Wait a second...', 'docs'); ?>">
</div>
</body>
</html>
