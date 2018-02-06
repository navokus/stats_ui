<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 22/06/2016
 * Time: 11:19
 */
?>
<script type="text/javascript">
	var timing =<?php echo "'".$body ['selected_tab'] ."'" ?>;
	var api_url = <?php echo "'".$body ['api_url'] ."'" ?>;
</script>
<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<?php foreach ($body ['tabs'] as $key => $value ):?>
			<?php
				$class=""; 
				
			if($body ['selected_tab']==$value){
				$class="class=\"active\"";
			} ?>
			 <li <?php echo $class ?>><a href="#tab_t<?php echo $value ?>" data-toggle="tab" data-id="<?php echo $value ?>" class="dbrclk"><?php echo $value ?></a></li>
		<?php endforeach;?>
		<li class="pull-right"><!-- <a href="<?php echo site_url('kpi/compare'); ?>" class="text-green"><i class="fa fa-line-chart"> view more</i></a> --></li>
	</ul>

	<div class="tab-content">
		<?php foreach ($body ['tabs'] as $key => $value ):?>
			<?php
				$class="class=\"tab-pane\"";
				
			if($body ['selected_tab'] ==$value){
				$class="class=\"tab-pane active\"";
			} ?>
			 <div <?php echo $class ?> id="tab_t<?php echo $value ?>">
			 	<div id='loading_<?php echo $value ?>' style='display:none'>
				  <img src='<?php echo base_url('public/frontend/dist/img/loading-animation.gif'); ?>'/>
				</div>
			 </div>
		<?php endforeach;?>
	</div>
	<script type="text/javascript">
	    $(document).ready(function() {
	    	renderDashboard2(timing);
	    });
	    	    
</script>
</div>