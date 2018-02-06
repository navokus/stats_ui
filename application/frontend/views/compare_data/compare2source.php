<script type="text/javascript">
    $(function () {
        Highcharts.setOptions({
            colors: ['#7CB5EC', '#F56954', '#7CB5EC', '#F56954', '#C3C66C']
        });
        $('#compare').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: '<?php echo $title ?>'
            },
            subtitle: {
                text: '<?php echo $subTitle ?>'
            },
            xAxis: {
                categories: [
                    <?php echo $xAxisCategories; ?>
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: '<?php echo $yPrimaryAxisTitle?>',
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
                shared: true,
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
                foreach($data as $key => $value){
                    echo "{";
                    echo "name:'" .  $value['name'] . "',";
                    echo "data: [" . $value['data'] . "],";
                    echo "},";
                }
                ?>

//                {
//                name: 'Tokyo',
//                data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
//
//            }, {
//                name: 'New York',
//                data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]
//
//            }, {
//                name: 'London',
//                data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]
//
//            }, {
//                name: 'Berlin',
//                data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]
//
//            }


            ]
        });



    });
</script>


<script type="text/javascript">
    $(document).ready(function() {
        var qa_report = $('#compare_report').DataTable({
            paging: false,
            searching: false,
            ordering: false,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'excel',title: 'QaDetailTables'},
                'copy'
            ]
        });
        new $.fn.dataTable.FixedColumns(qa_report, {
            heightMatch: 'none',
            leftColumns: 2
        });


    } );

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
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-th"></i> Active User Data</h3>
                    <div class="box-tools">
                        <a class="btn btn-box-tool" href="#" title="Copy to clipboard!"  id="copy">
                            <img src="<?php echo base_url('public/frontend/dist/img/copy.png'); ?>" width="22px" height="22px" />
                        </a>
                        <a class="btn btn-box-tool" href="#" title="Download excel file!" id="downloadExcel">
                            <img src="<?php echo base_url('public/frontend/dist/img/export.gif'); ?>" width="20px" height="20px" />
                        </a>
                    </div>
                </div>
                <div class="box-body text-center first">
                    <div class="table-responsive kpi-table">
                        <div id="all_kpi_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <table class ="table table-striped table-bordered" id = "compare_report"width="100%">
                                <thead class="thead-inverse">
                                <tr>
                                    <th class="text-left" style="width: auto;">Active User</th>
                                    <?php
                                    foreach($datatable as $key => $value){
                                        ?>
                                        <th class="text-left" style="width: auto;"><?php echo $key; ?></th>
                                    <?php }?>
                                </tr>
                                </thead>
                                <tr>
                                    <td class="text-left">ingame</td>
                                    <?php
                                    foreach($datatable as $key => $value){
                                        ?>
                                        <td class="text-left"><?php echo $value['ingame'] ?></td>
                                    <?php }

                                    ?>
                                </tr>

                                <tr>
                                    <td class="text-left">sdk</td>
                                    <?php
                                    foreach($datatable as $key => $value){
                                        ?>
                                        <td class="text-left"><?php echo $value['sdk'] ?></td>
                                    <?php }

                                    ?>
                                </tr>
                            </table>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- /.tab-content -->
        </div>
    </div>
<?php }?>