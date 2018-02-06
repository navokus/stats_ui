<?php

if(count($data) == 0){

} else {
?>
    <script type="text/javascript">
        $(function () {
            Highcharts.setOptions({
                colors: ['#F08080', '#20B2AA', '#778899', '#9370DB', '#3CB371', '#191970', '#FF4500',
                    '#DAA520', '#2F4F4F', '#B0C4DE', '#800000', '#808000', '#CD853F', '#708090',
                    '#5F9EA0', '#008B8B', '#FF8C00', '#2F4F4F', '#DAA520', '#CD5C5C',
                    '#DB7093', '#663399', '#4169E1', '#8B4513', '#4682B4', '#008080', '#40E0D0']
            });
            $('#<?php echo $id?>').highcharts({
                chart: {
                    height: 600,
                    type: 'column',
                    zoomType: 'xy',
                    animation: false
                },
                title: {
                    text: '<?php echo $title ?>',
                    x: -20 //center

                },
                subtitle: {
                    text: '<?php echo $subTitle ?>',
                    x: -20 //center
                },
                xAxis: {
                    categories: <?php echo "[";
                    foreach($days as $day){
                        echo "'" . $this->util->get_xcolumn_by_timming($day, $timing, true) . "',";
                    }
                    echo "]";
                    ?>,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '<?php echo $metric . " (" . $unit . ")"?>',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
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
                    headerFormat: '<b>{point.x}</b><br/>',
                    pointFormat: '{series.name}: {point.y:.,1f}<br/>'
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                credits: {
                    enabled: false
                },
                series: [
                    <?php
                    foreach($names as $name){
                        echo "{";
                        echo "name: '" ." " . $name . "',";
                        echo "data: [";
                        foreach ($days as $day){

                            if(isset($datas[$day][$name]))
                            {
                                echo $datas[$day][$name] . ",";
                            }else{
                                echo "0,";
                            }
                        }
                        echo "]";
                        echo "},";
                    }
                    ?>

                ]
            });



        });
    </script>
<?php }?>
