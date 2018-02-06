<div class="box box-solid bg-green-gradient">
    <div class="box-header">
        <h3 class="box-title">DOANH THU</h3>
    </div>
    <!-- /.box-header -->
    <form name="form" action="" method="post">
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
                        <input autocomplete="off" class="form-control pull-right" type="text" id="dpd2" name="day[2]" value="<?php echo $post['day']['2'] ?>">
                    </div>
                </div><!-- /.col -->
                <div class="col-md-4">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input autocomplete="off" class="form-control pull-right" type="text" id="dpd1" name="day[1]" value="<?php echo $post['day']['1'] ?>">
                    </div>
                </div>
                <!-- /.col -->
            </div>

            <div class="option_time option_week hide">
                <div class="col-md-4">
                    <select class="form-control" id="wpw2" name="week[2]">
                        <?php
                        foreach ($optionsWeek as $key => $value) {

                            if ($post['week']['2'] == $key) {
                                $selected = ' selected ';
                            } else {
                                $selected = '';
                            }

                            echo "<option value='". $key ."' ". $selected .">". $value ."</option>";
                        }
                        ?>
                    </select>
                </div><!-- /.col -->
                <div class="col-md-4">
                    <select class="form-control" id="wpw1" name="week[1]">
                        <?php
                        foreach ($optionsWeek as $key => $value) {

                            if ($post['week']['1'] == $key) {
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
                    <select class="form-control" id="mpm2" name="month[2]">
                        <?php
                        foreach ($optionsMonth as $key => $value) {

                            if ($post['month'][2] == $key) {
                                $selected = ' selected ';
                            } else {
                                $selected = '';
                            }

                            echo "<option value='". $key ."' ". $selected .">". $value ."</option>";
                        }
                        ?>
                    </select>
                </div><!-- /.col -->
                <div class="col-md-4">
                    <select class="form-control" id="mpm1" name="month[1]">
                        <?php
                        foreach ($optionsMonth as $key => $value) {
                            if ($post['month'][1] == $key) {
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
    <div class="col-md-12">
        <div id="arppu"></div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('#container').highcharts({
            colors: ['#00a65a', '#f39c12', '#7CB5EC', '#f56954'],
            chart: {
                zoomType: 'x'
            },
            title: {
                text: '<?php echo $title ?>'
            },
            subtitle: {
                text: '<?php echo $subTitle ?>'
            },
            xAxis: [{
                categories: [<?php echo $columnX; ?>],
                crosshair: true
            }],
            yAxis: [
                { // Secondary yAxis
                    title: {
                        text: 'Account',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    labels: {
                        // format: '{value}',
                        formatter: function () {
                            return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        },
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    opposite: true
                },
                { // Primary yAxis
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
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    title: {
                        text: 'DOANH THU',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    }
                }
            ],
            tooltip: {
                shared: true
            },
            series: [
                {
                    name: 'Doanh Thu',
                    type: 'column',
                    yAxis: 1,
                    data: [<?php echo $dataRevenue; ?>],
                    tooltip: {
                        valueSuffix: ' VND'
                    }

                }, {
                    name: 'Account chi trả',
                    type: 'spline',
                    data: [<?php echo $dataUser; ?>],
                    tooltip: {
                        valueSuffix: ''
                    },
                    lineWidth: 3,

                }
            ]
        });

        // chart arrpu
        $('#arppu').highcharts({
            colors: ['#00a65a', '#f39c12', '#7CB5EC', '#f56954'],
            chart: {
                zoomType: 'x'
            },
            title: {
                text: '<?php echo 'Biểu đồ ARPPU' ?>'
            },
//            subtitle: {
//                text: ''
//            },
            xAxis: [{
                categories: [<?php echo $columnX; ?>],
                crosshair: true
            }],
            yAxis: [

                { // Secondary yAxis
                    title: {
                        text: 'Account',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    labels: {
                        // format: '{value}',
                        formatter: function () {
                            return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        },
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    opposite: true
                },
                { // Primary yAxis
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
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    title: {
                        text: 'DOANH THU',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    }
                }
            ],
            tooltip: {
                shared: true
            },
            series: [{
                name: 'ARPPU',
                type: 'column',
                yAxis: 1,
                data: [<?php echo $dataARPPU; ?>],
                tooltip: {
                    valueSuffix: ' VND'
                }

            }, {
                name: 'Account chi trả',
                type: 'spline',
                data: [<?php echo $dataUser; ?>],
                tooltip: {
                    valueSuffix: ''
                },
                lineWidth: 3,

            }
            ]
        });
    });
</script>


<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-th"></i> Dữ liệu chi tiết</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body text-center">

                <table class="table table-bordered table-bordered-gray table-striped" id="table-arppu">
                    <thead>
                    <tr>
                        <th class="text-center" style="border-right: 1px solid gray;"><?php echo $time ?></th>
                        <th class="text-center">User chi trả</th>
                        <th class="text-center">Doanh Thu</th>
                        <th class="text-center">ARPPU</th>
                        <th class="text-center">User chi trả lần đầu</th>
                        <th class="text-center">Doanh Thu chi trả lần đầu</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php
                    $totalRevenue = $totalAccountFirstCharge = $totalRevenueFristCharge = 0;
                    foreach ($data as$value) :
                        if ($value) {
                            $displayDate = "";
                            $date = $value['CalculateValue'] ;
                            switch($timing) {
                                case "daily":
                                    $displayDate = date("d/m/Y", strtotime($date));
                                    break;
                                case "weekly":
                                    $partWeek = explode("-", $date);

                                    if (count($partWeek) == 2) {
                                        $displayDate = $partWeek[1] . " năm " . $partWeek[0];
                                    }
                                    break;
                                case "monthly":
                                    $partMonth = explode("-", $date);

                                    if (count($partMonth) == 2) {
                                        $displayDate = $partMonth[1] . " năm " . $partMonth[0];
                                    }
                                    break;
                                default:
                                    break;
                            }

                            ?>
                            <tr>
                                <td class="text-center"
                                    style="border-right: 1px solid gray;"><?php echo $value['CalculateValue'] ?></td>
                                <td class="text-right"><a  class="export" href="javascript:;" data-description="Danh sach tài khoản chi trả <?php echo $time . " " .$displayDate  ?>" data-url="<?php echo base_url("/index.php/Revenue/export_data")?>" export="<?php echo 'arevenue_' . $gameCode . '_' . $timing . '_' . $value['CalculateValue'] ?>"><?php echo number_format($value['AccountTotalAllGrade']) ?></a></td>
                                <td class="text-right"><?php echo number_format($value['RevenueTotalAllGrade']) ?> đ</td>
                                <td class="text-right"><?php echo number_format($value['ARPPU']) ?> đ</td>
                                <td class="text-right"><a class="export" href="javascript:;" data-description="Danh sách tài khoản chi trả lần đầu <?php echo $time . " " .$displayDate ?>" data-url="<?php echo base_url("/index.php/Revenue/export_data")?>" export="<?php echo 'afirstpay_' . $gameCode . '_' . $timing . '_' . $value['CalculateValue'] ?>"><?php echo number_format($value['TotalAccountFirstCharge']) ?></a></td>
                                <td class="text-right"><?php echo number_format($value['TotalRevenueFirstCharge']) ?> đ</td>
                            </tr>
                            <?php
                            $totalRevenue += $value['RevenueTotalAllGrade'];
                            $totalAccountFirstCharge += $value['TotalAccountFirstCharge'];
                            $totalRevenueFristCharge += $value['TotalRevenueFirstCharge'];
                        }
                    endforeach; ?>
                    </tbody>

                    <tfoot>
                    <tr style="background-color: lightgrey">
                        <th class="text-center">Tổng</th>
                        <th></th>
                        <th class="text-right"><?php echo number_format($totalRevenue); ?></th>
                        <th></th>
                        <th class="text-right"><?php echo number_format($totalAccountFirstCharge); ?></th>
                        <th class="text-right"><?php echo number_format($totalRevenueFristCharge); ?></th>
                    </tr>
                    </tfoot>
                </table>

            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>