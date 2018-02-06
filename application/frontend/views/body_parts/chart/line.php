<?php
if(count($data) == 0){
	$html = $this->load->view("body_parts/contact", null, TRUE);
	echo '<div class="row">' . $html . '</div>';
} else {
?>
<script type="text/javascript">
	$(function () {
		Highcharts.setOptions({
			lang: {
				decimalPoint: '.',
	            thousandsSep: ','
			}
		});
		
	    $('#<?php echo $id?>').highcharts({
	    	chart: {
	            height: 600,
	            type: 'line',
	            animation: false
	        },
	        title: {
	            text: '<?php echo $title . ' - ' . strtoupper($game["owner"])?>',
	            x: -20 //center
	        },
	        subtitle: {
	            text: '<?php echo $subTitle;?>',
	            x: -20
	        },
	        xAxis: {
	            categories: <?php echo "["; 
					        	foreach($days as $day){
					        		echo "'" . $this->util->get_xcolumn_by_timming($day, $timing, true) . "',";	
					        	}
					        	echo "]"; 
					        ?>,
				crosshair: true
	        },
	        yAxis: {
	            title: {
	                text: '<?php echo $metric . " (" . $unit . ")"?>'
	            }
	        },
	        legend: {
	            layout: 'horizontal',
	            align: 'center',
	            verticalAlign: 'bottom',
	            borderWidth: 0
	        },
	        tooltip: {
	            valueSuffix: ' <?php echo $unit ?>',
	            crosshairs: true,
	            shared: true,
	            valueDecimals: <?php if(!empty($valueDecimals)){ echo $valueDecimals;} else echo 0;?>
	        },
	        plotOptions: {
	            series: {
	                marker: {
	                    enabled: true
	                },
	                animation: false
	            }
	        },
	        series: [
     	        <?php 
	     	        foreach($selectedGroup as $id => $name){
	     	        	echo "{";
	     	        	echo "name: '" . $name . "',";
	     	        	echo "data: [";
	     	        	foreach ($days as $day){
	     	        		
	     	        		if(isset($data[$id][$day]))
	     	        		{
	     	        			echo $data[$id][$day] . ",";
	     	        		}else{
	     	        			echo "0,";
	     	        		}
	     	        	}
	     	        	echo "]";
	     	        	echo "},";
	     	        }
     	        ?>
	     	]
	    });
	});
</script>
<?php }?>
