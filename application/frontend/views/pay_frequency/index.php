<div class="box box-solid bg-green-gradient">
    <div class="box-header">
        <h3 class="box-title"> TẦN XUẤT - PHƯƠNG PHÁP CHI TRẢ</h3>
    </div>
    <!-- /.box-header -->
    <form name="form" action="<?php echo site_url('PayFrequency/') ?>" method="post">
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
                <h3 class="box-title"><i class="fa fa-money"></i> Tần xuất chi trả
                </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body text-center">
                <?php
                if($payFrequency) {

                    ?>
                    <table class="table table-bordered table-bordered-gray table-striped" id="table-transfer">
                        <thead>
                        <tr>
                            <th class="text-center" style="border-right: 1px solid gray; width: 100px"><?php echo $time ?> / User chi
                                trả
                            </th>
                            <th class="text-center">Tổng User chi trả </th>
                            <th class="text-center">1 lần</th>
                            <th class="text-center">2 lần</th>
                            <th class="text-center">3 lần</th>
                            <th class="text-center">4 lần</th>
                            <th class="text-center">5 lần</th>
                            <th class="text-center">6 lần</th>
                            <th class="text-center">7 lần</th>
                            <th class="text-center">8 lần</th>
                            <th class="text-center">9 lần</th>
                            <th class="text-center">10-14 lần</th>
                            <th class="text-center">15-19 lần</th>
                            <th class="text-center">20-29 lần</th>
                            <th class="text-center">>=30 lần</th>
                            <th class="text-center">Tổng lần</th>
                            <th class="text-center">Tần xuất / User</th>
                            <th class="text-center">Doanh thu</th>
                            <th class="text-center">ARPPU</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $rptType = "transactionfrequency";

                        foreach ($payFrequency as $key => $value) :
                            if ($value) {
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

                                $total_user_pay = $total_time_pay = $total_revenue = 0;
                                foreach ($value as $k => $v) {
                                    $total_user_pay += $v['AccountTotal'];
                                    $total_time_pay += $v['TotalTimes'];
                                    $total_revenue += $v['RevenueTotal'];
                                }

                                ?>
                                <tr>
                                    <td class="text-center"
                                        style="border-right: 1px solid gray; width: 100px"><?php echo $key ?></td>
                                    <td class="text-center"><?php echo number_format($total_user_pay) ?></td>
                                    <td class="text-center"><a  class="export" href="javascript:;" data-description="<?php echo "Danh sách tài khoản chi trả 1 lần - ".$time . ' '.$displayDate; ?>" data-url="<?php echo base_url("/index.php/PayFrequency/export_data");?>" export="<?php echo $rptType.'1_' . $gameCode . '_'.$timing. '_' . $key ?>"><?php echo number_format($value[1]['AccountTotal']) ?></a></td>
                                    <td class="text-center"><a  class="export" href="javascript:;" data-description="<?php echo "Danh sách tài khoản chi trả 2 lần - ".$time . ' '.$displayDate; ?>"  data-url="<?php echo base_url("/index.php/PayFrequency/export_data");?>"  export="<?php echo $rptType.'2_' . $gameCode . '_'.$timing. '_' . $key ?>"><?php echo number_format($value[2]['AccountTotal']) ?></a></td>
                                    <td class="text-center"><a  class="export" href="javascript:;" data-description="<?php echo "Danh sách tài khoản chi trả 3 lần - ".$time . ' '.$displayDate; ?>" data-url="<?php echo base_url("/index.php/PayFrequency/export_data");?>"  export="<?php echo $rptType.'3_' . $gameCode . '_'.$timing. '_' . $key ?>"><?php echo number_format($value[3]['AccountTotal']) ?></a></td>
                                    <td class="text-center"><a  class="export" href="javascript:;" data-description="<?php echo "Danh sách tài khoản chi trả 4 lần - ".$time . ' '.$displayDate; ?>" data-url="<?php echo base_url("/index.php/PayFrequency/export_data");?>"  export="<?php echo $rptType.'4_' . $gameCode . '_'.$timing. '_' . $key ?>"><?php echo number_format($value[4]['AccountTotal']) ?></a></td>
                                    <td class="text-center"><a  class="export" href="javascript:;" data-description="<?php echo "Danh sách tài khoản chi trả 5 lần - ".$time . ' '.$displayDate; ?>" data-url="<?php echo base_url("/index.php/PayFrequency/export_data");?>"  export="<?php echo $rptType.'5_' . $gameCode . '_'.$timing. '_' . $key ?>"><?php echo number_format($value[5]['AccountTotal']) ?></a></td>
                                    <td class="text-center"><a  class="export" href="javascript:;" data-description="<?php echo "Danh sách tài khoản chi trả 6 lần - ".$time . ' '.$displayDate; ?>" data-url="<?php echo base_url("/index.php/PayFrequency/export_data");?>"  export="<?php echo $rptType.'6_' . $gameCode . '_'.$timing. '_' . $key ?>"><?php echo number_format($value[6]['AccountTotal']) ?></a></td>
                                    <td class="text-center"><a  class="export" href="javascript:;" data-description="<?php echo "Danh sách tài khoản chi trả 7 lần - ".$time . ' '.$displayDate; ?>" data-url="<?php echo base_url("/index.php/PayFrequency/export_data");?>"  export="<?php echo $rptType.'7_' . $gameCode . '_'.$timing. '_' . $key ?>"><?php echo number_format($value[7]['AccountTotal']) ?></a></td>
                                    <td class="text-center"><a  class="export" href="javascript:;" data-description="<?php echo "Danh sách tài khoản chi trả 8 lần - ".$time . ' '.$displayDate; ?>" data-url="<?php echo base_url("/index.php/PayFrequency/export_data");?>"  export="<?php echo $rptType.'8_' . $gameCode . '_'.$timing. '_' . $key ?>"><?php echo number_format($value[8]['AccountTotal']) ?></a></td>
                                    <td class="text-center"><a  class="export" href="javascript:;" data-description="<?php echo "Danh sách tài khoản chi trả 9 lần - ".$time . ' '.$displayDate; ?>" data-url="<?php echo base_url("/index.php/PayFrequency/export_data");?>"  export="<?php echo $rptType.'9_' . $gameCode . '_'.$timing. '_' . $key ?>"><?php echo number_format($value[9]['AccountTotal']) ?></a></td>
                                    <td class="text-center"><a  class="export" href="javascript:;" data-description="<?php echo "Danh sách tài khoản chi trả 10-14 lần - ".$time . ' '.$displayDate; ?>" data-url="<?php echo base_url("/index.php/PayFrequency/export_data");?>"  export="<?php echo $rptType.'10_' . $gameCode . '_'.$timing. '_' . $key ?>"><?php echo number_format($value[10]['AccountTotal']) ?></a></td>
                                    <td class="text-center"><a  class="export" href="javascript:;" data-description="<?php echo "Danh sách tài khoản chi trả 15-19 lần - ".$time . ' '.$displayDate; ?>" data-url="<?php echo base_url("/index.php/PayFrequency/export_data");?>"  export="<?php echo $rptType.'15_' . $gameCode . '_'.$timing. '_' . $key ?>"><?php echo number_format($value[15]['AccountTotal']) ?></a></td>
                                    <td class="text-center"><a  class="export" href="javascript:;" data-description="<?php echo "Danh sách tài khoản chi trả 20-29 lần - ".$time . ' '.$displayDate; ?>" data-url="<?php echo base_url("/index.php/PayFrequency/export_data");?>"  export="<?php echo $rptType.'20_' . $gameCode . '_'.$timing. '_' . $key ?>"><?php echo number_format($value[20]['AccountTotal']) ?></a></td>
                                    <td class="text-center"><a  class="export" href="javascript:;" data-description="<?php echo "Danh sách tài khoản chi trả >=30 lần - ".$time . ' '.$displayDate; ?>"data-url="<?php echo base_url("/index.php/PayFrequency/export_data");?>"  export="<?php echo $rptType.'30_' . $gameCode . '_'.$timing. '_' . $key ?>"><?php echo number_format($value[30]['AccountTotal']) ?></a></td>
                                    <td class="text-center"><?php echo number_format($total_time_pay) ?></td>
                                    <td class="text-center"><?php echo number_format($total_time_pay / $total_user_pay) ?></td>
                                    <td class="text-center"><?php echo number_format($total_revenue) ?></td>
                                    <td class="text-center"><?php echo number_format($total_revenue / $total_user_pay) ?></td>
                                </tr>
                                <?php

                            }
                        endforeach; ?>
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

<?php
/*

<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-money"></i> Kênh chi trả
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
                            <th class="text-center" style="border-right: 1px solid gray; width: 100px"
                                rowspan="2"><?php echo $time ?> / Kênh
                            </th>
                            <?php
                            foreach ($payMethod['channel'] as $value) :
                                ?>
                                <th class="text-center" colspan="2"><?php echo $value ?></th>
                                <?php
                            endforeach;
                            ?>
                        </tr>
                        <tr>
                            <?php
                            foreach ($payMethod['channel'] as $value) :
                                ?>
                                <th class="text-center">Lần nạp</th>
                                <th class="text-center">Doanh thu</th>
                                <?php
                            endforeach;
                            ?>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        foreach ($payMethod['data'] as $time => $value) :
                            ?>
                            <tr>
                                <th class="text-center"><?php echo $time; ?></th>
                                <?php
                                foreach ($payMethod['channel'] as $channel) :
                                    ?>
                                    <td class="text-right"><?php echo number_format($value[$channel]['CashTime']) ?></td>
                                    <td class="text-right"><?php echo number_format($value[$channel]['CashTimeRevenue']) ?></td>
                                    <?php
                                endforeach;
                                ?>
                            </tr>
                            <?php
                        endforeach;
                        ?>
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
 */
?>