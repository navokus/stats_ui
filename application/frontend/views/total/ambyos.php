<script type="text/javascript">
Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
    return {
        radialGradient: {
            cx: 0.5,
            cy: 0.3,
            r: 0.7
        },
        stops: [
            [0, color],
            [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
        ]
    };
});
</script>
<section id="section-package">
	<div class="row">
		<div class="col-md-12">
			<script type="text/javascript">
		 $(function () {
		        Highcharts.setOptions({
		            colors: ['#009999', '#F56954', '#00A65A', '#C07BCC', '#C3C66C']
		            ,
		            credits: {
		                enabled: false
		            },lang: {
		                thousandsSep: ','
		            }
		        });
		        
			$('#trendchart').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Monthly Mobile'
            },subtitle: {
                text: '<?php echo $body["title"] ?>'
            },
            xAxis: {
                categories: [
                    <?php foreach ($body ['byDates'] as $key => $value ):?> <?php echo "'". $this->util->get_xcolumn_by_timming($key,31,true) ."',"; ?><?php endforeach;?>
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: '<?php echo $body["title"] ?>'
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
                    name: '<?php echo strtoupper($key) ?>',
                    data: [<?php foreach ($value as $value2):?> <?php echo $value2["value"] .","; ?><?php endforeach;?>]
                },
            	<?php endforeach;?>
                
            ]
        });
		 });
			</script>
			<div class="chart" id="trendchart"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="box box-solid">
				<div class="box-header with-border">
					<h3 class="box-title" id="exportFileName">
						<i class="fa fa-th"></i>Datatable
					</h3>
					<div class="box-tools">
						<a class="btn btn-box-tool" href="#" title="Copy to clipboard!"
							id="copy"> <img
							src="<?php echo base_url('public/frontend/dist/img/copy.png'); ?>"
							width="22px" height="22px" />
						</a> <a class="btn btn-box-tool" href="#"
							title="Download excel file!" id="downloadExcel"> <img
							src="<?php echo base_url('public/frontend/dist/img/export.gif'); ?>"
							width="20px" height="20px" />
						</a>
					</div>
				</div>
				<div class="box-body">

					<table id="kpiTable"
						class="table table-bordered table-bordered-gray table-striped"
						class="cell-border" border="1" cellspacing="0" width="100%"
						data-export-title="xx">
						<thead>
							<tr>
								<th>#</th>
							<?php foreach ($body ['byDates'] as $key => $value ):?> 
								<th><?php echo $this->util->get_xcolumn_by_timming($key,31,true)  ?></th>
								<?php endforeach;?>	
							</tr>
						</thead>
						<tbody>
					<?php foreach ($body ['byOss'] as $key => $value ):?>
					<tr>
								<td><?php $tmp=0; $growth=0; echo  strtoupper($key) ?></br> MoM Growth</td> 
						<?php foreach ($value as $value2):?> 
							<?php $growth = 1-$tmp/$value2['value']; ?>
							<td class="text-right"><?php echo number_format($value2['value']);?></br> <?php if($tmp==0) {echo "-";} else { echo number_format(100*$growth,2)."%";} $tmp=$value2['value']; 
							?></td>
						<?php endforeach;?>
            		</tr>
            		<?php endforeach;?>
				</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
      $(function () {
        $('#kpiTable').DataTable({
        	paging: false,
          searching: true,
          ordering: false,
          info: true,
          responsive: true,
          dom: 'Bfrtip',
          buttons: [
                    'copy',
                    'excel'
                ]
        });
      });
    </script>