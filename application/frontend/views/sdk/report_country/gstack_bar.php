<?php

if(count($data) == 0){
	$html = $this->load->view("body_parts/contact", null, TRUE);
	echo '<div class="row data-alert">' . $html . '</div>';
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
	            type: 'column',
                zoomType: 'xy',
	            animation: false
	        },
	        title: {
	            text: '<?php echo $title?>',
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
	            },
                min: 0,
                labels: {
                    // format: '{value} ',
                    formatter: function () {
                        if (this.value >= 1000000000) {
                        //if (this.value >= 1000000000000000000) {
                            this.value = this.value / 1000000000;
                            return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " Bil";
                        } else if (this.value >= 1000000) {
                            this.value = this.value / 1000000;
                            return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " Mil";
                        } else {
                            return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    },
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                }
	        },
            credits: {
                enabled: false
            },
	        legend: {
	            layout: 'horizontal',
	            align: 'center',
	            verticalAlign: 'bottom',
	            borderWidth: 0
	        },
	        tooltip: {
	            headerFormat: '<b>{point.x}</b><br/>',
	            pointFormat: '{series.name}: {point.y:.,1f}<br/>Total: {point.stackTotal:.,1f}'
	        },
	        plotOptions: {
	            column: {
	                stacking: 'normal',
	                dataLabels: {
	                    enabled: true,
	                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
	                    style: {
	                        textShadow: '0 0 3px black'
	                    },
	        			format: '<span>{point.percentage:.1f}%</span><br/>',
	                }
	            }
	        },
	        series: [
     	        <?php
                    $i = 0;
	     	        foreach($selectedCountry as $name){
	     	        	echo "{";
	     	        	echo "name: '" ." " . $name . "',";
	     	        	echo "color: '" . $colors[$i] . "',";
	     	        	$i++;
	     	        	echo "data: [";
	     	        	foreach ($days as $day){
	     	        		
	     	        		if(isset($data[$day][$name]))
	     	        		{
	     	        			echo $data[$day][$name] . ",";
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
