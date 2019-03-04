<h1 class="cu-section-title"><span style="background-position: left -32px;">&nbsp;</span><?php echo __('Home Page Editor', 'docs'); ?></h1>
<div class="descriptions">
<?php _e('You can edit and customize the home page for RapidDocs.', 'docs'); ?>

<?php _e('Add your own content and include Documents to integrate the home page with your site.', 'docs'); ?>
<br><br>
<form name="frmHome" method="post" action="hpage.php">
    <?php echo $editor->render(); ?>
    <br>
    <input type="hidden" name="action" value="save">
    <input type="submit" value=" &nbsp; <?php _e('Save Page', 'docs'); ?> &nbsp; ">
</form>
</div>
