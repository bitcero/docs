<h1 class="cu-section-title"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Dashboard','docs'); ?></h1>
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
<div class="rd_rightleft">
    <div class="rd_bk_left">
        
        <!-- Resume -->
        <div class="outer">
            <div class="th"><?php _e('RapidDocs Resume','docs'); ?></div>
            <div id="rd-resume-data">
                <div class="left"><a href="resources.php"><?php echo sprintf(__('%s Documents','docs'), '<strong>'.$resume_data['resources'].'</strong>'); ?></a></div>
                <div class="right"><?php echo sprintf(__('%s Sections','docs'), '<strong>'.$resume_data['sections'].'</strong>'); ?></div>
                <span class="clearboth"></span>
                <div class="left"><?php echo sprintf(__('%s Figures','docs'), '<strong>'.$resume_data['figures'].'</strong>'); ?></div>
                <div class="right"><?php echo sprintf(__('%s Notes & References','docs'), '<strong>'.$resume_data['notes'].'</strong>'); ?></div>
            </div>
        </div>
        <!-- End Resume -->
        <br />
        <!-- Drafts -->
        <div class="outer">
            <div class="th"><?php _e('Documents waiting for approval','docs'); ?></div>
            <ul class="rd_listres">
            <?php foreach($noapproved as $res): ?>
                <li class="<?php echo tpl_cycle("even,odd"); ?>">
                    <a href="resources.php?action=edit&amp;id=<?php echo $res['id']; ?>"><?php echo $res['title']; ?></a><br />
                    <?php echo $res['desc']; ?>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
        <!-- End Drafts -->
        <br />
        <!-- Drafts -->
        <div class="outer">
            <div class="th"><?php _e('No Published Documents','docs'); ?></div>
            <ul class="rd_listres">
            <?php foreach($nopublished as $res): ?>
                <li class="<?php echo tpl_cycle("even,odd"); ?>">
                    <a href="resources.php?action=edit&amp;id=<?php echo $res['id']; ?>"><?php echo $res['title']; ?></a><br />
                    <?php echo $res['desc']; ?>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
        <!-- End Drafts -->
        <br />
        
        <?php echo RMEvents::get()->run_event('docs.dashboard.left.blocks'); ?>
        
    </div>
    <div class="rd_bk_right">
        
        <!-- Top Documents -->
        <div class="outer">
            <div class="th"><?php _e('Top Documents','docs'); ?></div>
            <div class="even">
                <div id="rd-top-resources"><?php if(empty($top_data)): ?><?php _e('There are not documents to show here.','docs'); ?><?php else: ?>&nbsp;<?php endif; ?></div>
            </div>
        </div>
        <!-- End Top Documents -->
        <br />
        <div class="outer">
            <div class="th"><img src="../images/loading.gif" class="rd_loading_image" /> <?php _e('RapidDocs News','docs'); ?></div>
            <div id="rd-news">
            
            </div>
        </div>
        <br />
        <?php echo RMEvents::get()->run_event('docs.dashboard.right.blocks'); ?>
        
    </div>
</div>