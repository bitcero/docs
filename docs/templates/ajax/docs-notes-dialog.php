<div class="dialog-commands"
     data-editor="<?php echo $editor_id; ?>"
     data-type="<?php echo $editor_type; ?>"
     data-id="<?php echo $res->id(); ?>">
    <div class="row">
        <div class="col-sm-6">
            <div class="input-group search-box">
                <input type="text" class="form-control" value="<?php echo $search; ?>" placeholder="<?php _e('Search for notes...', 'docs'); ?>">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <span class="fa fa-search"></span>
                    </button>
                </span>
            </div>
        </div>
        <div class="col-sm-6">
            <a href="#" class="btn btn-default show-creator">
                <span class="icon icon-plus text-success"></span>
                <?php _e('Create Note', 'docs'); ?>
            </a>
        </div>
    </div>
</div>

<div class="notes-list">
    <div class="table-responsive">
        <table class="table">
            <tbody>
            <?php if (empty($notes)): ?>
                <tr>
                    <td class="text-info text-center">
                        <?php _e('There are not notes or references for this book.', 'docs'); ?>
                    </td>
                </tr>
            <?php endif; ?>
            <?php foreach ($notes as $note): ?>
                <tr>
                    <td class="text-right">
                        <strong><?php echo $note['id']; ?></strong>
                    </td>
                    <td>
                        <?php echo $note['text']; ?>
                    </td>
                    <td>
                        <a href="#" class="btn btn-default btn-sm insert-note" data-id="<?php echo $note['id']; ?>">
                            <span class="icon icon-share"></span>
                            <?php _e('Insert', 'docs'); ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php echo $nav->render(); ?>

<div class="notes-creator cu-titlebar" data-id="<?php echo $res->id(); ?>">
    <div class="form-group">
        <label for="notes-note"><?php _e('Note content', 'docs'); ?></label>
        <textarea class="form-control note-content" id="notes-note" name="note_content" rows="3"></textarea>
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-info create-note"><?php _e('Create & Insert', 'docs'); ?></button>
        <button type="button" class="btn btn-default"><?php _e('Cancel', 'docs'); ?></button>
    </div>
</div>