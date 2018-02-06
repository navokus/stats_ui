<link href="<?php echo base_url('public/frontend/plugins/retention-graph/css/retention-graph.css'); ?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('public/frontend/plugins/retention-graph/js/moment.js'); ?>"></script>
<script src="<?php echo base_url('public/frontend/plugins/retention-graph/js/retention-graph.js'); ?>"></script>
<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active tab-selection"><a href="#tab_user" data-toggle="tab" data-id="user">User Retention</a></li>
		<!-- <li class="tab-selection"><a href="#tab_payment" data-toggle="tab" data-id="revenue">Payment Retention</a></li> -->
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="tab_user">
			<div class="row">
				<div class="col-md-12">
					<?php
					if(count($data[$timing]) > 0){
						echo '<div id="demo"></div>';
					}else{
						$html = $this->load->view("body_parts/contact", null, TRUE);
						echo '<div class="row">' . $html . '</div>';
					}
        			?>
				</div>								
			</div>
		</div>
		<div class="tab-pane" id="tab_payment">
			<div class="col-md-12">
				
			</div>
		</div>
	</div>
	<!-- /.tab-content -->
	<div class="clearfix"></div>
</div>
<script>

var options = {
		data : {
			<?php
			    if($timing == "4"){
			    	echo "days: {";
			    }else if($timing == "17"){
			    	echo "days: {}, ";
			    	echo "weeks: {";
			    }else{
			    	echo "days: {}, ";
			    	echo "weeks: {}, ";
			    	echo "months: {";	
			    }
			    
			    /*foreach ($data[$timing] as $firstDate => $retentionArr){
			    	echo "'" . $firstDate . "': [";
		    		foreach($retentionArr as $date => $retention){
		    			echo $retention . ",";
		    		}
			    	echo "],\n";
			    }*/
			    $start = 0;
			    $numOfDate  = count($days);
			    foreach($days as $date){
			    	
			    	//echo "'" . $this->util->get_xcolumn_by_timming($date, $timing, true) . "': [";
			    	echo "'" . $date . "': [";
			    	
			    	for($i = $start; $i < $numOfDate; $i++){
			    		
			    		$value = $data[$timing][$date][$days[$i]];
			    		
			    		if($value > 0 ){
			    			echo $value . ",";
			    		}else {
			    			echo "0,";
			    		}
			    	}
	
			    	echo "],\n";
			    	$start += 1;
			    }
			    
			    echo "}"
		     ?>
	    },
	    inputDateFormat : "YYYY-MM-DD",
	    dateDisplayFormat : "DD-MMM-YYYY",
	    title : "",
	    /* cellClickEvent : function(date, day){
	        alert("date=" + date + "&day="+ day);
	    }, */
	    enableInactive: true,
	    /* dayClickEvent : function(day, startDate, endDate){
	        alert(day + "start" + startDate + "end" + endDate);
	    }, */
	    retentionDays : <?php echo count($days) - 1?>,
	    retentionWeeks : <?php echo count($days) - 1?>,
	    retentionMonths : <?php echo count($days) - 1?>,
	    enableDateRange: false,
	    showAbsolute : false,
	    toggleValues : true,
	    retentionType: "<?php if($timing == '4') echo 'Day'; else if ($timing == '17') echo 'Week'; else echo 'Month'; ?>"
	};
    $("#demo").retention(options);
</script>