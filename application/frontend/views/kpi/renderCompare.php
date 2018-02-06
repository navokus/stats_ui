<script type="text/javascript">


$(function () {
	Highcharts.setOptions({
	    colors: ['#009999', '#F56954', '#cc9900', '#C07BCC', '#C3C66C']
	,
	credits: {
	    enabled: false
	},lang: {
		thousandsSep: ','
	}
	});
    $('#chart').highcharts({
    	chart: {
            zoomType: 'xy'
        },
        title: {
            text: '<?php echo $body ['comparison_text']; ?>',
            x: -20 //center
        },
        subtitle: {
            text: '<?php echo $body ['game_info']["GameName"] . " - " . strtoupper($body ['game_info']["owner"]) ; ?>',
            x: -20
        },
        xAxis: {
            categories: [<?php foreach ($body ['kpi_data']['text_dates'] as $key => $value ):?> <?php echo "'".$value ."',"; ?><?php endforeach;?>]
        },
        yAxis:[<?php  if ($body['yAxis']['left']!=null){?>{
                 title: {
                        text: '<?php echo $body['yAxis']['left']["title"]; ?>',
                        style: {
                            color: Highcharts.getOptions().colors[<?php echo $body['yAxis']['left']['color']; ?>]
                        }
                    },
                    min: 0,
                    labels: {
                        formatter: function () {
                            if (this.value >= 1000000000000000000) {
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
                            color: Highcharts.getOptions().colors[<?php echo $body['yAxis']['left']['color']; ?>]
                        }
                    },
                    opposite:<?php echo $body['yAxis']['left']['opposite']; ?>
                },<?php }?>
                <?php  if ($body['yAxis']['right']!=null){?>{
                    title: {
                           text: '<?php echo $body['yAxis']['right']["title"]; ?>',
                           style: {
                               color: Highcharts.getOptions().colors[<?php echo $body['yAxis']['right']['color']; ?>]
                           }
                       },
                       min: 0,
                       labels: {
                           formatter: function () {
                               if (this.value >= 1000000000000000000) {
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
                               color: Highcharts.getOptions().colors[<?php echo $body['yAxis']['right']['color']; ?>]
                           }
                       },
                       opposite:<?php echo $body['yAxis']['right']['opposite']; ?>
                   },<?php }?>
                ],
        tooltip: {
        	shared: true
        },
        series: [<?php  foreach ($body ['kpis'] as $kpi_name):?> {
        	name:'<?php echo $body['kpi_data'][$kpi_name]["kpi_display"]; ?>',
        	data:[<?php foreach ($body['kpi_data'][$kpi_name]['data'] as $dkey => $dvalue ):?> <?php echo $dvalue .","; ?> <?php endforeach;?>],
        	type:'<?php echo $body['kpi_data'][$kpi_name]['chart'];?>',
        	yAxis:<?php echo $body['kpi_data'][$kpi_name]['yAxis'];?>,
        	color: Highcharts.getOptions().colors[<?php echo $body['yAxis'][$body['kpi_data'][$kpi_name]['dim']]['color']; ?>]
        },
        <?php endforeach;?>
         ]
    });

});
</script>
<div id="chart">
</div>
