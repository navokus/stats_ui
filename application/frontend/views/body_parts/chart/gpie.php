<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 25/04/2016
 * Time: 10:41
 */
?>

<script type="text/javascript">
    $(function () {
        // Radialize the colors

        Highcharts.setOptions({
			lang: {
				decimalPoint: '.',
	            thousandsSep: ','
			}
		});
		
        $('#<?php echo $id ?>').highcharts({
            colors: [<?php 
            	foreach($data as $group => $value){
            		foreach($value as $day => $v){
            			if($day == $days  && array_key_exists($group, $selectedGroup)){
            				echo "'" . $colors[$group] . "',";
            			}
            		}
            	}
        	?>],
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: '<?php echo $title ?>'
            },
            subtitle: {
                text: '<?php echo $subTitle ?>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '{point.percentage:.1f} %',
                        distance: -30
                    },
                    showInLegend: true
                }
            },
            tooltip: {
                pointFormat: 'Total: <b>{point.y:,.0f}</b>'
            },
            credits: {
                enabled: false
            },
            series: [{
                <?php
                echo "name: 'Ty le: ',";
                echo "colorByPoint: true,";
                echo "data:[";
                foreach($data as $group => $value){
                	foreach($value as $day => $v){
                		if($day == $days  && array_key_exists($group, $selectedGroup)){
	                		echo "{";
	                		echo "name:'" .  $selectedGroup[$group] . "',";
	                		echo "y: " . $v . "";
	                		echo "},";
                		}
                	}
                }
                echo "]";
                ?>
            }]
        });
    });
</script>

