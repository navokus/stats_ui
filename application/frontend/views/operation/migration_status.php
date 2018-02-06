<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 20/09/2016
 * Time: 17:37
 */
?>

<div class="box box-primary">
    <div class="box-footer text-black text-left">
        <form name="form" action="" method="post" class="form-horizontal">
            <div class="row">
                <div class="col-md-4 col-lg-4 col-xs-6">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input value="<?php echo $body['day']['default_range_date'];?>"  id="daterangepicker" name="daterangepicker" class="form-control" />
	                            <span class="input-group-btn">
			            	        <button type="submit" class="btn btn-danger">Xem</button>
			        	        </span>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">

        <?php
        $first = true;
        foreach($tab_content as $kpi_type => $description) {
            $active="";
            if($first==true){
                $active= ' class="active"';
                $first=false;
            }
            ?>
            <li<?php echo $active?>>
                <a href="#tab_<?php echo $kpi_type?>" data-toggle="tab"><?php echo $description?></a>
            </li>
        <?php
        }
        ?>
    </ul>

    <div class="tab-content">
        <?php

        $first=true;
        foreach($data_table as $kpi_type => $value){
            $active = "";
            if($first==true){
                $active = "active";
                $first=false;
            }
            ?>

            <div class="tab-pane <?php echo $active?>" id="tab_<?php echo $kpi_type?>">
                <section>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-footer">
                                    <div class="row">
                                        <?php

                                        $_view_data['data'] = $value['data'];
                                        $_view_data['header'] = $value['header'];
                                        $_view_data['title'] = $value['title'];
                                        $_view_data['id'] = $value['id'];
                                        $_view_data['btn_download'] = $value['btn_download'];
                                        $_view_data['exportTitle'] = $value['exportTitle'];
                                        $html = $this->load->view("body_parts/table/common_table", $_view_data, TRUE);
                                        echo $html;
                                        ?>
                                    </div>
                                </div>
                            </div><!-- /.box -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </section>
            </div>

        <?php
        }
        ?>
    </div>
    <!-- /.tab-content -->
</div>
<script type="application/javascript">
    $('#migration_status_game_kpi').DataTable({
        lengthChange: false,
        pageLength: 1000,
        responsive: true,
        scrollX: true,
        scrollY: "500px",
        fixedColumns:   {leftColumns: 1},
        order: [],
        buttons: []
    });
    $('#migration_status_server_kpi').DataTable({
        lengthChange: false,
        pageLength: 1000,
        responsive: true,
        scrollX: true,
        scrollY: "500px",
        fixedColumns:   {leftColumns: 1},
        order: [],
        buttons: []
    });
    $('#migration_status_channel_kpi').DataTable({
        lengthChange: false,
        pageLength: 1000,
        responsive: true,
        scrollX: true,
        scrollY: "500px",
        fixedColumns:   {leftColumns: 1},
        order: [],
        buttons: []
    });
    $('#migration_status_os_kpi').DataTable({
        lengthChange: false,
        pageLength: 1000,
        responsive: true,
        scrollX: true,
        scrollY: "500px",
        fixedColumns:   {leftColumns: 1},
        order: [],
        buttons: []
    });
    $('#migration_status_package_kpi').DataTable({
        lengthChange: false,
        pageLength: 1000,
        responsive: true,
        scrollX: true,
        scrollY: "500px",
        fixedColumns:   {leftColumns: 1},
        order: [],
        buttons: []
    });
</script>