<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 25/04/2016
 * Time: 10:41
 */
?>

<script type="text/javascript">
    function drawPieChart<?php echo $id ?>() {
        // Radialize the colors

        Highcharts.setOptions({
			lang: {
				decimalPoint: '.',
	            thousandsSep: ','
			}
		});
		
        $('#<?php echo $id ?>').highcharts({
            colors: [<?php 
    	        foreach($data as $key => $value){
            		echo "'" . $colors[$key] . "',";
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
                foreach($data as $key => $value){
                    echo "{";
                    echo "name:'" .  $key . "',";
                    echo "y: " . $value . "";
                    echo "},";
                }
                echo "]";
                ?>
            }]
        });
    }
</script>

