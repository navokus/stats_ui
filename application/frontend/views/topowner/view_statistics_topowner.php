<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 16/09/2016
 * Time: 09:40
 */
FUNCTION format_cash($cash) {
    // strip any commas
    $cash = (0 + STR_REPLACE(',', '', $cash));

    // make sure it's a number...
    IF(!IS_NUMERIC($cash)){ RETURN FALSE;}

    // filter and format it
    IF($cash>1000000000000){
        RETURN ROUND(($cash/1000000000000),2).' trillion';
    }ELSEIF($cash>1000000000){
        RETURN ROUND(($cash/1000000000),2).' B';
    }ELSEIF($cash>1000000){
        RETURN ROUND(($cash/1000000),2).' M';
    }ELSEIF($cash>1000){
        RETURN ROUND(($cash/1000),2).' K';
    }

    RETURN NUMBER_FORMAT($cash);
}

?>
<div class="box box-primary">
    <div class="box-footer text-black text-left">
        <form name="form" action="" method="post">
            <div class="box-body">
                <div class="row">
                    <div class="option_day">
                        <div class="col-md-3 col-lg-2 col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                <select class="form-control" name="options" id="slTiming" style="width: 100%;" onchange="this.form.submit()">
                                    <option value="1" <?php echo((1 == $_POST['options']) ? 'selected' : '') ?> >Daily</option>
                                    <option value="7" <?php echo((7 == $_POST['options']) ? 'selected' : '') ?> >Weekly</option>
                                    <option value="30" <?php echo((30 == $_POST['options']) ? 'selected' : '') ?> >Monthly</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="input-group" id="inputDate">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input value="<?php echo $body['day']['kpidatepicker'];?>" id="kpidatepicker" name="kpidatepicker" class="form-control" />
                                <span class="input-group-btn">
			            	<button type="submit" class="btn btn-danger">Xem</button>
			        	</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_rev" data-toggle="tab">Revenue</a>
        </li>
        <li>
            <a href="#tab_a1" data-toggle="tab">Active</a>
        </li>
    </ul>
    <!--code  -->
    <div class="tab-content">
        <!-- --><?php

        $first=true;
        foreach($selection as $key => $value){
            $active = "";
            if($first==true){
                $active = "active";
                $first=false;
            }
            ?>
            <div class="tab-pane <?php echo $active?>" id="tab_<?php echo $key?>">
                <section>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-footer">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div id="container_pie_chart_<?php echo $key ?>"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="container_bar_chart<?php echo $key ?>"></div>
                                        </div>
                                        <!--script -->
                                        <?php
                                        $data_bar_chart = $data[$key]['data_bar_chart'];
                                        $data_bar_chart['title'] = "Top 10 Owner ($key)";
                                        $data_bar_chart['id'] = "container_bar_chart$key";
                                        $data_bar_chart['chart_width'] = "500";

                                        $html = $this->load->view("topowner/bar", $data_bar_chart, TRUE);
                                        echo $html;

                                        $pie_chart = $data[$key];
                                        $pie_chart['id'] = "container_pie_chart_$key";
                                        $pie_chart['title'] = "Total $key";
                                        $html = $this->load->view("topowner/pie", $pie_chart, TRUE);
                                        echo $html; ?>



                                    </div>
                                </div>
                            </div><!-- /.box -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-th"></i> <?php echo $key; ?></h3>
                                    <div class="box-tools">
                                        <a class="btn btn-box-tool" href="#" title="Copy to clipboard!"  id="copy">
                                            <img src="<?php echo base_url('public/frontend/dist/img/copy.png'); ?>" width="22px" height="22px" />
                                        </a>
                                        <a class="btn btn-box-tool" href="#" title="Download excel file!" id="downloadExcel">
                                            <img src="<?php echo base_url('public/frontend/dist/img/export.gif'); ?>" width="20px" height="20px" />
                                        </a>
                                    </div>
                                </div>
                                <div class="box-body text-center first">
                                    <div class="table-responsive kpi-table">
                                        <div id="all_kpi_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                            <table class ="table table-striped table-bordered" id = "<?php echo $key?>_report"width="100%">
                                                <thead class="thead-inverse">
                                                <tr>
                                                    <th class="text-left" style="width: auto;">Date</th>
                                                    <th class="text-left" style="width: auto;">Owner</th>
                                                    <th class="text-left" style="width: auto;">Game Name</th>
                                                    <th class="text-left" style="width: auto;"><?php echo $key; ?></th>
                                                </tr>
                                                </thead>
                                                <?php

                                                    $tableData = $data[$key]['detail_table'];

                                                for ($j = 0; $j < count($tableData); $j++) {
                                                    ?>
                                                    <tr>
                                                        <td class="text-left"><?php echo $tableData[$j]['report_date'] ?></td>
                                                        <td class="text-left"><?php echo $tableData[$j]['owner'] ?></td>
                                                        <td class="text-left"><?php echo $tableData[$j]['GameName'] ?></td>
                                                        <td class="text-left"><?php echo number_format($tableData[$j]['kpi_value']) ?></td>
                                                    </tr>
                                                <?php }
                                                ?>
                                            </table>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-content -->
                        </div>
                    </div>
                </section>
            </div>


            <?php
        }
        ?>
    </div>
    <!-- /.tab-content -->

</div>


