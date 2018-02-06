<?php
echo $selection;

?>

<div class="row">
    <div class="col-md-12">
        <form role="form" name="add" method="post" action="">
            <div class="box">
                <div class="box-body">
                    <?php
                    //generate menu by game_code
                    foreach($all_report as $group_id => $v){
                        $group_name = $v['group_name'];
                        $class_1 = $v['class_1'];
                        $class_2 = $v['class_2'];

                        $report_detail = $v['report_detail'];
                        echo '<div class="row">';
                        echo '<div class="box-header">
                                    <h3 class="box-title">'.$group_name.'</h3>
                                  </div>';

                        foreach($report_detail as $report_id => $v_1){
                            $report_name = $v_1['report_name'];
                            $url = $v_1['report_url'];
                            $class_1 = $v_1['class_1'];
                            $class_2 = $v_1['class_2'];

                            $check = "";
                            if(isset($game_report[$group_id]['report_detail'][$report_id]))
                                $check = "checked";


                            echo '<div class="col-md-2">
                                    <div class="form-group">
                                        <input type="checkbox" name="'.$report_id.'" '.$check.' value="'.$report_id.'" >
                                        <label>'.$report_name.'</label>
                                    </div>
                                </div>';

                        }
                        echo '</div>';
                    }
                    ?>
                </div><!-- /.box-body -->
                <input type="hidden" value="updatereport" name="updatereport">
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div><!-- /.box -->
        </form>



        <form role="form" name="add" method="post" action="">
            <div class="box">
                <div class="box-body">

                    <div class="row">
                        <div class="box-header">
                            <h3 class="box-title">Add new report group</h3>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>GroupId</label>
                                <input type="textbox" name="groupId" placeholder="gamekpi" >
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>GroupName</label>
                                <input type="textbox" name="groupName" placeholder="Game KPI" >
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>HTML class 1</label>
                                <input type="textbox" name="class_1" value="fa fa-bar-chart">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>HTML class 2</label>
                                <input type="textbox" name="class_2" value="fa fa-angle-left pull-right">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Position</label>
                                <input type="number" name="position" >
                            </div>
                        </div>

                    </div>
                </div><!-- /.box-body -->
                <input type="hidden" value="addgroup" name="addgroup">
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div><!-- /.box -->
        </form>


        <form role="form" name="add" method="post" action="">
            <div class="box">
                <div class="box-body">

                    <div class="row">
                        <div class="box-header">
                            <h3 class="box-title">Add new report</h3>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>ReportId</label>
                                <input type="textbox" name="reportId" placeholder="dailyreport">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>ReportName</label>
                                <input type="textbox" name="reportName" placeholder="Daily Report">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Url</label>
                                <input type="textbox" name="url" placeholder="kpi/daily" >
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sel1">ReportGroup</label>
                                <select class="form-control" id="reportGroup" name="reportGroup">
                                    <?php foreach($group_report as $key => $value) {  ?>
                                    <option value="<?php echo $key?>" > <?php echo $value?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>HTML class</label>
                                <input type="textbox" name="class_2" value="fa fa-circle-o">
                            </div>
                        </div>


                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Position</label>
                                <input type="number" name="position" >
                            </div>
                        </div>

                    </div>
                </div><!-- /.box-body -->
                <input type="hidden" value="addreport" name="addreport">
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div><!-- /.box -->
        </form>


    </div>
</div>


