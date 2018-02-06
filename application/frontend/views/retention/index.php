
<?php
//var_dump("12132",$retentionPaying);
//exit();
?>
<div class="box box-solid bg-green-gradient">
    <div class="box-header">
        <h3 class="box-title">RETENTION</h3>
    </div>
    <!-- /.box-header -->
    <form name="form" action="<?php echo site_url('Retention/') ?>" method="post">
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
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">

                <li class="pull-left header"><i class="fa fa-th"></i> User đăng nhập</li>

            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1-1">
                    <?php
                    $retentionReturnLogin = 'retentionreturnlogin';
                    $retentionNewLogin = 'retentionnewlogin';
                    $retentionPayingKey = 'retentionreturnpaying';
                    $retentionNewPaying = 'retentionnewpaying';
                    $retentionRePaying = 'retentionrepay';
                    $header = array_keys($retentionLogin);

                    if ($header) {
                        ?>
                        <table class="table table-bordered table-bordered-gray no-margin table-striped">
                            <tbody>
                            <tr>
                                <th class="text-center" style="width:150px"> <?php echo $time; ?> / Tổng User<br>(New/Return User)</th>
                                <?php foreach ($header as $value) : ?>
                                    <th class="text-center"><?php echo $value ?></th>
                                <?php endforeach; ?>
                            </tr>

                            <?php foreach ($retentionLogin as $key => $value) : ?>
                                <tr>
                                    <th class="text-center"><?php echo $key; ?></th>
                                    <?php foreach ($header as $v) : ?>
                                        <td class="text-right">
                                            <?php
                                                $displayDate = "";
                                                $date =$v;
                                                switch($timing) {
                                                    case "daily":
                                                        $displayDate = date("d/m/Y", strtotime($date));
                                                        break;
                                                    case "weekly":
                                                        $partWeek = explode("-", $date);
                                                        $displayDate = "";
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
                                                $htmlValue1 = $htmlValue2 = "";
                                                if ($value[$v]) {
                                                  $htmlValue1 = '<a  class="export" data-url="'
                                                      .base_url("/index.php/Retention/export_data")
                                                      .'" data-description="Danh sách tài khoản đăng nhập - '.$time. ' '. $displayDate.'" href="javascript:;" export="'.$retentionReturnLogin."_".$gameCode."_".$timing."_".$v."_".$key.'">'.number_format($value[$v][0]) . '</a>';
                                                }
                                                if ($value[$v][1]) {
                                                    $htmlValue2  = "<br>".'<a  class="export" data-url="'.base_url("/index.php/Retention/export_data").'" data-description="Danh sách tài khoản đăng nhập mới - '.$time. ' '. $displayDate.'"
                                                    href="javascript:;" export="'.$retentionNewLogin."_".$gameCode."_".$timing."_".$v."_".$key.'">'
                                                        ."<span class='text-green'>" .  number_format($value[$v]['1']) ."</span></a>/<a  href='javascript:;' data-description='Danh sách tài khoản đăng nhập cũ - ".$time. ' '. $displayDate."' export='".$retentionReturnLogin."_".$gameCode."_".$timing."_".$v."_".$key."' class='export'  data-url='".base_url("/index.php/Retention/export_data")."' ><span class='text-red'>" . number_format($value[$v]['2']) . "</span></a>";
                                                }
                                                if($htmlValue2 != "") {
                                                    $htmlValue1 = number_format($value[$v][0]);
                                                }
                                                echo $htmlValue1;
                                                echo $htmlValue2;

                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php
                    } else {
                        echo '<div class="text-center">Không có dữ liệu.</div>';
                    }
                    ?>
                </div>
                <!-- /.tab-pane -->

            </div>
            <!-- /.tab-content -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                <li class="pull-left header"><i class="fa fa-th"></i> User chi trả</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <?php
                    $header = array_keys($retentionPaying);

                    if ($header) {
                        ?>

                        <table class="table table-bordered table-bordered-gray no-margin table-striped" id="table-transfer-detail">

                            <thead>
                            <tr>
                                <th class="text-center" style="width:150px; vertical-align: middle"
                                    rowspan="2"> <?php echo $time; ?> / Tổng User<br>(New Pay/Return Pay)
                                </th>
                                <?php foreach ($header as $value) : ?>
                                    <th class="text-center" colspan="2"><?php echo $value ?></th>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <?php foreach ($header as $value) : ?>
                                    <th class="text-center">Pay</th>
                                    <th class="text-center">Return pay</th>
                                <?php endforeach; ?>
                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($retentionPaying as $key => $value) : ?>
                                <tr>
                                    <th class="text-center"><?php echo $key; ?></th>
                                    <?php foreach ($header as $v) : ?>
                                        <td class="text-right">
                                            <?php
                                                $displayDate = "";
                                                $date =$v;
                                                switch($timing) {
                                                    case "daily":
                                                        $displayDate = date("d/m/Y", strtotime($date));
                                                        break;
                                                    case "weekly":
                                                        $partWeek = explode("-", $date);
                                                        $displayDate = "";
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
                                                $htmlValue1 = $htmlValue2  = "";
                                                if ($value[$v]) {
                                                    $htmlValue1 = '<a  class="export" data-url="'
                                                        .base_url("/index.php/Retention/export_data")
                                                        .'" data-description="Danh sách tài khoản chi trả - '.$time. ' '. $displayDate.'" href="javascript:;" export="'.$retentionPayingKey."_".$gameCode."_".$timing."_".$v."_".$key.'">'.number_format($value[$v][0]) . '</a>';
                                                }
                                                if ($value[$v][4]) {
                                                    $htmlValue2 =  "<br>"
                                                        .'<a  class="export" data-url="'
                                                        .base_url("/index.php/Retention/export_data")
                                                        .'" data-description="Danh sách tài khoản chi trả mới - '.$time. ' '. $displayDate
                                                        .'" href="javascript:;" export="'.$retentionNewPaying."_".$gameCode."_".$timing."_".$v."_".$key.'">'
                                                        ."<span class='text-green'>" . number_format($value[$v]['4'])
                                                        ."</span>/<a href='javascript:;' data-description='Danh sách tài khoản chi trả cũ - ".$time. ' '. $displayDate."'
                                                        export='".$retentionPayingKey."_".$gameCode."_".$timing."_".$v."_".$key."' class='export' ><span class='text-red'>" . number_format($value[$v]['6']) . "</span></a>";
                                                }
                                                if($htmlValue2 != ""){
                                                    $htmlValue1 = number_format($value[$v][0]);
                                                }
                                                echo $htmlValue1;
                                                echo $htmlValue2;
                                            ?>
                                        </td>
                                        <td class="text-right"><?php if ($value[$v]) {
                                                echo '<a  class="export" data-url="'
                                                .base_url("/index.php/Retention/export_data")
                                                .'" data-description="Danh sách tài khoản quay lại chi trả - '.$time. ' '. $displayDate
                                                .'" href="javascript:;" export="'.$retentionRePaying."_".$gameCode."_".$timing."_".$v."_".$key.'">'
                                                .number_format($value[$v][1]) . '</a>';
                                            } ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php
                    } else {
                        echo '<div class="text-center">Không có dữ liệu.</div>';
                    }
                    ?>

                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                <li class="pull-left header"><i class="fa fa-th"></i> Doanh thu</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <?php
                    $header = array_keys($retentionPaying);

                    if ($header) {
                        ?>

                        <table class="table table-bordered table-bordered-gray no-margin table-striped page-retention"
                               id="table-revenue2">
                            <thead>
                            <tr>
                                <th class="text-center" style="width:150px; vertical-align: middle"
                                    rowspan="2"> <?php echo $time; ?> / Doanh Thu <br>(New Pay/Return Pay)
                                </th>
                                <?php foreach ($header as $value) : ?>
                                    <th class="text-center" colspan="2"><?php echo $value ?></th>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <?php foreach ($header as $value) : ?>
                                    <th class="text-center">Pay</th>
                                    <th class="text-center">Return pay</th>
                                <?php endforeach; ?>
                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($retentionPaying as $key => $value) : ?>
                                <tr>
                                    <th class="text-center"><?php echo $key; ?></th>
                                    <?php foreach ($header as $v) : ?>
                                        <td class="text-right">
                                            <?php
                                                if ($value[$v]) echo number_format($value[$v][2]);
                                                if ($value[$v][5]) {
                                                    echo "<br><span class='text-green'>" . number_format($value[$v]['5']) ."</span>/<span class='text-red'>" . number_format($value[$v]['7']) . "</span>";
                                                }
                                            ?>
                                        </td>
                                        <td class="text-right"><?php if ($value[$v]) echo number_format($value[$v][3]); ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php
                    } else {
                        echo '<div class="text-center">Không có dữ liệu.</div>';
                    }
                    ?>

                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
    </div>
</div>