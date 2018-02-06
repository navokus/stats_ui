<!--
<section>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-dashboard" name="overview"></i>  <?php echo $section_name ?></h3>
                </div>

            </div>
        </div>
    </div>
</section>
-->
<div id="container_syz"></div>
<script type="text/javascript">
    $(function () {
        /**
         * In order to synchronize tooltips and crosshairs, override the
         * built-in events with handlers defined on the parent element.
         */
        Highcharts.setOptions({
            colors: ['#7CB5EC', '#F56954', '#7CB5EC', '#F56954', '#C3C66C']
        });


        $('#container_syz').bind('mousemove touchmove touchstart', function (e) {
            var chart,
                point,
                i,
                event;

            for (i = 4; i < Highcharts.charts.length; i = i + 1) {
                chart = Highcharts.charts[i];
                event = chart.pointer.normalize(e.originalEvent); // Find coordinates within the chart
                point = chart.series[0].searchPoint(event, true); // Get the hovered point

                if (point) {
                    point.highlight(e);
                }
            }
        });
        /**
         * Override the reset function, we don't need to hide the tooltips and crosshairs.
         */
        Highcharts.Pointer.prototype.reset = function () {
            return undefined;
        };

        /**
         * Highlight a point by showing tooltip, setting hover state and draw crosshair
         */
        Highcharts.Point.prototype.highlight = function (event) {
            this.onMouseOver(); // Show the hover marker
            this.series.chart.tooltip.refresh(this); // Show the tooltip
            this.series.chart.xAxis[0].drawCrosshair(event, this); // Show the crosshair
        };

        /**
         * Synchronize zooming through the setExtremes event handler.
         */
        function syncExtremes(e) {
            var thisChart = this.chart;

            if (e.trigger !== 'syncExtremes') { // Prevent feedback loop
                Highcharts.each(Highcharts.charts, function (chart) {
                    if (chart !== thisChart) {
                        if (chart.xAxis[0].setExtremes) { // It is null while updating
                            chart.xAxis[0].setExtremes(e.min, e.max, undefined, false, { trigger: 'syncExtremes' });
                        }
                    }
                });
            }
        }

// Get the data. The contents of the data file can be viewed at
// https://github.com/highcharts/highcharts/blob/master/samples/data/activity.json
//
        $.getJSON('<?php echo base_url()?>index.php/Behavior/get_json_file?game_code=<?php echo $gameCode?>&server_id=<?php echo $server_id?>&report_date=<?php echo $reportDate?>', function (activity) {
            $.each(activity.datasets, function (i, dataset) {

// Add X values
                dataset.data = Highcharts.map(dataset.data, function (val, j) {
                    return [activity.xData[j], val];
                });

                $('<div class="chart">')
                    .appendTo('#container_syz')
                    .highcharts({
                        chart: {
                            marginLeft: 70, // Keep all charts left aligned
                            //spacingTop: 20,
                            //spacingBottom: 20,
                            height: 200
                        },
                        title: {
                            text: dataset.name,
                            align: 'left',
                            margin: 0,
                            x: 100
                        },
                        exporting: {
                            enabled: false
                        },
                        credits: {
                            enabled: false
                        },
                        legend: {
                            enabled: false
                        },
                        xAxis: {
                            crosshair: true,
                            events: {
                                setExtremes: syncExtremes
                            },
                            labels: {
                                formatter: function () {
                                    return this.value;
                                    var currentH = this.value
                                    var newLable=""
                                    if(currentH < 10){
                                        newLable = "0" + currentH + ":59"
                                    }else{
                                        newLable = currentH + ":59"
                                    }
                                    return newLable;
                                }
                                //format: '{value+}H'
                            },
                            tickInterval: 1
                        },

                        yAxis: {
                            title: {
                                text: null
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
                        tooltip: {
                            positioner: function () {
                                return {
                                    x: this.chart.chartWidth - this.label.width - 10, // right aligned
                                    y: -1 // align to title
                                };
                            },
                            borderWidth: 0,
                            backgroundColor: 'none',
                            pointFormat: '{point.y}',
                            headerFormat: '',
                            shadow: false,
                            style: {
                                fontSize: '18px'
                            },
                            formatter: function() {
                                var current = this.point.y;
                                var f1 = current.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                return f1 + " / " + dataset.total_value + " " + dataset.unit
                            },
                            valueDecimals: dataset.valueDecimals
                        },
                        series: [{
                            data: dataset.data,
                            name: dataset.name,
                            type: dataset.type,
                            color: Highcharts.getOptions().colors[i],
                            fillOpacity: 0.3,
                            tooltip: {
                                valueSuffix: ' ' + dataset.unit
                            }
                        }]
                    });
            });
        });

    });

</script>