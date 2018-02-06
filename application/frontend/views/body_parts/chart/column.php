<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 20/04/2016
 * Time: 09:17
 */


//var_dump($data);exit();

?>
<script type="text/javascript">
    $(function () {
        Highcharts.setOptions({
            colors: ['#7CB5EC', '#F56954', '#00A65A', '#C07BCC', '#C3C66C']
        });
        $('#<?php echo $id?>').highcharts({
            chart: {
                zoomType: 'x',
                type: 'column'
            },
            title: {
                text: '<?php echo $title ?>'
            },
            subtitle: {
                text: '<?php echo $subTitle ?>'
            },
            xAxis: [{
                categories: [<?php echo $xAxisCategories; ?>],
                crosshair: true
            }],
            labels: {
                items: [{
                    html: 'Total fruit consumption',
                    style: {
                        left: '50px',
                        top: '18px',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                    }
                }]
            },
            yAxis: [
                { // Primary yAxis
                    title: {
                        text: '<?php echo $yPrimaryAxisTitle?>',
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
                                return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " Tỷ";
                            } else if (this.value >= 1000000) {
                                this.value = this.value / 1000000;
                                return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " Triệu";
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
                        text: '<?php echo $ySecondaryAxisTitle?>',
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
                                return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " Tỷ";
                            } else if (this.value >= 1000000) {
                                this.value = this.value / 1000000;
                                return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " Triệu";
                            } else {
                                return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        },
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    opposite: true
                },

            ],
            tooltip: {
                shared: true
            },
            credits: {
                enabled: false
            },
            series: [
                <?php
                $i=0;
                foreach($data as $key => $value){
                    echo "{";
                    echo "color: Highcharts.getOptions().colors[".$i."],";
                    echo "name:'" .  $value['name'] . "',";
                    echo "data: [" . $value['data'] . "],";
                    echo "},";
                    $i++;
                }
                ?>
            ]
        });
    });
</script>
