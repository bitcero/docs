<div class="the-books">
    <div class="input-group">
        <input type="text" class="form-control" name="dialog_uname" id="dialog-uname"
               value="@<?php echo '' != $user ? $user : $xoopsUser->uname(); ?>"
               title="<?php _e('Owner', 'docs'); ?>">
            <span class="input-group-btn">
                <button type="button" class="btn btn-default" id="dialog-search">
                    <span class="fa fa-search"></span>
                </button>
            </span>
    </div>
    <div class="books-list">
        <?php if (empty($books)): ?>
            <div class="text-center text-info">
                <?php _e('This user does not have any book currently. Please try another user.', 'docs'); ?>
            </div>
        <?php else: ?>
        <ul>
            <?php foreach ($books as $book): ?>
                <li>
                    <a href="#" data-id="<?php echo $book['id']; ?>" data-owner="<?php echo $user; ?>">
                        <span class="<?php echo $res && $res->id() == $book['id'] ? 'icon icon-circle-right' : 'icon icon-book'; ?>"></span>
                        <?php echo $book['title']; ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <ul>
                <?php endif; ?>
    </div>
</div>
<div class="sections-list" data-editor="<?php echo $editor_id; ?>" data-type="<?php echo $editor_type; ?>">
        <span class="the-title">
            <?php _e('Sections in Book', 'docs'); ?>
        </span>
        <div class="the-list">
            <?php if(empty($sections)): ?>
                <?php if($res): ?>
                    <span class="label label-warning"><?php _e('There are not sections in this book', 'docs'); ?></span>
                <?php else: ?>
                    <span class="label label-info"><?php _e('You have not selected a book to show sections in it', 'docs'); ?></span>
                <?php endif; ?>
            <?php else: ?>

                <ul>
                    <?php foreach($sections as $section): ?>
                    <li style="padding-left: <?php echo ($section['jump'] * 13); ?>px;">
                        <a href="#"
                           data-title="<?php echo $section['title']; ?>"
                           data-link="<?php echo RMUris::relative_url($section['link']); ?>"<?php if($section['jump'] == 0): ?> class="root"<?php endif; ?>>
                            <span class="icon icon-forward pull-right"></span>
                            <?php echo $section['number']; ?>.
                            <?php if($section['jump'] == 0): ?>
                                <strong><?php echo $section['title']; ?></strong>
                            <?php else: ?>
                                <?php echo $section['title']; ?>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>

            <?php endif; ?>
        </div>
</div>
