<?php
echo $body['kpi_selection'];
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-th"></i> DataTable Monthly Export</h3>
                <div class="box-tools">
                    <a class="btn btn-box-tool" href="#" title="Copy to clipboard!" id="copy">
                        <img src="<?php echo base_url('public/frontend/dist/img/copy.png'); ?>" width="22px"
                             height="22px"/>
                    </a>
                    <a class="btn btn-box-tool" href="#" title="Download excel file!" id="downloadExcel">
                        <img src="<?php echo base_url('public/frontend/dist/img/export.gif'); ?>" width="20px"
                             height="20px"/>
                    </a>
                </div>
            </div>
            <div class="box-body text-center first">
                <div class="table-responsive kpi-table">
                    <div id="all_kpi_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <table class ="table table-striped table-bordered" id = "exp_monthly_report" data-export-title = "monthly_report" width="100%">
                            <thead class="thead-inverse">
                            <tr>
                                <th class="text-left" >Game</th>
                                <th class="text-left" >Game Name</th>
                                <th class="text-left" >Platform</th>
                                <th class="text-left" >Dept</th>
                                <th class="text-left" >Date</th>
                                <th class="text-left" >Avegare A1</th>
                                <th class="text-left" >A30</th>
                                <th class="text-left" >Monthly Active Users</th>
                                <th class="text-left" >Peak A1</th>
                                <th class="text-left" >PU30</th>
                                <th class="text-left" >Monthly Paying Users</th>
                                <th class="text-left" >Monthly Revenue</th>
                                <th class="text-left" >Revenue in 30days</th>
                                <th class="text-left" >N30</th>
                                <th class="text-left" >Monthly New Paying Users</th>
                                <th class="text-left" >Monthly Avegare ACU</th>
                                <th class="text-left" >Monthly Avegare PCU</th>
                                <th class="text-left" >Monthly Peak PCU</th>
                                <th class="text-left" >Monthly ARPPU</th>
                                <th class="text-left" >Monthly Average Payrate</th>
                                <th class="text-left" >Monthly Average RR1</th>
                                <th class="text-left" >Monthly Average RR3</th>
                                <th class="text-left" >Monthly Average RR7</th>
                                <th class="text-left" >Monthly Average RR30</th>
                            </tr>
                            </thead>
                            <?php

                            for ($i = 0; $i < count($data); $i++) {
                                ?>
                                <tr>
                                    <td class="text-left"><?php echo $data[$i]['game'] ?></td>
                                    <td class="text-left"><?php echo $data[$i]['game_name'] ?></td>
                                    <td class="text-left"><?php echo $data[$i]['platform'] ?></td>
                                    <td class="text-left"><?php echo $data[$i]['dept'] ?></td>
                                    <td class="text-left"><?php echo date('M-Y', strtotime($data[$i]['date'])); ?></td>
                                    <td class="text-left"><?php echo round($data[$i]['aa1'][0]) ?></td>
                                    <td class="text-left"><?php echo round($data[$i]['a30'][0])?></td>
                                    <td class="text-left"><?php echo round($data[$i]['am'][0]) ?></td>
                                    <td class="text-left"><?php echo round($data[$i]['pa1'][0]) ?></td>
                                    <td class="text-left"><?php echo round($data[$i]['pu30'][0]) ?></td>
                                    <td class="text-left"><?php echo round($data[$i]['pum'][0]) ?></td>
                                    <td class="text-left"><?php echo round($data[$i]['grm'][0])?></td>
                                    <td class="text-left"><?php echo round($data[$i]['gr30'][0]) ?></td>
                                    <td class="text-left"><?php echo round($data[$i]['n30'][0]) ?></td>
                                    <td class="text-left"><?php echo round($data[$i]['npum'][0]) ?></td>
                                    <td class="text-left"><?php echo round($data[$i]['aacu1'][0]) ?></td>
                                    <td class="text-left"><?php echo round($data[$i]['apcu1'][0]) ?></td>
                                    <td class="text-left"><?php echo round($data[$i]['ppcu1'][0]) ?></td>
                                    <td class="text-left"><?php echo round($data[$i]['grm'][0]/$data[$i]['pum'][0],2) ?></td>
                                    <td class="text-left"><?php echo round($data[$i]['payrate'][0],2) ?></td>
                                    <td class="text-left"><?php echo round($data[$i]['arnr1'][0],2) ?></td>
                                    <td class="text-left"><?php echo round($data[$i]['arnr3'][0],2) ?></td>
                                    <td class="text-left"><?php echo round($data[$i]['arnr7'][0],2) ?></td>
                                    <td class="text-left"><?php echo round($data[$i]['arnr30'][0],2) ?></td>

                                </tr>
                            <?php }
                            ?>
                        </table>

                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <span id="exportFileName" hidden="true"><?php echo "export_monthly123" ?></span>
    </div>


</div>
<script type="application/javascript">
    var exportTitle = $("#exp_monthly_report").data("export-title");
    if ($('#exp_monthly_report').length) {
        $('#exp_monthly_report').dataTable( {
            scrollX:true,
            dom: 'Bfrtip',
            buttons:[
                { extend: 'excel',title: exportTitle},
                'copy'
            ],
            "ordering": false,
            "pageLength": 20
        } );

    }
</script>





