<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
            </div>
            <div class="box-body">
                <?php foreach ($datagames as $gameCode => $kpiValue):?>
                <div class="row col-md-12" style="float: none; margin: 0 auto;">
                    <div class="box">
                        <div class="box-header with-border dashboard-keykpis">
                            <h3 class="box-title">
                                <i class="fa fa-tag"></i>
                               <?php echo $gameNames[$gameCode]."(".$gameCode.")"; ?>
                            </h3>
                        </div>
                        <div class="box-body dashboard-keykpis">
                            <?php foreach ($kpiValue as $kpikey => $value):
                                if ($kpikey == "a1") {
                                   $namekpi = " Active Users";
                                    $icon ="fa-users";
                                    $valueCurrent = number_format( $value["currentValue"] );
                                    $valueYesterDay = number_format( $value["yesterday"] );
                                }else if($kpikey == "pu1"){
                                    $namekpi = "PayingUsers";
                                    $icon ="fa-users";
                                    $valueCurrent = number_format( $value["currentValue"] );
                                    $valueYesterDay = number_format( $value["yesterday"] );
                                }else if ($kpikey == "gr1"){
                                    $namekpi = "Revenue (VND)";
                                    $icon ="fa-dollar";
                                    $valueCurrent = number_format ( $value["currentValue"]  * 1.0 / 1000000, 2) . " mil";
                                    $valueYesterDay = number_format ( $value["yesterday"]  * 1.0 / 1000000, 2). " mil";
                                }else if ($kpikey == "n1"){
                                    $namekpi = "New Users";
                                    $icon ="fa-user-plus";
                                    $valueCurrent = number_format( $value["currentValue"] );
                                    $valueYesterDay = number_format( $value["yesterday"] );
                                }else if ($kpikey == "npu1"){
                                    $namekpi = "New PayingUsers";
                                    $icon ="fa-user-plus";
                                    $valueCurrent = number_format( $value["currentValue"] );
                                    $valueYesterDay = number_format( $value["yesterday"] );
                                }
                                ?>

                                <div class="col-sm-4 col-md-15">
                                    <h4 class="text-left"><i
                                          class="fa <?php echo $icon; ?> green-text"></i> <?php echo $namekpi; ?>
                                    </h4>
                                    <div class="color-palette-set box-kpi">
                                        <div class="color-palette kpi">
                                            <span><?php echo $valueCurrent?></span>
                                        </div>
                                        <div class="color-palette">
                                            <span><?php echo $value['ratioYesterday']?> vs Previous day</span>
                                        </div>
                                        <div class="color-palette">
                                            <span>Previous day: <?php echo $valueYesterDay?> </span>
                                        </div>
                                        <div class="color-palette">
                                            <div class="dashboard-kpi-trend" id="trend_<?php echo $gameCode.$kpikey?>"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(function () {
        <?php foreach ($datachart as $key => $data ):?>
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
            , xAxis: {
                labels: {
                    enabled: false
                },
                title: {
                    text: null
                },
                startOnTick: false,
                endOnTick: false,
                tickPositions: [],
                visible: false,
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
                }, area: {
                    lineColor: '#00cc00',
                    lineWidth: 1,
                    marker: {
                        lineWidth: 1,
                        lineColor: '#00cc00'
                    }
                }
            },
            series: [{
                name: 'SparklineChart',
                data: [<?php foreach ($data as $date =>$value):?>{y: <?php if(is_null($value) || empty($value)){$value=0;}; echo $value?>, d:<?php $dateformat = date('d/m/Y',strtotime($date)); echo "'".$dateformat."'"?>},<?php endforeach;?>],
                color: '#f39c12'
            }]
        });

        <?php endforeach;?>

    });



</script>


