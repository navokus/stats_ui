<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 25/04/2016
 * Time: 10:41
 */
?>

<script type="text/javascript">
    $(function () {
        // Radialize the colors
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

        $('#<?php echo $id ?>').highcharts({
            colors: ['#7CB5EC', '#00a65a', '#f56954', "#FFBF00", "#8A4B08"],
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: '<?php echo $title ?>'
            },
            subtitle: {
                text: '<?php echo $subTitle ?>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format:   '{point.percentage:.1f} %' ,
                        distance: -30
                    },
                    showInLegend: true
                }
            },
            tooltip: {
                pointFormat: 'Total: <b>{point.y}</b>'
            },
            credits: {
                enabled: false
            },
            series: [{
                <?php
                echo "name: 'Ty le: ',";
                echo "colorByPoint: true,";
                echo "data:[";
                foreach($data as $key => $value){
                    echo "{";
                    echo "name:'" .  strtoupper($key) . "',";
                    echo "y: " . $value . "";
                    echo "},";
                }
                echo "]";
                ?>
            }]
        });
    });
</script>

