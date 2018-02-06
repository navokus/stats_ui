<div class="box box-solid bg-green-gradient">
    <div class="box-header">
        <h3 class="box-title">PAYING CHANNEL</h3>
    </div>
    <!-- /.box-header -->
    <form name="form" action="<?php echo site_url('Channel/') ?>" method="post">
        <div class="box-footer text-black" style="display: block;">
            <input type="hidden" name="gameCode" value="<?php echo $this->session->userdata('default_game') ?>">

            <div class="col-md-3">
                <select class="form-control" name="options">
                    <option value="4" <?php echo((4 == $post['options']) ? 'selected' : '') ?> >Chọn Ngày</option>
                    <option value="5" <?php echo((5 == $post['options']) ? 'selected' : '') ?> >Chọn Tuần</option>
                    <option value="6" <?php echo((6 == $post['options']) ? 'selected' : '') ?> >Chọn Tháng</option>
                </select>
            </div>
            <!-- /.col -->

            <div class="option_time option_disable hide">
                <div class="col-md-4">
                    <input autocomplete="off" class="form-control" type="text" disabled>
                </div>
                <!-- /.col -->

            </div>

            <div class="option_time option_day hide">
                <div class="col-md-4">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input autocomplete="off" class="form-control pull-right" type="text" id="dpd1" name="day"
                               value="<?php echo $post['day'] ?>">
                    </div>
                </div>
                <!-- /.col -->

            </div>

            <div class="option_time option_week hide">
                <div class="col-md-4">
                    <select class="form-control" id="wpw1" name="week">
                        <?php
                        foreach ($optionsWeek as $key => $value) {

                            if ($post['week'] == $key) {
                                $selected = ' selected ';
                            } else {
                                $selected = '';
                            }
                            echo "<option value='" . $key . "' " . $selected . ">" . $value . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <!-- /.col -->

            </div>

            <div class="option_time option_month ">
                <div class="col-md-4">
                    <select class="form-control" id="mpm1" name="month">
                        <?php
                        foreach ($optionsMonth as $key => $value) {
                            if ($post['month'] == $key) {
                                $selected = ' selected ';
                            } else {
                                $selected = '';
                            }
                            echo "<option value='" . $key . "' " . $selected . ">" . $value . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <!-- /.col -->
            </div>


            <div class="col-md-1">
                <button type="submit" class="btn btn-danger">Xem</button>
            </div>
            <!-- /.col -->

        </div>
    </form>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="container"></div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-money"></i> Dữ liệu chi tiết
                </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body text-center">

                <?php
                if(!$payMethod['channel']) {
                    echo '<div class="text-center">Không có dữ liệu.</div>';
                } else {
                    ?>

                    <table class="table table-bordered table-bordered-gray table-striped" id="table-transfer-detail">
                        <thead>
                        <tr>
                            <th class="text-center" style="border-right: 1px solid gray; width: 100px"  rowspan="2"><?php echo $time ?> / Kênh
                            </th>
                            <?php
                            foreach ($payMethod['channel'] as $value) :
                                ?>
                                <th class="text-center" colspan="4"><?php echo $value ?></th>
                                <?php
                            endforeach;
                            ?>
                        </tr>
                        <tr>
                            <?php
                            foreach ($payMethod['channel'] as $value) :
                                ?>
                                <th class="text-center">User</th>
                                <th class="text-center">Doanh thu</th>
                                <th class="text-center">User FristPay</th>
                                <th class="text-center">Doanh thu FirstPay</th>
                                <?php
                            endforeach;
                            ?>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $totalRevenue = $totalUserFirstPay = $totalRevenueFristPay = array();
                        foreach ($payMethod['data'] as $time => $value) :
                            ?>
                            <tr>
                                <th class="text-center"><?php echo $time; ?></th>
                                <?php
                                $data['columnX'][] = "'" . $time . "'";
                                foreach ($payMethod['channel'] as $channel) :
                                    ?>
                                    <td class="text-right"><?php echo number_format($value[$channel]['AccountTotal']) ?></td>
                                    <td class="text-right"><?php echo number_format($value[$channel]['RevenueTotal']) ?>đ</td>
                                    <td class="text-right"><?php echo number_format($value[$channel]['TotalAccountFirstCharge']) ?></td>
                                    <td class="text-right"><?php echo number_format($value[$channel]['TotalRevenueFirstCharge']) ?>đ</td>
                                    <?php
                                    $data['account'][$channel][] = intval($value[$channel]['AccountTotal']) ;
                                    $data['revenue'][$channel][] = intval($value[$channel]['RevenueTotal']);

                                    $totalRevenue[$channel] += $value[$channel]['RevenueTotal'];
                                    $totalUserFirstPay[$channel] += $value[$channel]['TotalAccountFirstCharge'];
                                    $totalRevenueFristPay[$channel] += $value[$channel]['TotalRevenueFirstCharge'];
                                endforeach;
                                ?>
                            </tr>
                            <?php
                        endforeach;
                        ?>
                        <tr style="background-color: lightgrey">
                            <th class="text-center">Tổng</th>
                            <?php
                                foreach ($payMethod['channel'] as $channel) :
                            ?>
                            <th></th>
                            <th class="text-right"><?php echo number_format($totalRevenue[$channel]) ?></th>
                            <th class="text-right"><?php echo number_format($totalUserFirstPay[$channel]) ?></th>
                            <th class="text-right"><?php echo number_format($totalRevenueFristPay[$channel]) ?></th>
                            <?php
                                endforeach;
                            ?>
                        </tr>
                        </tbody>
                    </table>
                    <?php
                }
                ?>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('#container').highcharts({
            chart: {
                type: 'column'
            },
            colors: ['#f39c12', '#dd4b39', '#00a65a', '#3c8dbc', '#00c0ef', '#D81B60', '#605ca8', '#f56954', '#001F3F', '#39CCCC'],
            title: {
                text: 'Biểu đồ kênh chi trả'
            },
//            subtitle: {
//                text: ''
//            },
            xAxis: [{
                categories: [<?php echo implode(',', $data['columnX']); ?>],
            }],
            yAxis: [
                { // Primary yAxis
                    labels: {
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
                        }
                    },
                    title: {
                        text: 'DOANH THU'
                    }
                }
            ],
            tooltip: {
                shared: true
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                }
            },
            series: [
                <?php
                    foreach ($data['revenue'] as $channel => $value) :
                ?>
                {
                    name: '<?php echo $channel; ?>',
                    data: [<?php echo implode(',', $value); ?>],
                },
                <?php
                    endforeach;
                ?>
            ]
        });
    });
</script>