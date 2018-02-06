<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 16/09/2016
 * Time: 09:40
 */


?>
<div class="nav-tabs-custom">
    <!--code  -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-th"></i> <?php echo $key; ?></h3>
                    <div class="box-tools">
                        <a class="btn btn-box-tool" href="#" title="Copy to clipboard!" id="copy">
                            <img src="<?php echo base_url('public/frontend/dist/img/copy.png'); ?>"
                                 width="22px" height="22px"/>
                        </a>
                        <a class="btn btn-box-tool" href="#" title="Download excel file!"
                           id="downloadExcel">
                            <img src="<?php echo base_url('public/frontend/dist/img/export.gif'); ?>"
                                 width="20px" height="20px"/>
                        </a>
                    </div>
                </div>
                <table class="table table-striped table-bordered" border="1px" id="server_test_report"
                       width="100%" data-export-title="<?php echo $title ?>_report">
                    <thead>
                    <tr>

                        <th class="text-left" style="width: auto;">Date</th>
                        <th class="text-left" style="width: auto;">Server ID</th>
                        <th class="text-left" style="width: auto;">Value</th>

                    </tr>
                    </thead>
                    <?php foreach ($data as $report_date => $data2) {
                        foreach ($data2['jxm'][$kpi] as $serverId => $value) {
                            ?>
                            <tr>
                                <td><?php echo $report_date; ?></td>
                                <td><?php echo $serverId; ?></td>
                                <td><?php echo $value; ?></td>
                            </tr>

                        <?php }
                    } ?>
                </table>
                <!--<div class="box-body text-center first">
                    <div class="table-responsive kpi-table">
                        <div id="all_kpi_wrapper"
                             class="dataTables_wrapper form-inline dt-bootstrap no-footer">

                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>-->
            </div>
            <!-- /.tab-content -->
        </div>
    </div>


</div>
<!-- /.tab-content -->

</div>

