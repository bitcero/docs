<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $xoops_langcode; ?>" lang="<?php echo $xoops_langcode; ?>">
<head>
	<meta http-equiv="content-type" content="text/html; charset=<?php echo $xoops_charset; ?>" />
	<meta http-equiv="content-language" content="<?php echo $xoops_langcode; ?>" />
	<title><?php _e('Notes & References','docs'); ?> &raquo; <?php echo $xoops_sitename; ?></title>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo $theme_css; ?>" />
	<style type="text/css">
		body{
			background:#FFF;
			margin: 10px;

		}
		.outer{
			width: 100%;
		}
	</style>
</head>
<body>
<?php foreach($rmc_messages as $message): ?>
<div class="<?php if($message['level']): ?>errorMsg<?php else: ?>infoMsg<?php endif; ?>">
    <?php echo html_entity_decode($message['text']); ?>
</div>
<?php endforeach; ?>
<div id='nav'>
 <form name="frm" method="POST" action="./references.php">
 <table class="outer" cellspacing="1" width="100%">
        <tr class="even">
	    <td><strong><?php _e('Search','docs'); ?></strong>
		<input type="text" name="search" size="15" value="<?php echo $search; ?>"/>
		<input name="sbtsearch" class="formButton" value="<?php _e('Go','docs'); ?>" type="submit"/>
        <input name="id" value="<?php echo $id; ?>" type="hidden" />
        <input name="section" value="<?php echo $id_sec; ?>" type="hidden" />
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

<form name="frmref" id="frm-notes" method="POST" action="references.php">
<table class='outer' cellspacing="1">
	<tr>
		<th colspan="4"><?php _e('Existing notes and references','docs'); ?></th>
	</tr>
	<tr class="head" align="center">
		<td><input type="checkbox" name="checkall" id="checkall" onchange="$('#frm-notes').toggleCheckboxes(':not(#checkall)');"/></td>
		<td><?php _e('ID','docs'); ?></td>
		<td><?php _e('Title','docs'); ?></td>
		<td><?php _e('Options','docs'); ?></td>
	</tr>
    <?php if(empty($references)): ?>
    <tr clss="even" align="center">
        <td colspan="4">
            <?php _e('Notes was not found.','docs'); ?>
            <?php if($id<=0): ?>
                <br />
                <a href="javascript:;" onclick="docsAjax.getSectionsList(1);"><?php _e('Select Document first','docs'); ?></a>
            <?php else: ?>
                <br />
                <a href="javascript:;" onclick="docsAjax.displayForm();"><?php _e('Create new note','docs'); ?></a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endif; ?>
	<?php foreach($references as $ref): ?>
	<tr align="center" valign="top" class="<?php echo tpl_cycle("even,odd"); ?>">
		<td width="20" align="center"><input type="checkbox" name="refs[]" value="<?php echo $ref['id']; ?>" /></td>
		<td><?php echo $ref['id']; ?></td>
		<td align="left">
            <strong><a href="javascript:;" onclick="editor.insertNote(<?php echo $ref['id']; ?>);"><?php echo $ref['title']; ?></a><br /></strong>
            <?php echo $ref['content']; ?>
        </td>
		<td nowrap="nowrap"><a href="javascript:;" onclick="editor.insertNote(<?php echo $ref['id']; ?>);"><?php _e('Insert','docs'); ?></a> |
		<a href="javascript:;" onclick="docsAjax.editNote(<?php echo $ref['id']; ?>);"><?php _e('Edit','docs'); ?></a></td>
	</tr>
	<?php endforeach; ?>
	<tr class="foot">
		<td colspan="4">
		<input name="delete" class="formButton" type="submit" value="<?php _e('Delete','docs'); ?>" onclick="return confirm('<?php _e('Do you really wish to delet selected notes?','docs'); ?>');" />
		<input name="close" class="formButton" type="button" value="<?php _e('Close','docs'); ?>" onclick="editor.close();" />
		<?php $nav->display(false); ?></td>
	</tr>
</table>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input name="action" type="hidden" value="delete" />
<input name="id" value="<?php echo $id; ?>" type="hidden" />
<input name="page" value="<?php echo $page; ?>" type="hidden" />
<input name="search" value="<?php echo $search; ?>" type="hidden" />
</form>

<?php echo $other_content; ?>

<div id="resources-list" title="<?php _e('Select Document','docs'); ?>"><img src="images/wait.gif" class="image_waiting" alt="<?php _e('Wait a second...','docs'); ?>" /></div>
<div id="resources-form" title="<?php _e('Create Note','docs'); ?>">
<form name="frmRefs" id="frm-notes" method="post" action="references.php">
<label><?php _e('Title:','docs'); ?></label>
<input type="text" name="title" id="note-title" value="" class="required" />
<label><?php _e('Content:','docs'); ?></label>
<textarea name="reference" id="note-content" cols="45" rows="6" class="required"></textarea>
<input type="hidden" name="action" value="save" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<input type="hidden" name="search" value="<?php echo $search; ?>" />
<input type="hidden" name="name" value="<?php echo rmc_server_var($_GET,'name',''); ?>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
<img src="images/wait.gif" class="image_waiting" alt="<?php _e('Wait a second...','docs'); ?>" />
</div>
</body>
</html>
