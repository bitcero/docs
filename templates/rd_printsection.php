<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $xoops_langcode; ?>" lang="<?php echo $xoops_langcode; ?>">
<head>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $xoops_charset; ?>" />
<meta http-equiv="content-language" content="<?php echo $xoops_langcode; ?>" />
<title><?php echo $xoops_pagetitle; ?> &raquo; <?php echo $xoops_sitename; ?></title>
<link href="<?php echo XOOPS_URL; ?>/favicon.ico" rel="SHORTCUT ICON" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo XOOPS_URL; ?>/modules/docs/css/print.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo XOOPS_URL; ?>/modules/docs/css/docs.css" />
</head>
<body>
<?php include RMTemplate::get()->get_template('rd_resindextoc.php','module','docs'); ?>
<?php $not_show_top = 1; ?>
<?php foreach($toc as $sec): ?>
    <?php include RMTemplate::get()->get_template('rd_item.php','module','docs'); ?>
<?php endforeach; ?>
</body>
</html>