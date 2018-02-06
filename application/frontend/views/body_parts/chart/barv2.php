<?php ?>
<div id="container_<?php echo $id?>" style="margin: 0 auto"></div>
<script type="text/javascript">

	$(function () {
		Highcharts.setOptions({
			lang: {
				thousandsSep: ','
			}
		});
		
		$('#container_<?php echo $id?>').highcharts({
			chart: {
				type: 'column'
			},
			title: {
				text: '<?php echo $title . " - " . $game["GameName"] . " - " . strtoupper($game["owner"])?>'
			},
			subtitle: {
				text: '<?php echo $subTitle?>'
			},
			xAxis: {
				type: 'category',
				labels: {
					rotation: 0,
					style: {
						fontSize: '13px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
			},
			yAxis: {
				min: 0,
				title: {
					text: '<?php echo $metric . " (" . $unit . ")"?>'
				}
			},
			legend: {
				enabled: false
			},
			tooltip: {
				pointFormat: '<?php echo $kpi?>: <b>{point.y:,.0f} <?php echo $unit?></b>'
			},
			series: [{
				name: 'Server',
				data: [<?php
	                foreach($data as $key => $value){
	                    echo "['S" .  $key . "', " . $value . "],";
	                }
		        ?>],
				dataLabels: {
					enabled: true,
					rotation: 0,
					color: '#FFFFFF',
					align: 'center',
					format: '{point.y:,.0f}', // one decimal
					y: 30, // 10 pixels down from the top
					style: {
						fontSize: '13px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
			}]
		});
	});
</script>