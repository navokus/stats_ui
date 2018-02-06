
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
        <?php $total_a1=0; ?>
        <?php if($body ['os_data']!=null){ ?>
        $('#usermobile_<?php echo $body['timing']?>').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Active Users by Mobile OS'
            },subtitle: {
                text: '<?php echo $body['gameCode'] ?>'
            },
            xAxis: {
                categories: [
                    <?php foreach ($body ['os_data']['text_dates'] as $key => $value ):?> <?php echo "'".$value ."',"; ?><?php endforeach;?>
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Number of Active Users'
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
                {
                    name: 'IOS',
                    color:'#cc9900',
                    data: [<?php foreach ($body ['os_data']["a".$body['suffix']]['ios'] as $key => $value ):?> <?php echo $value .","; ?><?php endforeach;?>]
                },
                {
                    name: 'Android',
                    color:'#00cc00',
                    data: [<?php foreach ($body ['os_data']["a".$body['suffix']]['android'] as $key => $value ):?> <?php echo $value .","; ?><?php endforeach;?>]
                },
                {
                    name: 'Other',
                    color:'#669999',
                    data: [<?php foreach ($body ['os_data']["a".$body['suffix']]['other'] as $key => $value ):?> <?php echo $value .","; ?><?php endforeach;?>]
                }
            ]
        });


        $('#usermobile_pie_<?php echo $body['timing']?>').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                spacingBottom: 25,
                spacingTop: 25,
                spacingLeft: 10,
                spacingRight: 10,
            },
            title: {
                text: 'Mobile Os Active Users Share'
            },subtitle: {
                text: '<?php echo $body['gameCode'] ?>'
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
                        distance: -30,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    showInLegend: true
                }
            },

            series: [{
                name: 'Mobile OS',
                data: [{
                    name: 'IOS',
                    color:'#cc9900',
                    <?php $total_a1=0; foreach ($body ['os_data']["a".$body['suffix']]['ios'] as $key => $value ):?> <?php $total_a1+= $value; ?><?php endforeach; echo "y:".$total_a1;?>

                }, {
                    name: 'Android',
                    color:'#00cc00',
                    <?php $total_a1=0; foreach ($body ['os_data']["a".$body['suffix']]['android'] as $key => $value ):?> <?php $total_a1+= $value; ?><?php endforeach; echo "y:". $total_a1;?>
                }, {
                    name: 'Other',
                    color:'#669999',
                    <?php $total_a1=0; foreach ($body ['os_data']["a".$body['suffix']]['other'] as $key => $value ):?> <?php $total_a1+= $value; ?><?php endforeach; echo "y:". $total_a1;?>
                }]
            }]
        });
        $('#revenuemobile_<?php echo $body['timing']?>').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Revenue by Mobile OS'
            },subtitle: {
                text: '<?php echo $body['gameCode'] ?>'
            },
            xAxis: {
                categories: [
                    <?php foreach ($body ['os_data']['text_dates'] as $key => $value ):?> <?php echo "'".$value ."',"; ?><?php endforeach;?>
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
                {
                    name: 'IOS',
                    color:'#cc9900',
                    data: [<?php foreach ($body ['os_data']["gr".$body['suffix']]['ios'] as $key => $value ):?> <?php echo $value .","; ?><?php endforeach;?>]
                },
                {
                    name: 'Android',
                    color:'#00cc00',
                    data: [<?php foreach ($body ['os_data']["gr".$body['suffix']]['android'] as $key => $value ):?> <?php echo $value .","; ?><?php endforeach;?>]
                },
                {
                    name: 'Other',
                    color:'#669999',
                    data: [<?php foreach ($body ['os_data']["gr".$body['suffix']]['other'] as $key => $value ):?> <?php echo $value .","; ?><?php endforeach;?>]
                }
            ]
        });


        $('#revenuemobile_pie_<?php echo $body['timing']?>').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                spacingBottom: 25,
                spacingTop: 25,
                spacingLeft: 10,
                spacingRight: 10,
            },
            title: {
                text: 'Mobile Os Revenue Share'
            },subtitle: {
                text: '<?php echo $body['gameCode'] ?>'
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
                        distance: -30,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    showInLegend: true
                }
            },

            series: [{
                name: 'Mobile OS',
                data: [{
                    name: 'IOS',
                    color:'#cc9900',
                    <?php $total_a1=0; foreach ($body ['os_data']["gr".$body['suffix']]['ios'] as $key => $value ):?> <?php $total_a1+= $value; ?><?php endforeach; echo "y:".$total_a1;?>

                }, {
                    name: 'Android',
                    color:'#00cc00',
                    <?php $total_a1=0; foreach ($body ['os_data']["gr".$body['suffix']]['android'] as $key => $value ):?> <?php $total_a1+= $value; ?><?php endforeach; echo "y:". $total_a1;?>
                }, {
                    name: 'Other',
                    color:'#669999',
                    <?php $total_a1=0; foreach ($body ['os_data']["gr".$body['suffix']]['other'] as $key => $value ):?> <?php $total_a1+= $value; ?><?php endforeach; echo "y:". $total_a1;?>
                }]
            }]
        });

        <?php }; ?>
        $('#userkpi_<?php echo $body['timing']?>').highcharts({
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: 'Active User'
            },subtitle: {
                text: '<?php echo $body['gameCode'] ?>'
            },
            xAxis: {
                categories: [
                    <?php foreach ($body ['kpi_data']['text_dates'] as $key => $value ):?> <?php echo "'".$value ."',"; ?><?php endforeach;?>
                ]
            },
            yAxis: [
                { // Primary yAxis
                    title: {
                        text: 'ACTIVE USERs',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    min: 0,
                    labels: {
                        // format: '{value} ',
                        formatter: function () {
                            //if (this.value >= 1000000000) {
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
                            color: Highcharts.getOptions().colors[0]
                        }
                    }
                },
                { // Second yAxis
                    title: {
                        text: 'NEW USER',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    min:0,
                    labels: {
                        // format: '{value} ',
                        formatter: function () {
                            if (this.value >= 1000000000) {
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
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    opposite: true
                }

            ],
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
                        enabled: false,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        style: {
                            textShadow: '0 0 3px black'
                        }
                    }
                }
            },
            series: [
                {
                    name: 'Active Users',
                    data: [<?php foreach ($body ['kpi_data']["a".$body['suffix']] as $key => $value ):?> <?php echo $value .","; ?><?php endforeach;?>],
                    type:'column',
                    color: Highcharts.getOptions().colors[0],
                    yAxis:0
                },
                {
                    name: 'New Users',
                    data: [<?php foreach ($body ['kpi_data']["n".$body['suffix']] as $key => $value ):?> <?php echo $value .","; ?><?php endforeach;?>],
                    type:'spline',
                    color: Highcharts.getOptions().colors[1],
                    yAxis:1
                }
            ]
        });



        $('#revenuekpi_<?php echo $body['timing']?>').highcharts({
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: 'Revenue (VND)'
            },
            subtitle: {
                text: '<?php echo $body['gameCode'] ?>'
            },
            xAxis: {
                categories: [
                    <?php foreach ($body ['kpi_data']['text_dates'] as $key => $value ):?> <?php echo "'".$value ."',"; ?><?php endforeach;?>
                ]
            },
            yAxis: [
                { // Primary yAxis
                    title: {
                        text: 'Revenue',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    min: 0,
                    labels: {
                        // format: '{value} ',
                        formatter: function () {
                            //if (this.value >= 1000000000) {
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
                            color: Highcharts.getOptions().colors[0]
                        }
                    }
                },
                { // Second yAxis
                    title: {
                        text: 'Paying Users',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    min:0,
                    labels: {
                        // format: '{value} ',
                        formatter: function () {
                            if (this.value >= 1000000000) {
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
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    opposite: true
                }

            ],
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
                        enabled: false,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        style: {
                            textShadow: '0 0 3px black'
                        }
                    }
                }
            },
            series: [
                {
                    name: 'Revenue',
                    data: [<?php foreach ($body ['kpi_data']["gr".$body['suffix']] as $key => $value ):?> <?php echo $value .","; ?><?php endforeach;?>],
                    type:'column',
                    color: Highcharts.getOptions().colors[0],
                    yAxis:0
                },
                {
                    name: 'Paying Users',
                    data: [<?php foreach ($body ['kpi_data']["pu".$body['suffix']] as $key => $value ):?> <?php echo $value .","; ?><?php endforeach;?>],
                    type:'spline',
                    color: Highcharts.getOptions().colors[1],
                    yAxis:1
                }
            ]
        });


        <?php foreach ($body ['kpi_data']["trend"] as $key => $value ):?>
        $('#trend_<?php echo $key ?>').highcharts({
            chart: {
                backgroundColor: '#ebebe0',
                borderWidth: 0,
                type: 'area',
                margin: [2, 0, 2, 0],
                style: {
                    overflow: 'visible'
                },
                skipClone: true
            },
            exporting: {
                enabled: false
            },
            title: {
                text: ''
            }
            ,xAxis: {
                labels: {
                    enabled: false
                },
                title: {
                    text: null
                },
                startOnTick: false,
                endOnTick: false,
                tickPositions: [],
                visible:false,
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
            credits: {
                enabled: false
            },
            tooltip: {
                pointFormat: '{point.d}: {point.y:,.0f}',
                shared: true
            },
            plotOptions: {
                series: {
                    animation: false,
                    lineWidth: 1,
                    shadow: false,
                    states: {
                        hover: {
                            lineWidth: 1,
                            enabled: false
                        }
                    },
                    lineColor: '#00cc00',
                    lineWidth: 1,
                    fillOpacity: 0.5,
                    marker: {
                        radius: 1,
                        states: {
                            hover: {
                                radius: 2,
                                enabled: false
                            }
                        }
                    },
                },
                column: {
                    negativeColor: '#910000',
                    borderColor: 'silver'
                },area: {
                    lineColor: '#00cc00',
                    lineWidth: 1,
                    marker: {
                        lineWidth: 1,
                        lineColor: '#00cc00'
                    }
                }
            },
            series: [{
                name:'<?php echo $value["kpi_name"]; ?>',
                data: [<?php foreach ($body ['kpi_data'][$key] as $kkey => $kvalue ):?> { y:<?php echo $kvalue; ?>, d:'<?php echo  $body ['kpi_data']['text_dates'][$kkey]; ?>'},<?php endforeach;?>],
                color:'#f39c12'
            }]
        });

        <?php endforeach;?>

    });



</script>
<section>


    <?php

    $fTitle = "";
    $overview_logdate = date ( "d-M-Y", strtotime ( $body ['selected_date']) );
    $key = $body ['timing'];
    if ($key == "17" || $key == "31") {

        $fTitle = " (" . $this->util->getStartDateIn ( $overview_logdate, $key ) . "->" . $overview_logdate . ")";
    }
    ?>
    <div class="box box-default color-palette-box">
        <div class="box-header with-border dashboard-keykpis">
            <h3 class="box-title">
                <i class="fa fa-tag"></i> <?php echo "Key metrics for ". $body ['selected_date_text'] .$fTitle; ?>
            </h3>
        </div>
        <div class="box-body dashboard-keykpis">
            <div class="row">
                <?php foreach ($body ['kpi_data']["trend"] as $key => $value ):?>
                    <?php
                    $icon ="fa-users";
                    $valueText = number_format ( $value["selected_value"] );
                    $prevValueText = number_format ( $value["prev_value"] );
                    if (strpos($key, 'gr') !== false) {
                        $icon ="fa-dollar";
                        $valueText = number_format ( $value["selected_value"]  * 1.0 / 1000000, 2) . " mil";

                        $prevValueText = number_format ( $value["prev_value"]  * 1.0 / 1000000, 2). " mil";
                    }else if (strpos($key, 'nrr') !== false) {
                        $icon ="fa-history";
                    }else if (strpos($key, 'n') !== false) {
                        $icon ="fa-user-plus";
                    }

                    $trend_icon ="fa-caret-up text-green";
                    $percent = intval($value["trend"]);
                    $textTrend = $value["trend"] ."%";

                    ?>

                    <div class="col-sm-4 col-md-15">
                        <h4 class="text-left"><i class="fa <?php echo $icon; ?> green-text"></i> <?php echo $value["kpi_name"]; ?></h4>
                        <div class="color-palette-set box-kpi">
                            <div class="color-palette kpi">
                                <span><?php echo $valueText; ?></span>
                            </div>
                            <?php if($percent>0){?>
                                <div class="color-palette">
                                    <span><?php echo $textTrend;?> vs <?php echo $value["prev_date_text"]; ?></span>
                                </div>
                            <?php }?>
                            <div class="color-palette">
                                <span><?php echo $value["prev_date_text"]; ?>: <?php echo $prevValueText; ?> </span>
                            </div>
                            <div class="color-palette">
                                <div class="dashboard-kpi-trend" id="trend_<?php echo $key ?>"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer text-right">
            <a href="<?php echo site_url('kpi/compare'); ?>" class="uppercase"> <i class="fa fa-bar-chart"></i>More reports</a>
        </div>
    </div>

    <?php if($body ['has_os_data']!=null){  ?>
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-tag"></i> <?php echo "Report from ".  $body['os_start_time_text'] ." to " .$body['os_end_time_text']  ?>
                </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="chart" id="usermobile_<?php echo $body['timing']?>"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="chart" id="usermobile_pie_<?php echo $body['timing']?>"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="chart" id="revenuemobile_<?php echo $body['timing']?>"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="chart" id="revenuemobile_pie_<?php echo $body['timing']?>"></div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-right">
                <a href="<?php echo site_url('mobile/device-os'); ?>" class="uppercase"> <i class="fa fa-bar-chart"></i>Detail reports</a>
            </div>
        </div>
    <?php } ?>




    <?php if($body ['kpi_data']["gr".$body['suffix']]!=null){ ?>
    <div class="box box-default color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-tag"></i> <?php echo "Report from ".  $body['kpi_start_time_text'] ." to " .$body['kpi_end_time_text']  ?>
            </h3>
        </div>
        <div class="box-body">

            <div class="row">
                <div class="col-md-6">
                    <div class="chart" id="revenuekpi_<?php echo $body['timing']?>"></div>
                    <a class="btn btn-primary btn-sm pull-right" href="<?php echo site_url('kpi/revenue'); ?>">Details</a>
                </div>
                <?php } ?>
                <?php if($body ['kpi_data']["a".$body['suffix']]!=null){ ?>
                <div class="col-md-6">
                    <div class="chart" id="userkpi_<?php echo $body['timing']?>"></div>
                    <a class="btn btn-primary btn-sm pull-right" href="<?php echo site_url('kpi/user'); ?>">Details</a>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <!--
        <div class="box-footer text-right">
            <a href="<?php echo site_url('kpi/compare'); ?>" class="uppercase"> <i class="fa fa-bar-chart"></i>More reports</a>
        </div>
        -->
    </div>
<?php } ?>

</section>