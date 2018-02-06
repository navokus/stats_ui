<?php $ChartLimit=20; ?>
<section id="section-package">
	<div class="row">
		<div class="col-md-12">
			<script type="text/javascript">
							Highcharts.setOptions({
								lang: {
									thousandsSep: ','
								}
							});
							
							$(function() {
								$('#chart_<?php echo $body['kpi'].$body['type'] ?>').highcharts({
									chart: {
										type: 'column'
									},
									colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
									title: {
										text: '<?php echo strtoupper($body['type_name'])?> Games in <?php echo $body['month']?>'
									},
									subtitle: {
										text: '<?php echo $body['from_date']?> - <?php echo $body['to_date']?>'
									},
									xAxis: {
										categories: [
									<?php $idx=0; foreach ($body ['data'] as $key => $value ):?> <?php echo "'".$value ['game_name'] ."',"; $idx++; if($idx>=$ChartLimit){break;}?><?php endforeach;?>
										            ],
										            crosshair: true
									},
									yAxis: {
										min: 0,
										title: {
											text: 'Revenue (in millions VND)'
										},labels: {
											 format: '{value:,.0f} mil',
											style: {
												color: Highcharts.getOptions().colors[0],
												fontSize: '1em',
												fontFamily: 'Verdana, sans-serif',
												color:'#111'
							
											}
										}
									},
									legend: {
										layout: 'horizontal',
							            align: 'right',
							            verticalAlign: 'top',
							            x: -200,
							            y: 200,
							            floating: true,
							            borderWidth: 1,
							            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
							            shadow: true,
							            title:{text:''},
							            itemStyle:{fontSize:'2em'},
							            enabled:false
									},
									plotOptions: {
										series: {
							                borderWidth: 0,
							                dataLabels: {
							                    enabled: true,
							                    format: '{point.y:,.1f}%',
							                    align: 'center'
							                }
							            },
							            column: {
							                pointPadding: 0,
							                borderWidth: 0
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
										$idx = 0;
										foreach ( $data as $key => $value ) {
											echo "[" . $value ['mil_value'] . "],";
											$idx ++;
											if ($idx >= $ChartLimit) {
												break;
											}
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
												fontSize: '1em',
												fontFamily: 'Verdana, sans-serif',
												color:'#111'
											}
										}
									}]
								});

							       
							});
							</script>
			<div id="chart_<?php echo $body['kpi'].$body['type'] ?>"></div>
		</div>

	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="box box-solid">
				<div class="box-header with-border">
					<h3 class="box-title" id="exportFileName">
						<i class="fa fa-th"></i> <?php echo strtoupper($body['type_name'])?> Games in <?php echo $body['month']?></h3>
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
						class="cell-border" border="1" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>#</th>
								<th>Game</th>
								<th>Game Type</th>
								<th>Monthly Revenue(mil VND)</th>
								<th>Team</th>
							</tr>
						</thead>
						<tbody>
					<?php $idx=1?>
					<?php foreach ($body ['data'] as $key => $value ):?>
                	<tr>
								<td><?php echo $idx;?></td>
								<td><a href="<?php echo site_url('dashboard') ."/". $value['game_code']; ?>"><?php echo $value['game_name'];?></a></td>
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
								<td class="text-right"><?php echo number_format($value['mil_value']);?></td>
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
