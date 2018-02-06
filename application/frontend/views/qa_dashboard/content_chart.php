<script type="text/javascript">
    Highcharts.setOptions({
        lang: {
            thousandsSep: ','
        }
    });

    $(function () {
        <?php foreach ($datachartRev as $key => $data ):?>
        $('#chart_<?php echo $key?>').highcharts({
            chart: {
                type: 'column'
            },
            colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
            title: {
                text: '<?php if(strpos($key, 'game') !== false){
                    echo "Top game by revenue";
                }else if (strpos($key, 'mobile') !== false){
                    echo "Top mobile game";
                }
                    ?>'
            },
            subtitle: {
                text:  '<?php echo $fromtodate?>'
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '1.5em',
                        fontFamily: 'Verdana, sans-serif',
                        color: '#111'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Revenue (in millions VND)'
                }, labels: {
                    format: '{value:,.0f} mil',
//                    formatter: function () {
//                        if (this.value >= 1000000000) {
//                            this.value = this.value / 1000000000;
//                            return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " Bil";
//                        } else if (this.value >= 1000000) {
//                            this.value = this.value / 1000000;
//                            return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " Mil";
//                        } else {
//                            return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
//                        }
//                    },
                    style: {
                        color: Highcharts.getOptions().colors[0],
                        fontSize: '1em',
                        fontFamily: 'Verdana, sans-serif',
                        color: '#111'

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
                        format: '{point.y:,.0f} mil',

//                        formatter: function () {
//                            if (this.value >= 1000000000) {
//                                this.value = this.value / 1000000000;
//                                return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " Bil";
//                            } else if (this.value >= 1000000) {
//                                this.value = this.value / 1000000;
//                                return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " Mil";
//                            } else {
//                                return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
//                            }
//                        },
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
                    <?php foreach ($data as $gamecode => $value ):?>
                    [<?php
                        $value = round($value/1000000,2);
                        echo "'".$gamecode."'".", ".$value?>],
                    <?php endforeach;?>
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
                        color: '#111'
                    }
                }
            }]
        });
        <?php endforeach;?>

        <?php foreach ($datachartOS as $key => $data ):?>

        $('#mobile_<?php echo $key?>').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: '<?php
                    if(strpos($key, "a") !== FALSE){
                        echo "Active by Os";
                    }else if (strpos($key, "gr")!== FALSE){
                        echo "Revenue by Os";
                    }
                    ?>'
            },subtitle: {
                text:  '<?php echo $fromtodate?>'
            },
            xAxis: {
                categories: [
                    <?php
                    foreach ($data as $logdate => $datachart):
                        echo "'".$logdate."',"  ;
                    endforeach;
                    ?>
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: '<?php
                        if(strpos($key, "a") !== FALSE){
                            echo "Number of Active Users";
                        }else if (strpos($key, "gr")!== FALSE){
                            echo "Number of Revenue Users";
                        }
                        ?>'

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
            credits: {
                enabled: false
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
                    data:[<?php
                        foreach ($data as $logdate => $datachart):
                            echo $datachart["ios"].",";
                        endforeach;
                        ?>
                    ]
                },
                {
                    name: 'Android',
                    color:'#00cc00',
                    data: [<?php
                        foreach ($data as $logdate => $datachart):
                            echo $datachart["android"].",";
                        endforeach;
                        ?>
                    ]
                },
                {
                    name: 'Other',
                    color:'#669999',
                    data: [<?php
                        foreach ($data as $logdate => $datachart):
                            echo $datachart["other"].","  ;
                        endforeach;
                        ?>
                    ]
                }
            ]
        });

        <?php endforeach;?>

        <?php foreach ($datachartOS as $key => $data ):?>

        $('#mobile_pie_<?php echo $key?>').highcharts({
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
                text:
                    '<?php
                        if(strpos($key, "a") !== FALSE){
                            echo "Total Active by Os";
                        }else if (strpos($key, "gr")!== FALSE){
                            echo "Total Revenue by Os";
                        }
                        ?>'
            },subtitle: {
                text: '<?php echo $fromtodate?>'
            },
            credits: {
                enabled: false
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
                    <?php $total_a1=0;  foreach ($data as $logdate => $datachart):?> <?php $total_a1+= $datachart["ios"]; ?><?php endforeach; echo "y:".$total_a1;?>
                }, {
                    name: 'Android',
                    color:'#00cc00',
                    <?php $total_a1=0;  foreach ($data as $logdate => $datachart):?> <?php $total_a1+= $datachart["android"]; ?><?php endforeach; echo "y:".$total_a1;?>
                }, {
                    name: 'Other',
                    color:'#669999',
                    <?php $total_a1=0;  foreach ($data as $logdate => $datachart):?> <?php $total_a1+= $datachart["other"]; ?><?php endforeach; echo "y:".$total_a1;?>
                }]
            }]
        });
        <?php endforeach;?>


        <?php foreach ($datachartGameType as $key => $data ):?>

        $('#gameType_<?php echo $key?>').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: '<?php
                    if(strpos($key, "a") !== FALSE){
                        echo "Active by Game Type";
                    }else if (strpos($key, "gr")!== FALSE){
                        echo "Revenue by Game Type";
                    }
                    ?>'
            },subtitle: {
                text: '<?php echo $fromtodate?>'
            },
            xAxis: {
                categories: [
                    <?php
                    foreach ($data as $logdate => $datachart):
                        echo "'".$logdate."',"  ;
                    endforeach;
                    ?>
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: '<?php
                        if(strpos($key, "a") !== FALSE){
                            echo "Number of Active Users";
                        }else if (strpos($key, "gr")!== FALSE){
                            echo "Number of Revenue Users";
                        }
                        ?>'

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
            credits: {
                enabled: false
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
                    name: 'Client',
                    color:'#cc9900',
                    data:[<?php
                        foreach ($data as $logdate => $datachart):
                            if(is_null($datachart["1"]) or empty($datachart["1"])){
                                $datachart["1"]=0;
                            }
                            echo $datachart["1"].",";
                        endforeach;
                        ?>
                    ]
                },
                {
                    name: 'Mobile',
                    color:'#00cc00',
                    data: [<?php
                        foreach ($data as $logdate => $datachart):
                            if(is_null($datachart["2"]) or empty($datachart["2"])){
                                $datachart["2"]=0;
                            }
                            echo $datachart["2"].",";
                        endforeach;
                        ?>
                    ]
                },
                {
                    name: 'Web',
                    color:'#669999',
                    data: [<?php
                        foreach ($data as $logdate => $datachart):
                            if(is_null($datachart["3"]) or empty($datachart["3"])){
                                $datachart["3"]=0;
                            }
                            echo $datachart["3"].","  ;
                        endforeach;
                        ?>
                    ]
                }
            ]
        });

        <?php endforeach;?>

