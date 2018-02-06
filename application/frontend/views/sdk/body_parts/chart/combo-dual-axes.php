<?php
/**
 * Created by IntelliJ IDEA.
 * User: quangctn
 * Date: 11/10/2017
 * Time: 10:28
 */
?>
<script type="text/javascript">
    $(function () {
        Highcharts.chart('<?php echo $id; ?>', {
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: '<?php echo $title ?>'
            },
            subtitle: {
                text: '<?php echo $substitle ?>'
            },
            xAxis: [{

                categories: [
                    <?php foreach ($date as $key => $value) {
                    echo "'";
                    echo $value;
                    echo "'";
                    if (!(count($date) == $key)){
                        echo ",";
                    }
                } ?>],
                crosshair: true
            }],
            yAxis: [{ // Primary yAxis
                labels: {
                    format: '',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: 'Active User',
                    style: {
                        color: Highcharts.getOptions().colors[1]
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

            }, { // Secondary yAxis
                title: {
                    text: 'Revenue',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                min:0,
                labels: {
                    // format: '{value} ',
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
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                opposite: true
            }],
            tooltip: {
                shared: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Revenue',
                type: 'column',
                yAxis: 1,
                data: [
                    <?php foreach ($data_chart[$lstKpi[0]] as $key => $value){
                        echo $value;
                        echo ",";
                }
                    ?>
                ],
                tooltip: {
                    valueSuffix: ' vnd'
                }

            }, {
                name: 'Active User',
                type: 'spline',
                data: [
                    <?php foreach ($data_chart[$lstKpi[1]] as $key => $value){
                    echo $value;
                    echo ",";
                }
                    ?>
                ],
                tooltip: {
                    valueSuffix: ''
                }
            }]
        });
    });
</script>
