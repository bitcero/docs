<h1 class="cu-section-title"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Reviewing Modifications','docs'); ?></h1>

<div class="rd_review_con">
    <div class="rd_original">
        <div class="th"><?php _e('Original Data','docs'); ?></div>
        <div>
        <label><?php _e('Section Title:','docs'); ?></label>
        <span class="title"><a href="<?php echo $section['link']; ?>"><?php echo $section['title']; ?></a></span>
        <label><?php _e('Section Content:','docs'); ?></label>
        <div class="content"><?php echo $section['text']; ?></div>
        </div>
        <br />
            <a href="<?php echo $section['link']; ?>" target="_blank" class="button"><?php _e('View Section','docs'); ?></a>
            <a href="sections.php?sec=<?php echo $section['id']; ?>&amp;id=<?php echo $section['res']; ?>&amp;action=edit" class="button formButton"><?php _e('Edit Section','docs'); ?></a>
    </div>
    
    <div class="rd_new">
        <div class="th"><?php _e('New Data','docs'); ?></div>
        <div>
        <label><?php _e('Section Title:','docs'); ?></label>
        <span class="title"><?php echo $new_content['title']; ?></span>
        <label><?php _e('Section Content:','docs'); ?></label>
        <div class="content"><?php echo $new_content['text']; ?></div>
        </div>
        <br />
        <a href="edits.php?action=approve&amp;ids[]=<?php echo $new_content['id']; ?>" class="button formButton"><?php _e('Approve','docs'); ?></a>
        <a href="edits.php?action=edit&amp;id=<?php echo $new_content['id']; ?>" class="button formButton"><?php _e('Edit','docs'); ?></a>
        <a href="edits.php?action=delete&amp;ids[]=<?php echo $new_content['id']; ?>" class="button formButton"><?php _e('Discard','docs'); ?></a>
    </div>
</div>