<!--        --><?php //foreach ($datachartGameType as $key => $data ):?>
//
//        $('#gameType_pie_<?php //echo $key?>//').highcharts({
//            chart: {
//                plotBackgroundColor: null,
//                plotBorderWidth: null,
//                plotShadow: false,
//                type: 'pie',
//                spacingBottom: 25,
//                spacingTop: 25,
//                spacingLeft: 10,
//                spacingRight: 10,
//            },
//            title: {
//                text:
//                    '<?php
//                        if(strpos($key, "a") !== FALSE){
//                            echo "Total Active by Game Type";
//                        }else if (strpos($key, "gr")!== FALSE){
//                            echo "Total Revenue by Game Type";
//                        }
//                        ?>//'
//            },subtitle: {
//                text: '<?php //echo $fromtodate?>//'
//            },
//            credits: {
//                enabled: false
//            },
//            tooltip: {
//                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
//            },
//            plotOptions: {
//                pie: {
//                    allowPointSelect: true,
//                    cursor: 'pointer',
//                    dataLabels: {
//                        enabled: true,
//                        distance: -30,
//                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
//                        style: {
//                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
//                        }
//                    },
//                    showInLegend: true
//                }
//            },
//
//            series: [{
//                name: 'Game Type',
//                data: [{
//                    name: 'Client',
//                    color:'#cc9900',
//                    <?php //$total_a1=0;  foreach ($data as $logdate => $datachart):?><!-- --><?php //$total_a1+= $datachart["1"]; ?><!----><?php //endforeach; echo "y:".$total_a1;?>
//                }, {
//                    name: 'Mobile',
//                    color:'#00cc00',
//                    <?php //$total_a1=0;  foreach ($data as $logdate => $datachart):?><!-- --><?php //$total_a1+= $datachart["2"]; ?><!----><?php //endforeach; echo "y:".$total_a1;?>
//                }, {
//                    name: 'Web',
//                    color:'#669999',
//                    <?php //$total_a1=0;  foreach ($data as $logdate => $datachart):?><!-- --><?php //$total_a1+= $datachart["3"]; ?><!----><?php //endforeach; echo "y:".$total_a1;?>
//                }]
//            }]
//        });
//        <?php //endforeach;?>


        <?php foreach ($datachartGameType as $key => $data ):?>

        $('#gameType_pie_<?php echo $key?>').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Browser market shares. January, 2015 to May, 2015'
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


                        format: '<b>{point.name}</b>: {point.y:.1f} . {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        },
                        connectorColor: 'silver'
                    }
                }
            },
            series: [{
                name: 'Brands',
                data: [{
                    name: 'Client',
                    color:'#cc9900',
                    <?php $total_a1=0;  foreach ($data as $logdate => $datachart):?> <?php $total_a1+= $datachart["1"]; ?><?php endforeach; echo "y:".$total_a1;?>
                }, {
                    name: 'Mobile',
                    color:'#00cc00',
                    <?php $total_a1=0;  foreach ($data as $logdate => $datachart):?> <?php $total_a1+= $datachart["2"]; ?><?php endforeach; echo "y:".$total_a1;?>
                }, {
                    name: 'Web',
                    color:'#669999',
                    <?php $total_a1=0;  foreach ($data as $logdate => $datachart):?> <?php $total_a1+= $datachart["3"]; ?><?php endforeach; echo "y:".$total_a1;?>
                }]
            }]
        });

        <?php endforeach;?>
    });





</script>

<div class="row" style="float: none; margin: 0 auto;">
    <div class="box">
            <div class="box-body">
                <?php foreach ($datachartRev as $key => $data ):?>
                <div class="col-md-6 col-sm-6 col-xs-9">
                    <div id="chart_<?php echo $key?>"></div>
                </div>
                    <div class="col-md-6 col-sm-6 col-xs-9">
                        <div id="chart_<?php echo $key?>"></div>
                    </div>
                <?php endforeach;?>

            </div>
    </div>
</div>


<div class="row" style="float: none; margin: 0 auto;">
    <div class="box">
        <div class="box-body">
            <?php foreach ($datachartOS as $key => $data ):?>
                <div class="row" style="float: none; margin: 0 auto;">
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div id="mobile_<?php echo $key?>"></div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div id="mobile_pie_<?php echo $key?>"></div>
                    </div>
                </div>
            <?php endforeach;?>

            <?php foreach ($datachartGameType as $key => $data ):?>
                <div class="row" style="float: none; margin: 0 auto;">
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div id="gameType_<?php echo $key?>"></div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div id="gameType_pie_<?php echo $key?>"></div>
                    </div>
                </div>
            <?php endforeach;?>


        </div>
    </div>
</div>