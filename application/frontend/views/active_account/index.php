<div class="box box-solid bg-green-gradient">
    <div class="box-header">
        <h3 class="box-title">ACTIVE ACCOUNT</h3>
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
</div>

<script type="text/javascript">
    $(function () {
        $('#container').highcharts({
            colors: ['#7CB5EC', '#00a65a', '#f56954'],
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
                        text: 'Số lượng',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    labels: {
                        // format: '{value}',
                        formatter: function () {
                            return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        },
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                },
            ],
            tooltip: {
                shared: true
            },
            series: [
                {
                    name: 'Active Role',
                    type: 'spline',
                    data: [<?php echo $dataRoleActive; ?>],
                    tooltip: {
                        valueSuffix: ''
                    },
                    lineWidth: 3,

                },
                {
                    name: 'Active Account',
                    type: 'spline',
                    data: [<?php echo $dataUserActive; ?>],
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

                <table class="table table-bordered table-bordered-gray table-striped" id="active-account">
                    <thead>
                    <tr>
                        <th class="text-center" style="border-right: 1px solid gray;"><?php echo $time ?></th>
                        <th class="text-center">Active Account</th>
                        <th class="text-center">Active Role</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    foreach ($data as$value) :
                        if ($value) {
                            ?>
                            <tr>
                                <td class="text-center" style="border-right: 1px solid gray;"><?php echo $value['CalculateValue'] ?></td>
                                <td class="text-center" ><a class="export" href="javascript:;" export="<?php echo 'ActiveAccount_' . $gameCode . '_' . $time . '_' . $value['CalculateValue'] ?>"><?php echo number_format($value['ActiveAccountTotal']) ?></a></td>
                                <td class="text-center" ><a class="export" href="javascript:;" export="<?php echo 'ActiveRole_' . $gameCode . '_' . $time . '_' . $value['CalculateValue'] ?>"><?php echo number_format($value['ActiveRoleTotal']) ?></a></td>
                            </tr>
                            <?php
                        }
                    endforeach; ?>
                    </tbody>
                </table>

            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>