<?php ?>
<div id="container_<?php echo $id?>" style="margin: 0 auto"></div>
<script type="text/javascript">
	/**
	 * In order to synchronize tooltips and crosshairs, override the
	 * built-in events with handlers defined on the parent element.
	 */
	$('#container_<?php echo $id?>').bind('mousemove touchmove touchstart', function (e) {
	    var chart,
	        point,
	        i,
	        event;

	    for (i = 0; i < Highcharts.charts.length; i = i + 1) {
	        chart = Highcharts.charts[i];
	        event = chart.pointer.normalize(e.originalEvent); // Find coordinates within the chart
	        point = chart.series[0].searchPoint(event, true); // Get the hovered point
	
	        if (point) {
	            point.highlight(e);
	        }
	    }
	});
	/**
	 * Override the reset function, we don't need to hide the tooltips and crosshairs.
	 */
	Highcharts.Pointer.prototype.reset = function () {
	    return undefined;
	};
	
	/**
	 * Highlight a point by showing tooltip, setting hover state and draw crosshair
	 */
	Highcharts.Point.prototype.highlight = function (event) {
	    this.onMouseOver(); // Show the hover marker
	    this.series.chart.tooltip.refresh(this); // Show the tooltip
	    this.series.chart.xAxis[0].drawCrosshair(event, this); // Show the crosshair
	};
	
	/**
	 * Synchronize zooming through the setExtremes event handler.
	 */
	function syncExtremes(e) {
	    var thisChart = this.chart;
	
	    if (e.trigger !== 'syncExtremes') { // Prevent feedback loop
	        Highcharts.each(Highcharts.charts, function (chart) {
	            if (chart !== thisChart) {
	                if (chart.xAxis[0].setExtremes) { // It is null while updating
	                    chart.xAxis[0].setExtremes(e.min, e.max, undefined, false, { trigger: 'syncExtremes' });
	                }
	            }
	        });
	    }
	}
	
	 <?php 
	 	$i = 0;
	 	foreach ($datasets as $key => $value) {?>
	        $('<div class="chart">')
	            .appendTo('#container_<?php echo $id?>')
	            .highcharts({
	                chart: {
	                    marginLeft: 70
	                },
	                title: {
	                    text: <?php echo "'" . $value["title"] . "'"?>,
	                    align: 'center'
	                },
	                subtitle: {
	    				text: '<?php echo $subTitle?>'
	    			},
	                legend: {
	                    enabled: false
	                },
	                xAxis: {
	                	categories: <?php echo "["; 
					                	foreach($value["data"] as $k => $v){
					                		echo "'S" . $k . "',";	
					                	}
					                	echo "]"; 
					                ?>,
	                    crosshair: true
	                },
	                yAxis: {
	                	min: 0,
	                    title: {
	                        text: '<?php echo $value["name"] . " (" . $value["unit"] . ")"?>'
	                    }
	                },
	    			tooltip: {
	    				pointFormat: '<?php echo $value["name"]?>: <b>{point.y:,.0f} <?php echo $value["unit"]?></b>'
	    			},
	                series: [{
	                	type: <?php echo "'" . $value["type"] . "'"?>,
	                    name: <?php echo "'" . $value["name"] . "'"?>,
	                    data: <?php echo "["; 
		                    	foreach($value["data"] as $k => $v){
		                    		echo $v . ",";	
		                    	}
		                    	echo "]"; 
		                      ?>,
	                    color: Highcharts.getOptions().colors[<?php echo $i?>],
	                    fillOpacity: 0.3,
	                    tooltip: {
	                        valueSuffix: ' ' + <?php echo "'" . $value["unit"] . "'"?>
	                    },
	                    dataLabels: {
	    					enabled: true,
	    					rotation: 0,
	    					color: '#FFFFFF',
	    					align: 'center',
	    					format: '{point.y:,.0f}',
	    					y: 30,
	    					style: {
	    						fontSize: '13px',
	    						fontFamily: 'Verdana, sans-serif'
	    					}
	    				}
	                }]
	            });
        <?php 
			$i++;
		}?>
</script>