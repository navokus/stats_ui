<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 26/04/2016
 * Time: 11:26
 */
?>

<form name="form" action="" method="post">
    <div class="box-footer text-black" style="display: block;">
        <input type="hidden" name="default_game" value="<?php echo $this->session->userdata('current_game') ?>">
        <div class="col-md-2">
            <select class="form-control" name="options">
                <option value="4" <?php echo((4 == $body['options']) ? 'selected' : '') ?> >Chọn ngày</option>
                <option value="5" <?php echo((5 == $body['options']) ? 'selected' : '') ?> >Chọn tuần</option>
                <option value="6" <?php echo((6 == $body['options']) ? 'selected' : '') ?> >Chọn tháng</option>
            </select>
        </div>
        <!-- /.col -->

        <div class="option_time option_day hide">
            <div class="col-md-4">
                <div class="input-group">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <input value="<?php echo $body['day']['default_single_date'];?>"  id="datesinglepicker" name="datesinglepicker" class="glyphicon glyphicon-calendar fa fa-calendar" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;"/>
                </div>
            </div>
        </div>



        <div class="option_time option_disable hide">
            <div class="col-md-4">
                <input autocomplete="off" class="form-control" type="text" disabled>
            </div>
        </div>

        <div class="option_time option_week hide">
            <div class="col-md-2">
                <select class="form-control" id="wpw2" name="week[2]">
                    <?php
                    foreach ($body['optionsWeek'] as $key => $value) {

                        if ($body['week']['1'] == $key) {
                            $selected = ' selected ';
                        } else {
                            $selected = '';
                        }

                        echo "<option value='". $key ."' ". $selected .">". $value ."</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="option_time option_month ">
            <div class="col-md-2">
                <select class="form-control" id="mpm2" name="month[2]">
                    <?php
                    foreach ($body['optionsMonth'] as $key => $value) {

                        if ($body['month'][1] == $key) {
                            $selected = ' selected ';
                        } else {
                            $selected = '';
                        }
                        echo "<option value='". $key ."' ". $selected .">". $value ."</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="btn-group">
                <button type="submit" name="submit_type" value="day" class="btn btn-danger">Day</button>
                <button type="submit" name="submit_type" value="session" class="btn btn-danger">Session</button>
            </div>
        </div>

    </div>
</form>

