<?php ?>
<section id="section-package">
	<div class="row">
		<div class="col-md-12">
		<script type="text/javascript">
			$('#trendchart').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Revenue by Mobile OS'
            },subtitle: {
                text: '<?php echo "sss" ?>'
            },
            xAxis: {
                categories: [
                    <?php foreach ($body ['byDates'] as $key => $value ):?> <?php echo "'".$key ."',"; ?><?php endforeach;?>
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Revenue (VND)'
                },
                stackLabels: {
                    enabled: false,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            legend: {
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {

                shared: true
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
            	<?php foreach ($body ['byOss'] as $key => $value ):?> 
            	{
                    name: $key,
                    color:'#cc9900',
                    data: [<?php foreach ($value as $value2):?> <?php echo $value2["value"] .","; ?><?php endforeach;?>]
                },
            	<?php endforeach;?>
                
            ]
        });
			</script>			
			<div class="chart" id="trendchart"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="box box-solid"></div>
		</div>
	</div>
</section>
