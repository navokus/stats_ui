<?php
/**
 * Created by IntelliJ IDEA.
 * User: quangctn
 * Date: 11/10/2017
 * Time: 10:28
 */
?>
<script type="text/javascript">
    $(document).ready(function () {

        // Build the chart
        Highcharts.chart('<?php echo $id; ?>', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: '<?php echo $title; ?>'
            },
            tooltip: {
                pointFormat: 'Total: <b>{point.y}</b>'
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '{point.percentage:.1f} %',
                        distance: -30
                    },
                    showInLegend: true
                }
            },
            series: [{
                <?php
                echo "name: 'Ty le: ',";
                echo "colorByPoint: true,";
                echo "data:[";
                foreach ($data as $kpi => $data2) {
                    foreach ($data2 as $kpi2 => $data3) {
                        if (strcmp($kpi2, $kpi) == 0) {
                            foreach ($data3 as $os => $data4) {
                                echo "{";
                                echo "name:'" . $os . "',";
                                echo "y: " . $data4 . "";
                                echo "},";
                            }
                        }
                    }
                }
                echo "]";
                ?>
            }]
        });
    });
</script>