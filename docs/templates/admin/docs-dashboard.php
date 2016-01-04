<h1 class="cu-section-title"><?php _e('Dashboard','docs'); ?></h1>
<?php if(!empty($top_data)): ?>
<script type="text/javascript">
    
    function make_top_resources(){
        var api = new jGCharts.Api();         
        var opt = {
            data : [[<?php echo implode(",", $top_data['reads']); ?>]],//mandatory 
            axis_labels: ['<?php _e('Documents by popularity','docs'); ?>'],
            size : $("#rd-top-resources").width()+'x300',//default 300x200 (width x height) - maximum size 300,000 pixels};//set options
            bar_width: 15,
            bar_spacing: parseInt(($("#rd-top-resources").width()-<?php echo (count($top_data['reads'])*15); ?>)/<?php echo count($top_data['reads']); ?>),
            title_size: 20,
            legend: ['<?php echo implode("','", $top_data['names']); ?>'],
            legend_position: 'b',
            grid: true,
            grid_x: 0,
            grid_y: 10
        }
        $("#rd-top-resources").html('');
        jQuery('<img>')
        .attr('src', api.make(opt))//options  
        .appendTo("#rd-top-resources"); 
    }
    
    function make_comm_resources(){
        var api = new jGCharts.Api();         
        var opt = {
            data : [[<?php echo implode(",", $comm_data['comments']); ?>]],//mandatory 
            axis_labels: ['<?php _e('Sections by comments','docs'); ?>'],
            size : $("#rd-comms-resources").width()+'x300',//default 300x200 (width x height) - maximum size 300,000 pixels};//set options
            bar_width: 15,
            bar_spacing: parseInt(($("#rd-comms-resources").width()-<?php echo (count($comm_data['comments'])*15); ?>)/<?php echo count($comm_data['comments']); ?>),
            title_size: 20,
            legend: ['<?php echo implode("','", $comm_data['names']); ?>'],
            legend_position: 'b',
            grid: true,
            grid_x: 0,
            grid_y: 10
        }
        $("#rd-comms-resources").html('');
        jQuery('<img>')
        .attr('src', api.make(opt))//options  
        .appendTo("#rd-comms-resources"); 
    }
    
    $(document).ready(function(){
        make_top_resources();
        make_comm_resources();
        
        $(window).resize(function(){
            make_top_resources();
            make_comm_resources();
        });
        
    });
    
</script>
<?php endif; ?>

<div class="row" data-news="load" data-boxes="load" data-module="docs" data-target="#docs-news" data-container="dashboard" data-box="docs-dashboard">

    <div class="size-1" data-dashboard="item">

        <!-- Resume -->
        <div class="cu-box box-primary">
            <div class="box-header">
                <h3 class="box-title"><?php _e('Documentor Resume','docs'); ?></h3>
            </div>
            <div id="box-content">

                <table class="table">
                    <tr>
                        <td>
                            <a href="resources.php"><?php echo sprintf(__('%s Documents','docs'), '<strong>'.$resume_data['resources'].'</strong>'); ?></a>
                        </td>
                        <td>
                            <?php echo sprintf(__('%s Sections','docs'), '<strong>'.$resume_data['sections'].'</strong>'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo sprintf(__('%s Figures','docs'), '<strong>'.$resume_data['figures'].'</strong>'); ?>
                        </td>
                        <td>
                            <?php echo sprintf(__('%s Notes & References','docs'), '<strong>'.$resume_data['notes'].'</strong>'); ?>
                        </td>
                    </tr>
                </table>

            </div>
        </div>
        <!-- End Resume -->

    </div>

    <div class="size-1" data-dashboard="item">
        <!-- Waiting -->
        <div class="cu-box">
            <div class="box-header">
                <span class="fa fa-caret-up box-handler"></span>
                <h3 class="box-title"><?php _e('Documents waiting for approval','docs'); ?></h3>
            </div>
            <div class="box-content">

                <?php if ( empty( $noapproved) ): ?>
                    <span class="text-info">
                    <?php _e('There are not content waiting for approval', 'docs'); ?>
                </span>
                <?php endif; ?>
                <ul class="list-group">
                    <?php foreach($noapproved as $res): ?>
                        <li class="list-group-item">
                            <a href="resources.php?action=edit&amp;id=<?php echo $res['id']; ?>"><?php echo $res['title']; ?></a><br />
                            <?php echo $res['desc']; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        </div>
        <!-- End Wating -->
    </div>

    <div class="size-1" data-dashboard="item">
        <!-- Drafts -->
        <div class="cu-box">
            <div class="box-header">
                <span class="fa fa-caret-up box-handler"></span>
                <h3 class="box-title"><?php _e('Drafts','docs'); ?></h3>
            </div>
            <div class="box-content">
                <?php if ( empty( $nopublished) ): ?>
                    <span class="text-info">
                    <?php _e('There are not drafts', 'docs'); ?>
                </span>
                <?php endif; ?>
                <ul class="list-group">
                    <?php foreach($nopublished as $res): ?>
                        <li class="list-group-item">
                            <a href="resources.php?action=edit&amp;id=<?php echo $res['id']; ?>"><?php echo $res['title']; ?></a>
                            <small class="help-block"><?php echo $res['desc']; ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <!-- End Drafts -->
    </div>

    <div class="size-2" data-dashboard="item">
        <!-- Top Documents -->
        <div class="cu-box box-blue-grey">
            <div class="box-header">
                <span class="fa fa-caret-up box-handler"></span>
                <h3 class="box-title"><?php _e('Top Documents','docs'); ?></h3>
            </div>
            <div class="box-content">
                <div id="rd-top-resources"><?php if(empty($top_data)): ?><?php _e('There are not documents to show here.','docs'); ?><?php else: ?>&nbsp;<?php endif; ?></div>
            </div>
        </div>
        <!-- End Top Documents -->
    </div>

    <div class="size-1" data-dashboard="item">
        <div class="cu-box">
            <div class="box-header">
                <span class="fa fa-caret-up box-handler"></span>
                <h3 class="box-title"><?php _e('Documentor News', 'docs'); ?></h3>
            </div>
            <div class="box-content" id="docs-news">

            </div>
        </div>
    </div>

</div>
