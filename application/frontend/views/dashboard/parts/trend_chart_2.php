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
                    <h3 class="box-title"><i class="fa fa-user" name="User"></i> User</h3>
                    <a href="#User"class="pull-right" data-toggle="collapse" hidden="true" data-target="#dashboard-trend-chart-2"><span class="hidden-xs">Settings </span><i class="fa fa-gears"></i></a>
                </div>
                <div class="col-md-12">
                    <form name="form"  action="<?php echo site_url('Dashboard/userconfig?type=trend-chart-2'); ?>" method="post">
                        <div class="collapse" id="dashboard-trend-chart-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="float: right">
                            <span id="basic_setting_2">
                                <select  multiple="multiple" id="dashboard-trend-chart-2-choose" name="dashboard-trend-chart-2-choose[]">
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
                            <span id="advance_setting_2" hidden="true">
                                <input type="text" value="<?php echo $config['times']?>" onkeypress="return (event.charCode==0) || (event.charCode >= 48 && event.charCode <= 57)" name="times-2" placeholder="Times" size="3" title="Number of day/week/month will be display">
                                <input type="text" value="<?php echo $config['left']?>" name="left-text-2" placeholder="Left text" size="9" title="Description on left side of chart">
                                <input type="text" value="<?php echo $config['right']?>" name="right-text-2" placeholder="Right text" size="9" title="Description on left side of chart">
                                <?php
                                for($ii=1;$ii<=6;$ii++){
                                    ?>
                                    <div>
                                        <select name="dashboard-trend-chart-2-choose-<?php echo $ii?>">
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
                                        <select name="chart-type-2-<?php echo $ii?>">
                                            <option value="spline" <?php echo $spline_checked?>>Spline</option>
                                            <option value="column" <?php echo $column_checked?>>Column</option>
                                        </select>
                                    </div>
                                <?php
                                }
                                ?>
                            </span>
                            <input type="hidden" name="input_setting_mode_2" id="input_setting_mode_2" value="basic">
                            <div><button id="setting_mode_2" value="basic" type="button" class="btn btn-link" onclick="change_setting_mode_2()">Show advance setting</button></div>
                            <div><input type="checkbox" id="allgame" name="allgame" >Apply for all games? </input></div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="container_2"></div>
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
    function change_setting_mode_2(){
        var setting_mode_2 = document.getElementById("setting_mode_2");
        var advance_setting_2 = document.getElementById("advance_setting_2");
        var basic_setting_2 = document.getElementById("basic_setting_2");
        var input_setting_mode_2 = document.getElementById("input_setting_mode_2");
        if(setting_mode_2.getAttribute("value") == "basic"){
            advance_setting_2.hidden=false;
            basic_setting_2.hidden=true;
            setting_mode_2.innerHTML = "Show basic setting"
            setting_mode_2.setAttribute("value","advance")
            input_setting_mode_2.setAttribute("value","advance")
        }else{
            advance_setting_2.hidden=true;
            basic_setting_2.hidden=false;
            setting_mode_2.innerHTML = "Show advance setting"
            setting_mode_2.setAttribute("value","basic")
            input_setting_mode_2.setAttribute("value","basic")
        }
    }
</script>
