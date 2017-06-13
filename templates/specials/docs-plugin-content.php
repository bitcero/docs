<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="icon icon-book"></span>
        <span class="caption"><?php echo $xoopsModule->getVar('name'); ?></span>
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <li>
            <a href="#" data-action="load-remote-dialog" data-url="sections.php?action=link-resources&editor=<?php echo $id; ?>&type=<?php echo $type; ?>" data-window-id="link-dialog">
                <span class="icon icon-link"></span>
                <?php _e('Link existing content', 'docs'); ?>
            </a>
        </li>
        <?php if ( defined('RMCSUBLOCATION') && 'newresource' == RMCSUBLOCATION ): ?>
            <li>
                <a href="#"
                   data-action="load-remote-dialog"
                   data-url="sections.php?action=insert-notes&editor=<?php echo $id; ?>&type=<?php echo $type; ?>&id=<?php echo $id_res; ?>"
                   data-window-id="notes-dialog">
                    <span class="icon icon-pencil"></span>
                    <?php _e('Insert Note', 'docs'); ?>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</div>