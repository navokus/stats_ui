<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 08/06/2016
 * Time: 10:35
 */
?>
<section>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-money" name="revenue"></i> Revenue</h3>
                    <a href="#revenue"class="pull-right" hidden="true" data-toggle="collapse" data-target="#dashboard-trend-chart-1"><span class="hidden-xs">Settings </span><i class="fa fa-gears"></i></a>
                </div>
                <div class="col-md-12">
                    <form name="form"  action="<?php echo site_url('Dashboard/userconfig?type=trend-chart-1'); ?>" method="post">
                        <div class="collapse" id="dashboard-trend-chart-1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="float: right">
                            <span id="basic_setting">
                                <select  multiple="multiple" id="dashboard-trend-chart-1-choose" name="dashboard-trend-chart-1-choose[]">
                                    <?php
                                    $config = $customize_list['config'];
                                    unset($customize_list['config']);
                                    foreach($customize_list as $key => $value){
                                        $checked = ($value['select'] == 1) ? "selected" : "";
                                        echo "<option value='" . $key . "' $checked>"  .strtoupper($key). "</option>";
                                    }
                                    ?>
                                </select>

                            </span>
                            <span id="advance_setting" hidden="true">
                                <input type="text" value="<?php echo $config['times']?>" onkeypress="return (event.charCode==0) || (event.charCode >= 48 && event.charCode <= 57)" name="times" placeholder="Times" size="4" title="Number of day/week/month will be display">
                                <input type="text" value="<?php echo $config['left']?>" name="left-text" placeholder="Left text" size="9" title="Description on left side of chart">
                                <input type="text" value="<?php echo $config['right']?>" name="right-text" placeholder="Right text" size="9" title="Description on left side of chart">
                                <?php
                                    for($ii=1;$ii<=6;$ii++){
                                        ?>
                                        <div>
                                        <select name="dashboard-trend-chart-choose-<?php echo $ii?>">
                                            <option value="none"></option>
                                            <?php

                                            $once = false;
                                            $spline_checked = "";
                                            $column_checked= "";
                                            foreach($customize_list as $key => $value){
                                                if($once == false && $value['select'] == 1){
                                                    $checked = "selected";
                                                    $once = true;
                                                    $customize_list[$key]['select'] = 0;
                                                    if($customize_list[$key]['chart_type'] == "spline")
                                                        $spline_checked = "selected";
                                                    else
                                                        $column_checked = "selected";
                                                }else{
                                                    $checked = "";
                                                }

                                                echo "<option value='" . $key . "' $checked>"  .strtoupper($key). "</option>";
                                            }

                                            ?>
                                        </select>
                                        <select name="chart-type-<?php echo $ii?>">
                                            <option value="spline" <?php echo $spline_checked?>>Spline</option>
                                            <option value="column" <?php echo $column_checked?>>Column</option>
                                        </select>

                                        </div>
                                <?php
                                    }
                                ?>
                            </span>
                            <input type="hidden" name="input_setting_mode" id="input_setting_mode" value="basic">
                            <div><button id="setting_mode" value="basic" type="button" class="btn btn-link" onclick="change_setting_mode()">Show advance setting</button></div>
                            <div><input type="checkbox" id="allgame" name="allgame" >Apply for all games? </input></div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="container_1"></div>
                        </div>
                    </div>
                    <?php
                        echo $trend_chart;
                    ?>
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section>

<script type="text/javascript">
    function change_setting_mode(){
        var setting_mode = document.getElementById("setting_mode");
        var advance_setting = document.getElementById("advance_setting");
        var basic_setting = document.getElementById("basic_setting");
        var input_setting_mode = document.getElementById("input_setting_mode");
        if(setting_mode.getAttribute("value") == "basic"){
            advance_setting.hidden=false;
            basic_setting.hidden=true;
            setting_mode.innerHTML = "Show basic setting"
            setting_mode.setAttribute("value","advance")
            input_setting_mode.setAttribute("value","advance")
        }else{
            advance_setting.hidden=true;
            basic_setting.hidden=false;
            setting_mode.innerHTML = "Show advance setting"
            setting_mode.setAttribute("value","basic")
            input_setting_mode.setAttribute("value","basic")
        }
    }
</script>
