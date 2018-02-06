<div class="box box-solid bg-green-gradient">
    <div class="box-header">
        <h3 class="box-title">CHUYỂN ĐỔI GAME KHÁC</h3>
    </div>
    <!-- /.box-header -->
    <form name="form" action="<?php echo site_url('PayTransfer/') ?>" method="post">
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
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-exchange"></i> Theo dõi chi trả game khác</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body text-center">
                <?php
                $rptTypeTotalAccount = "totalAccount";
                $rptTypeStopPayingAccount = "stoppaying";
                $rptTypeNewPay = "retentionnewpaying";
                $rptTypePrePay = "retentionreturnpaying";
                $rptTypeReturnPay = "retentionrepay";
                $rptTypePayingOtherProductAccount = "overlappaying";
                $header = array_keys($transferPaying);
//                $arrHeader = array();
                if ($header) {
                    ?>

                    <table class="table table-bordered table-bordered-gray no-margin table-striped nowrap "
                           id="table-transfer">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 150px; vertical-align: middle"
                                rowspan="2"> <?php echo $time; ?> / User <br>   (First Pay / Return pay)
                            </th>
                            <?php foreach ($header as $value) : ?>

                                <th class="text-center" colspan="4"><?php  echo $value ?></th>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <?php foreach ($header as $value) : ?>
                                <th class="text-center">Total Acc</th>
                                <th class="text-center">StopPaying Acc</th>
                                <th class="text-center">Paying Acc</th>
                                <th class="text-center">PayingOtherProduc Acc</th>
                            <?php endforeach; ?>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($transferPaying as $key => $value) : ?>
                            <?php
                            $displayDate = "";
                            $date =$key;
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
                            ?>
                            <tr>
                                <td class="text-center"><b><?php echo $key; ?></b></td>
                                <?php foreach ($header as $v) : ?>
                                    <td class="text-right">
                                        <?php if ($value[$v]) echo number_format($value[$v][0]); ?>
                                    </td>
                                    <td class="text-right">
                                        <?php if ($value[$v]){
                                            echo '<a href="#"  class="export"
                                            data-description="Danh sách tài khoản ngưng chi trả game '.$gameCode.' - '.$time. ' '. $displayDate.'"
                                            data-url="'.base_url("/index.php/PayTransfer/export_data").'"
                                            export="'. $rptTypeStopPayingAccount.'_'. $gameCode.'_'. $timing.'_'. $v.'_'. $key.'" > '.number_format($value[$v][1]) . '</a>';
                                        }
                                        ?></td>
                                    <td class="text-right">
                                        <?php
                                        $htmlValue1 = $htmlValue2 = "";
                                        if ($value[$v]) {
                                            $htmlValue1 =  '<a href="#"  class="export"
                                            data-description="Danh sách tài khoản ngưng chi trả game '.$gameCode.' - '.$time. ' '. $displayDate.'"
                                            data-url="'.base_url("/index.php/PayTransfer/export_data").'"
                                            export="'. $rptTypePrePay.'_'. $gameCode.'_'. $timing.'_'. $v.'_'. $key.'" > '.number_format($value[$v][2]) . '</a>';
                                        }
                                        ?>
                                        <?php
                                        if(empty($value[$v][4]) === FALSE && $value[$v][4]) {
                                            $htmlValue2 = "<br>".'<a href="#" class="export"  data-url="'.base_url("/index.php/PayTransfer/export_data")
                                                .'"  data-description="Danh sách tài khoản chi trả mới - '.$time.' '.$displayDate
                                                .'"  export="'. $rptTypeNewPay .'_'.$gameCode.'_'. $timing.'_'. $v.'_'. $key.'" > '
                                                ."<span class='text-green'>" . number_format($value[$v]['4']) ."</span></a>/"
                                                .'<a href="#" class="export" data-url="'.base_url("/index.php/PayTransfer/export_data").'"  data-description="Danh sách tài khoản chi trả cũ - '.$time.' '.$displayDate
                                                .'"  export="'. $rptTypePrePay .'_'.$gameCode.'_'. $timing.'_'. $v.'_'. $key.'">'."<span class='text-red'>"
                                                . number_format($value[$v]['6']) . "</span></a>";
                                        }
                                        if($htmlValue2 != ""){
                                            $htmlValue1 = number_format($value[$v][2]) ;
                                        }
                                        echo $htmlValue1;
                                        echo $htmlValue2;
                                        ?>
                                    </td>
                                    <td class="text-right">
                                        <?php if ($value[$v]){
                                            echo '<a href="#" class="export"
                                             '.' data-description="Danh sách tài khoản chi trả game '.$gameCode.' và các game khác - '.$time. ' '. $displayDate.'"
                                            data-url="'.base_url("/index.php/PayTransfer/export_data").'" '.'export="'
                                                . $rptTypePayingOtherProductAccount.'_'.$gameCode.'_'. $timing.'_'. $v.'_'.$key.'">'.number_format($value[$v][3]) .'</a>';
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
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-exchange"></i> Chi tiết chi trả game khác</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body text-center">
                <?php
                $overlapPaying = "overlappaying";
                $detailTypeRetentionOverlap = "retentionpaying";
                $detailTypeStopOverlap = "stoppaying";


                $header = array_keys($transferPayingDetail);
                if ($header) {
                    ?>
                    <table class="table table-bordered table-bordered-gray no-margin table-striped nowrap "
                           id="table-transfer-detail">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 120px; vertical-align: middle" rowspan="2"> Game
                                / <?php echo $time; ?></th>
                            <?php foreach ($header as $value) : ?>
                                <th class="text-center" colspan="6"><?php echo $value ?></th>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <?php foreach ($header as $value) : ?>
                                <th class="text-center">Paying Acc Total</th>
                                <th class="text-center">Revenue Total</th>
                                <th class="text-center">Both Product Paying Acc</th>
                                <th class="text-center">Both Product Paying Revenue</th>
                                <th class="text-center">Only Paying Other Product Acc</th>
                                <th class="text-center">Only Paying Other Product Revenue</th>
                            <?php endforeach; ?>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($gameTransferPayingDetail as $v) : ?>

                            <?php

                            $check = '';
                            $tmp = '<tr><td class="text-center"><b>' . strtoupper($v) . '</b></td>';

                            foreach ($transferPayingDetail as $key => $value) :
                                switch($timing){
                                    case "daily":
                                        $displayDate = date("d/m/Y", strtotime($key));
                                        break;
                                    case "weekly":
                                        $partWeek = explode("-",$key);
                                        $displayDate = "";
                                        if(count($partWeek) == 2){
                                            $displayDate = $partWeek[1]. " năm ".$partWeek[0];
                                        }
                                        break;
                                    case "monthly":
                                        $partMonth = explode("-",$key);
                                        $displayDate = "";
                                        if(count($partMonth) == 2){
                                            $displayDate = $partWeek[1]. " năm ".$partWeek[0];
                                        }
                                        break;
                                    default:
                                        break;
                                }
                                $OverLapAccTotal = $PayingAccTotal = $PayingAccRevenue = $StopPayingAccTotal = $StopPayingAccRevenue = "";

                                if ($value[$v][0]) $OverLapAccTotal = number_format($value[$v][0]);
                                if ($value[$v][1]) $PayingAccTotal = number_format($value[$v][1]);
                                if ($value[$v][2]) $PayingAccRevenue = number_format($value[$v][2]);
                                if ($value[$v][3]) $StopPayingAccTotal = number_format($value[$v][3]);
                                if ($value[$v][4]) $StopPayingAccRevenue = number_format($value[$v][4]);

                                if ($OverLapAccTotal || $PayingAccTotal || $PayingAccRevenue || $StopPayingAccTotal || $StopPayingAccRevenue) {
                                    $check = 1;
                                }

                                $tmp .= '
	                      			<td class="text-right"><a href="javascript:;" class="export"
	                      			                        export="'.$overlapPaying . "_"  . $gameCode  . "_" . $v  . "_" . $timing  . "_" . $key . "_".'"
	                      			                        data-url="'.base_url("/index.php/PayTransfer/export_data_detail").'"
	                      			                        data-description="Danh sách tài khoản  chi trả cho game '.strtoupper($v).' - ' .$time .' '. $displayDate.'" >' . $OverLapAccTotal . '</a> </td>
	                      			<td class="text-right">' . number_format($value[$v][2] + $value[$v][4]) . '</td>
			                      	<td class="text-right"><a href="javascript:;" class="export"
	                      			                        export="'.$overlapPaying ."_" . $gameCode  . "_" . $v  . "_" . $timing  . "_" . $key .  "_" . $detailTypeRetentionOverlap. '"
	                      			                        data-url="'.base_url("/index.php/PayTransfer/export_data_detail").'"
	                      			                        data-description="Danh sách tài khoản có chi trả cho game '.strtoupper($v).' - ' .$time .' '. $displayDate.'" >' . $PayingAccTotal . '</td>
			                      	<td class="text-right">' . $PayingAccRevenue . '</td>
			                      	<td class="text-right"><a href="javascript:;" class="export"
	                      			                        export="'.$overlapPaying .  "_" . $gameCode  . "_" . $v  . "_" . $timing  . "_" . $key ."_" . $detailTypeStopOverlap.'"
	                      			                        data-url="'.base_url("/index.php/PayTransfer/export_data_detail").'"
	                      			                        data-description="Danh sách tài khoản ngưng chi trả cho game '.strtoupper($v).' - ' .$time .' '. $displayDate.'" >' . $StopPayingAccTotal . '</td>
			                      	<td class="text-right">' . $StopPayingAccRevenue . '</td>
	                      			';
                                ?>
                                <?php
                            endforeach;
                            if ($check == 1) echo $tmp . '</tr>';
                            ?>

                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    echo '<div class="text-center">Không có dữ liệu.</div>';
                }
                ?>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>