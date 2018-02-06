<?php ?>
<section id="section-package">
	<div class="row">
		<div class="col-md-6">
			<script type="text/javascript">
							Highcharts.setOptions({
								lang: {
									thousandsSep: ','
								}
							});
							
							$(function() {
								$('#chart_<?php echo $body['kpi'].$body['type'] ?>').highcharts({
									chart: {
										type: 'bar'
									},
									colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
									title: {
										text: 'Top 10 <?php echo $body['type_name']?> by revenue in <?php echo $body['month']?>'
									},
									subtitle: {
										text: '<?php echo $body['from_date']?> - <?php echo $body['to_date']?>'
									},
									xAxis: {
										type: 'category',
										labels: {
											rotation: 0,
											style: {
												fontSize: '1.5em',
												fontFamily: 'Verdana, sans-serif',
												color:'#111'
											}
										}
									},
									yAxis: {
										min: 0,
										title: {
											text: 'Revenue (in millions VND)'
										},labels: {
											 format: '{value:,.0f} mil',
											///formatter: function () {
												//if (this.value >= 1000000000) {
												//if (this.value >= 1000000000000000000) {
													//this.value = this.value / 1000000000;
													//return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " bil";
												//} //else if (this.value >= 1000000) {
													//this.value = this.value / 1000000;
													//return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " mil";
												//} else {
													//return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
												//}
											//},
											style: {
												color: Highcharts.getOptions().colors[0],
												fontSize: '1em',
												fontFamily: 'Verdana, sans-serif',
												color:'#111'
							
											}
										}
									},
									legend: {
										enabled: false
									},
									plotOptions: {
										series: {
											borderWidth: 0,
											dataLabels: {
												enabled: true,
												format: '{point.y:,.0f} mil'
											}
										}
									},
									credits: {
										enabled: false
									},
									tooltip: {
										pointFormat: '{point.y} millions</b>'
									},
									series: [{
										name: 'Revenue',
										colorByPoint: true,
										data: [
										<?php
										$data = $body ['data'];
										$data = array_reverse ( $data );
										$idx = 10;
										foreach ( $data as $key => $value ) {
											echo "['" . $value ['game_name'] . " (#" . $idx . ")'," . round ( ($value ['kpi_value'] / 1000000), 2 ) . "],";
											$idx --;
										}
										?>
										],
										dataLabels: {
											enabled: true,
											rotation: 0,
											color: '#111',
											align: 'center',
											format: '{point.y:,.0f}', // one decimal
											y: 0, // 10 pixels down from the top
											style: {
												fontSize: '1.5em',
												fontFamily: 'Verdana, sans-serif',
												color:'#111'
											}
										}
									}]
								});


								$('#chartpie_<?php echo $body['kpi'].$body['type'] ?>').highcharts({
							           chart: {
							               plotBackgroundColor: null,
							               plotBorderWidth: null,
							               plotShadow: false,
							               type: 'pie'
							           },
							        credits: {
							            enabled: false
							        },
							           title: {
							               text: 'Ratio of top 10 <?php echo $body['type_name']?> by revenue in <?php echo $body['month']?>'
							           },
							           subtitle: {
							               text: '<?php echo $body['from_date']?> - <?php echo $body['to_date']?>'
							           },
							           tooltip: {
							               pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
							           },
							           plotOptions: {
							               pie: {
							                   allowPointSelect: true,
							                   cursor: 'pointer',
							                   dataLabels: {
							                       enabled: true,
							                       format: '<b>{point.name}</b>: {point.percentage:.1f} %',
							                       distance: 10,
							                       style: {
								                        fontSize: '1.5em',
								                    fontFamily: 'Verdana, sans-serif',
							                        	color: '#111'
							                    	}
							                   },
							                   showInLegend: false
							               }
							           },
							           series: [{
							               name: 'Games',
							               colorByPoint: true,
							               data: [
<?php

$data = $body ['data'];
$data = array_reverse ( $data );
$idx = 10;
foreach ( $data as $key => $value ) {
	echo "{name:'" . $value ['game_name'] . "',y:" . $value ['kpi_value'] . "},";
	$idx --;
}
?>
											]
							           }]
							       });
							       
							});
							</script>
			<div id="chart_<?php echo $body['kpi'].$body['type'] ?>"></div>
		</div>
		<div class="col-md-6">
			<div id="chartpie_<?php echo $body['kpi'].$body['type'] ?>"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="box box-solid">
				<div class="box-header with-border">
					<h3 class="box-title" id="exportFileName">
						<i class="fa fa-th"></i> Top 10 <?php echo $body['type_name']?> by revenue in <?php echo $body['month']?></h3>
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

					<table id="game-list-table-<?php echo $body['type']?>"
						class="table table-bordered table-bordered-gray table-striped"
						class="cell-border" border="1" cellspacing="0" width="100%"
						data-export-title="Top 10 <?php echo $body['type_name']?> by revenue in <?php echo $body['month']?>">
						<thead>
							<tr>
								<th>#</th>
								<th>Game</th>
								<th>Game Type</th>
								<th>Monthly Revenue (VND)</th>
								<th>Ratio (%)</th>
								<th>Release Date</th>
								<th>Team</th>
							</tr>
						</thead>
						<tbody>
					<?php $idx=1?>
					<?php foreach ($body ['data'] as $key => $value ):?>

                	<tr>
								<td><?php echo $idx;?></td>
								<td><?php echo $value['game_name'];?></td>
								<td><?php
						
switch ($value ['game_type']) {
							case 1 :
								echo "Client Game";
								break;
							case 2 :
								echo "Mobile Game";
								break;
							case 3 :
								echo "Web Game";
								break;
						}
						?></td>
								<td class="text-right"><?php echo number_format($value['kpi_value']);?></td>
								<td class="text-right"><?php echo number_format(round(($value['kpi_value']/$body ['total'])*100,2),2)?></td>
								<td><?php echo $value['release_date'];?></td>
								<td><?php echo strtoupper($value['owner']);?></td>
							</tr>
					<?php $idx++;?>
        			<?php endforeach;?>
					
				</tbody>
					</table>
				</div>
			</div>
		</div>

	</div>
</section>
