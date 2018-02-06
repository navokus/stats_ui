<script type="text/javascript">
    $(function () {
        Highcharts.setOptions({
            colors: ['#7CB5EC', '#F56954', '#7CB5EC', '#F56954', '#C3C66C']
        });
        $('#compare').highcharts({

            chart: {
                /* alignTicks: false, */
                zoomType: 'xy'
            },
            title: {
                text: '<?php echo $title ?>'
            },
            subtitle: {
                text: '<?php echo $subTitle ?>'
            },
            xAxis: [{
                categories: [<?php echo $xAxisCategories; ?>]
            }],
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
                        text: '<?php echo $ySecondaryAxisTitle?>',
                        style: {
                            color: Highcharts.getOptions().colors[1]
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
                }

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
                    echo "type:'" .  $value['type'] . "',";
                    echo "yAxis:" .  $i . ",";
                    echo "data: [" . $value['data'] . "],";
                    echo "lineWidth: 2";
                    echo "},";
                    $i++;
                }
                ?>
            ]
        });
    });
</script>




<?php if($checkTime==false) {

    echo "<p>Hiện dữ liệu không được chọn quá 30 ngày 30 tuần hoặc 30 tháng vui lòng chọn lại,
    hoặc liên hệ <b>[canhtq@vng.com.vn or vinhdp@vng.com.vn or tuonglv@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";

}else {?>

<div class="row" style="float: none; margin: 0 auto;">
    <div class="box">
        <div class="box-body">
            <div class="box-header with-border">
                <h3 class="box-title">Compare-Data</h3>
            </div>

            <div class="row" style="float: none; margin: 0 auto;">
                <div class="col-md-12 col-sm-12 col-xs-18">
                    <div id="compare"></div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php }?>