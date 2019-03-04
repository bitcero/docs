<?php
$references = RMTemplate::get()->get_var('references');
if ($references): ?>
    <hr>
<section class="row" id="notes-and-references">
    <div class="col-xs-12">
        <h4><?php _e('Notes and References', 'docs'); ?></h4>
        <ol>
            <?php $i = 1; foreach ($references as $ref): ?>
                <li id="note-<?php echo $i; ?>"><a href="#top<?php echo $i; ?>">&uarr;</a> <?php echo $ref['text']; ?></li>
                <?php $i++; endforeach; ?>
        </ol>
    </div>
</section>
<?php endif; ?>