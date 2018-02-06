<div class="box box-solid bg-green-gradient">
    <div class="box-header">
        <h3 class="box-title">SO SÁNH GAME KHÁC</h3>
    </div>
    <!-- /.box-header -->
    <form name="form" action="<?php echo site_url('CompareGame/') ?>" method="post">
        <div class="box-footer text-black" style="display: block;">
            <input type="hidden" name="gameCode" value="<?php echo $this->session->userdata('default_game') ?>">

            <div class="col-md-3">
                <select class="form-control" name="options">
                    <option value="4" <?php echo((4 == $post['options']) ? 'selected' : '') ?> >Chọn Ngày</option>
<!--                    <option value="5" --><?php //echo((5 == $post['options']) ? 'selected' : '') ?><!-- >Chọn Tuần</option>-->
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
                <li class="pull-left header"><i class="fa fa-random"></i> So sánh từng game</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="compare_oneGame">

                    <div class="col-md-12 page-header">
                        <form name="form" action="#" method="post">
                            <div class="pull-right ">
                                <select class="form-control compare-game" name="gameCodeCompare">
                                    <?php foreach ($aGames as $value) : ?>
                                        <?php
                                        if ($value['GameCode'] != $this->session->userdata('default_game')) { ?>
                                            <option
                                                value="<?php echo $value['GameCode'] ?>"><?php echo $value['GameName'] ?></option>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- /.col -->
                        </form>
                    </div>

                    <div id="rs_compapre_onegame"></div>
                </div>


            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                <li class="pull-left header"><i class="fa fa-random"></i> So sánh nhiều game</li>
            </ul>
            <div class="tab-content">

                <div class="tab-pane active" id="compare_allGame">

                    <?php

                    if ($compareAllGame[$this->session->userdata('default_game')]['CalculateValue']) {
                        $header = array_keys($compareAllGame);
                        ?>
                        <table class="table table-bordered table-bordered-gray no-margin table-striped nowrap compare-game"
                               id="table-compare-all-game">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 170px;">
                                    Tháng <?php echo $compareAllGame[$this->session->userdata('default_game')]['CalculateValue']; ?>
                                </th>
                                <th class="text-center">
                                    <?php echo strtoupper($this->session->userdata('default_game')) ?>
                                </th>
                                <?php foreach ($header as $value) :
                                    if ($this->session->userdata('default_game') != $value) {
                                        ?>
                                        <th class="text-center"><?php echo strtoupper($value); ?></th>
                                        <?php
                                    }
                                    ?>
                                <?php endforeach; ?>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            $i = 0;
                            foreach ($compareAllGame as $gameCode => $v) :

                                // total user
                                if ($i == 0) {
                                    $row_user = '<tr><th class="text-center">Tổng User</th>';
                                    $row_user .= '<th class="text-right">' . number_format($compareAllGame[$this->session->userdata('default_game')]['TotalUser']) . '</th>';
                                }

                                // user paying
                                if ($i == 0) {
                                    $row_user_pay = '<tr><th class="text-center">User chi trả</th>';
                                    $row_user_pay .= '<th class="text-right">' . number_format($compareAllGame[$this->session->userdata('default_game')]['TotalUserPaying']) . '</th>';
                                }

                                // Doanh thu
                                if ($i == 0) {
                                    $row_revenue = '<tr><th class="text-center">Doanh thu</th>';
                                    $row_revenue .= '<th class="text-right">' . number_format($compareAllGame[$this->session->userdata('default_game')]['TotalRevenue']) . '</th>';
                                }

                                // total playing time
                                if ($i == 0) {
                                    $row_playtime = '<tr><th class="text-center">Thời gian chơi</th>';
                                    $row_playtime .= '<th class="text-right">' . number_format($compareAllGame[$this->session->userdata('default_game')]['TotalPlaytime']) . '</th>';
                                }

                                // avg playing time
                                if ($i == 0) {
                                    $row_avPlaytime = '<tr><th class="text-center">TB Thời gian chơi</th>';
                                    $row_avPlaytime .= '<th class="text-right">' . round($compareAllGame[$this->session->userdata('default_game')]['TotalPlaytime'] / $compareAllGame[$this->session->userdata('default_game')]['TotalUser']/ 2) . '</th>';
                                }

                                if ($this->session->userdata('default_game') != $gameCode) {

                                    $row_user .= '
							      			<td class="text-right">' . number_format($compareAllGame[$gameCode]['TotalUser']) . '</td>
						      			';

                                    $row_user_pay .= '
							      			<td class="text-right">' . number_format($compareAllGame[$gameCode]['TotalUserPaying']) . '</td>
						      			';

                                    $row_revenue .= '
							      			<td class="text-right">' . number_format($compareAllGame[$gameCode]['TotalRevenue']) . '</td>
						      			';

                                    $row_playtime .= '
							      			<td class="text-right">' . number_format($compareAllGame[$gameCode]['TotalPlaytime']) . '</td>
						      			';

                                    $row_avPlaytime .= '
							      			<td class="text-right">' . round($compareAllGame[$gameCode]['TotalPlaytime'] / $compareAllGame[$gameCode]['TotalUser'], 2) . '</td>
						      			';

                                    if ($i == count($compareAllGame) - 1) {
                                        $row_user .= '</tr>';
                                        $row_user_pay .= '</tr>';
                                        $row_revenue .= '</tr>';
                                        $row_playtime .= '</tr>';
                                        $row_avPlaytime .= '</tr>';
                                    }
                                }
                                $i++;
                            endforeach;

                            echo $row_user;
                            echo $row_user_pay;
                            echo $row_revenue;
                            echo $row_playtime;
                            echo $row_avPlaytime;

                            ?>

                            </tbody>
                        </table>
                    <?php } else {
                        echo '<div class="text-center">Không có dữ liệu.</div>';
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>