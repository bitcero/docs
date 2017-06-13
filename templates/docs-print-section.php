<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $xoops_langcode; ?>" lang="<?php echo $xoops_langcode; ?>">
<head>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $xoops_charset; ?>" />
<meta http-equiv="content-language" content="<?php echo $xoops_langcode; ?>" />
<title><?php echo $xoops_pagetitle; ?> &raquo; <?php echo $xoops_sitename; ?></title>
<link href="<?php echo XOOPS_URL; ?>/favicon.ico" rel="SHORTCUT ICON" />
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo XOOPS_URL; ?>/modules/docs/css/print.css" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo XOOPS_URL; ?>/modules/docs/css/docs.min.css" />
</head>
<body onload="window.print();">
<?php include RMTemplate::get()->get_template('docs-resource-toc.php','module','docs'); ?>
<?php $not_show_top = 1; ?>
<?php foreach($toc as $sec): ?>
    <?php include RMTemplate::get()->get_template('docs-item-section.php','module','docs'); ?>
<?php endforeach; ?>
</body>
</html>