<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 25/04/2016
 * Time: 14:49
 */
?>
<script type="text/javascript">
    $(function () {
        Highcharts.setOptions({
            colors: ['#058DC7', '#6AF9C4', '#ED561B'],
            custom1: "aaabbb"
        });
        $('#<?php echo $id ?> ').highcharts({
            chart: {
                type: 'bar',
                height: 500
            },
            title: {
                text: '<?php echo $title ?>'
            },
            subtitle: {
                text: ''
            },
            xAxis: [{

                categories:[<?php echo $categories ?>],
                title: {
                    text: null
                }
            }],
            yAxis: {
                min: 0,
                title: {
                    text: '',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                shared: true
            },
            plotOptions: {
                bar: {
                    dataLabels:{
                        enabled:true,
                        formatter:function() {
                            var data = this.series.data
                            var total = 0;
                            for (var i=0;i<data.length;i++){
                                total+= data[i].y
                            }
                            var pcnt = (this.y / total) * 100;
                            var text = this.y + " (" + Highcharts.numberFormat(pcnt) + '%)';
                            return text;
                        }
                    }
                }
            },

            credits: {
                enabled: false
            },
            series:[
                {name: 'Total Revenue',
                <?php
                echo "data: [" . $total . "]";
                echo "},";
                ?>

            ]
        });
    });
</script>
