<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 22/06/2016
 * Time: 11:19
 */
?>
<div class="box box-primary">
    <div class="box-footer text-black text-left">
        <form name="form" action="" method="post" class="form-horizontal">
            <div class="row">
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
        </form>
    </div>
</div>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_kpi_key_compare" data-toggle="tab">Key KPI Compare</a>
        </li>
        <li>
            <a href="#tab_mail_report_status" data-toggle="tab">KPI Status</a>
        </li>
        <li>
            <a href="#tab_view_status" data-toggle="tab">View Status</a>
        </li>
        <li>
            <a href="#tab_mail_report_status" data-toggle="tab">Mail Report Status</a>
        </li>

    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tab_kpi_key_compare">
            <section id="section-revenue">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-warning">
                            <!--<div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-money" name="revenue"></i> Revenue</h3>
                            </div>-->
                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                        echo $key_kpi_compare;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </section>
        </div>

        <div class="tab-pane" id="tab_view_status">
            <section id="section-revenue">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-warning">
                            <!--<div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-money" name="revenue"></i> Revenue</h3>
                            </div>-->
                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                        echo $view_status;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </section>
        </div>

        <div class="tab-pane" id="tab_mail_report_status">
            <section id="section-revenue">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-warning">
                            <!--<div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-money" name="revenue"></i> Revenue</h3>
                            </div>-->
                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                        echo "In-progress";
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </section>
        </div>

    </div>
    <!-- /.tab-content -->
</div>