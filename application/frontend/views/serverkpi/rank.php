
<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_t1" data-toggle="tab" data-id="1">Daily</a></li>
		<li><a href="#tab_t7" data-toggle="tab" data-id="7">Last 7 Days</a></li>
		<li><a href="#tab_t30" data-toggle="tab" data-id="30">Last 30 Days</a></li>
	</ul>

	<div class="tab-content">
		<?php 
			$total = 0;
			foreach ($bar["server"] as $key => $value) {
		?>
		<div class="tab-pane <?php if($key == 1) echo "active"?>" id="tab_t<?php echo $key?>">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-solid">
						<div class="box-header with-border">
			                <h3 class="box-title"><i class="fa fa-font-awesome"></i> Server's Ranking</h3>
			            </div>
						<div class="box-footer">
							<div class="row">
								<div class="col-md-12">
									<div>
										<table class="table table-condensed" style="border-collapse:collapse;">

										    <thead>
										        <tr>
										        	<th>&nbsp;</th>
										        	<th>Rank</th>
										            <th>Server</th>
										            <th>Active</th>
										            <th>New Register</th>
										            <th>Paying User</th>
										            <th>Revenue</th>
										            <th>New Paying User</th>
										            <th>New Paying Revenue</th>
										        </tr>
										    </thead>
										
										    <tbody>
										        <tr data-toggle="collapse" data-target="#demo1" class="accordion-toggle">
										            <td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span></button></td>
										            <td><b>1</b></td>
										            <td>27</td>
										            <td><span class="text-green">123,456 <i class="fa fa-caret-up"></i></span></td>
										            <td><span class="text-red">23,456 <i class="fa fa-caret-down"></i></span></td>
										          	<td><span class="text-green">3,456 <i class="fa fa-caret-up"></i></span></td>
										          	<td><span class="text-green">4,560,000,000 <i class="fa fa-caret-up"></i></span></td>
										          	<td><span class="text-green">456 <i class="fa fa-caret-up"></i></span></td>
										          	<td><span class="text-green">456,000,000 <i class="fa fa-caret-up"></i></span></td>
										        </tr>
										        <tr>
										            <td colspan="12" class="hiddenRow">
										            	<div class="accordian-body collapse" id="demo1"> 
															<table id="table-sparkline" class="table table-striped">
											                	<thead>
											                    	<tr>
											                    		<td><a href="WorkloadURL">Server 27:</a></td>
											                    		<td>Date: 27-Dec-2016</td>
											                    	</tr>
											                        <tr>
											                        	<th>KPI</th>
											                        	<th>Trend</th>
											                        	<th>-30 days</th>
											                        	<th>-7 day</th>
											                        	<th>-1 day</th>
											                        	<th>Current Value</th>
											                        </tr>
											                 	</thead>
											                    <tbody id="tbody-sparkline">
											                        <tr>
											                        	<th>Active</th>
											                        	<td data-sparkline="71, 78, 39, 66, 43, 65, 87, 32, 54, 98, 09, 43, 65, 76, 187"></td>
											                        	<td>7</td>
											                        	<td>87</td>
											                        	<td>87</td>
											                        	<td>187</td>
											                        </tr>
											                        <tr>
											                        	<th>New Register</th>
											                        	<td data-sparkline="71, 78, 39, 66, 43, 65, 87, 32, 54, 98, 09, 43, 65, 76, 87"></td>
											                        	<td>7</td>
											                        	<td>87</td>
											                        	<td>87</td>
											                        	<td>87</td>
											                        </tr>
											                        <tr>
											                        	<th>Paying User</th>
											                        	<td data-sparkline="71, 78, 39, 66, 43, 65, 87, 32, 54, 98, 09, 43, 65, 76, 87"></td>
											                        	<td>7</td>
											                        	<td>87</td>
											                        	<td>87</td>
											                        	<td>87</td>
											                        </tr>
											                        <tr>
											                        	<th>Revenue</th>
											                        	<td data-sparkline="71, 78, 39, 66, 43, 65, 87, 32, 54, 98, 09, 43, 65, 76, 87"></td>
											                        	<td>7</td>
											                        	<td>87</td>
											                        	<td>87</td>
											                        	<td>87</td>
											                        </tr>
											                        <tr>
											                        	<th>New Paying User</th>
											                        	<td data-sparkline="71, 78, 39, 66, 43, 65, 87, 32, 54, 98, 09, 43, 65, 76, 87"></td>
											                        	<td>7</td>
											                        	<td>87</td>
											                        	<td>87</td>
											                        	<td>87</td>
											                        </tr>
											                        <tr>
											                        	<th>New Paying Revenue</th>
											                        	<td data-sparkline="71, 78, 39, 66, 43, 65, 87, 32, 54, 98, 09, 43, 65, 76, 87"></td>
											                        	<td>7</td>
											                        	<td>87</td>
											                        	<td>87</td>
											                        	<td>87</td>
											                        </tr>
											                    </tbody>
											               	</table>
										        		</div>
										        	</td>
										        </tr>
										        <tr data-toggle="collapse" data-target="#demo2" class="accordion-toggle">
										            <td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span></button></td>
										            <td>OBS Name</td>
										            <td>OBS Description</td>
										            <td>hpcloud</td>
										            <td>nova</td>
										          	<td> created</td>
										          	<td> created</td>
										          	<td> created</td>
										          	<td> created</td>
										        </tr>
										        <tr>
										            <td colspan="6" class="hiddenRow"><div id="demo2" class="accordian-body collapse">Demo2</div></td>
										        </tr>
										        <tr data-toggle="collapse" data-target="#demo3" class="accordion-toggle">
										            <td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span></button></td>
										            <td>OBS Name</td>
										            <td>OBS Description</td>
										            <td>hpcloud</td>
										            <td>nova</td>
										          	<td> created</td>
										          	<td> created</td>
										          	<td> created</td>
										          	<td> created</td>
										        </tr>
										        <tr>
										            <td colspan="6" class="hiddenRow"><div id="demo3" class="accordian-body collapse">Demo3</div></td>
										        </tr>
										    </tbody>
										</table>
										<script type="text/javascript">
											$(function () {
											    /**
											     * Create a constructor for sparklines that takes some sensible defaults and merges in the individual
											     * chart options. This function is also available from the jQuery plugin as $(element).highcharts('SparkLine').
											     */
											    Highcharts.SparkLine = function (a, b, c) {
											        var hasRenderToArg = typeof a === 'string' || a.nodeName,
											            options = arguments[hasRenderToArg ? 1 : 0],
											            defaultOptions = {
											                chart: {
											                    renderTo: (options.chart && options.chart.renderTo) || this,
											                    backgroundColor: null,
											                    borderWidth: 0,
											                    type: 'area',
											                    margin: [2, 0, 2, 0],
											                    width: 500,
											                    height: 50,
											                    style: {
											                        overflow: 'visible'
											                    },
											                    skipClone: true
											                },
											                title: {
											                    text: ''
											                },
											                credits: {
											                    enabled: false
											                },
											                xAxis: {
											                	categories: ["2016-07-01","2016-07-02","2016-07-03","2016-07-04","2016-07-05","2016-07-06","2016-07-07",
															                	"2016-07-08","2016-07-09","2016-07-10","2016-07-11","2016-07-12","2016-07-13","2016-07-14",
															                	"2016-07-15"], 
											                    labels: {
											                        enabled: false
											                    },
											                    title: {
											                        text: null
											                    },
											                    startOnTick: false,
											                    endOnTick: false,
											                    tickPositions: []
											                },
											                yAxis: {
											                    endOnTick: false,
											                    startOnTick: false,
											                    labels: {
											                        enabled: false
											                    },
											                    title: {
											                        text: null
											                    },
											                    tickPositions: [0]
											                },
											                legend: {
											                    enabled: false
											                },
											                tooltip: {
											                    backgroundColor: null,
											                    borderWidth: 0,
											                    shadow: false,
											                    useHTML: true,
											                    hideDelay: 0,
											                    shared: true,
											                    padding: 0,
											                    positioner: function (w, h, point) {
											                        return { x: point.plotX - w / 2, y: point.plotY - h };
											                    }
											                },
											                plotOptions: {
											                    series: {
											                        animation: false,
											                        lineWidth: 1,
											                        shadow: false,
											                        states: {
											                            hover: {
											                                lineWidth: 1
											                            }
											                        },
											                        marker: {
											                            radius: 1,
											                            states: {
											                                hover: {
											                                    radius: 2
											                                }
											                            }
											                        },
											                        fillOpacity: 0.25
											                    },
											                    column: {
											                        negativeColor: '#910000',
											                        borderColor: 'silver'
											                    }
											                },
											                exporting: {enabled: false}
											            };
	
											        options = Highcharts.merge(defaultOptions, options);
	
											        return hasRenderToArg ?
											            new Highcharts.Chart(a, options, c) :
											            new Highcharts.Chart(options, b);
											    };
	
											    var start = +new Date(),
											        $tds = $('td[data-sparkline]'),
											        fullLen = $tds.length,
											        n = 0;
	
											    // Creating 153 sparkline charts is quite fast in modern browsers, but IE8 and mobile
											    // can take some seconds, so we split the input into chunks and apply them in timeouts
											    // in order avoid locking up the browser process and allow interaction.
											    function doChunk() {
											        var time = +new Date(),
											            i,
											            len = $tds.length,
											            $td,
											            stringdata,
											            arr,
											            data,
											            chart;
	
											        for (i = 0; i < len; i += 1) {
											            $td = $($tds[i]);
											            stringdata = $td.data('sparkline');
											            arr = stringdata.split('; ');
											            data = $.map(arr[0].split(', '), parseFloat);
											            chart = {};
	
											            if (arr[1]) {
											                chart.type = arr[1];
											            }
											            $td.highcharts('SparkLine', {
											                series: [{
											                    data: data,
											                    pointStart: 0
											                }],
											                tooltip: {
											                    headerFormat: '<span style="font-size: 10px">' + $td.parent().find('th').html() + ' - {point.x}:</span><br/>',
											                    pointFormat: '<b>{point.y}</b> users'
											                },
											                chart: chart
											            });
	
											            n += 1;
	
											            // If the process takes too much time, run a timeout to allow interaction with the browser
											            if (new Date() - time > 500) {
											                $tds.splice(0, i + 1);
											                setTimeout(doChunk, 0);
											                break;
											            }
	
											            // Print a feedback on the performance
											            if (n === fullLen) {
											                $('#result').html('Generated ' + fullLen + ' sparklines in ' + (new Date() - start) + ' ms');
											            }
											        }
											    }
											    doChunk();
	
											});
										</script>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php }?>
	</div>
	<!-- /.tab-content -->
</div>