<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 08/06/2016
 * Time: 10:35
 */
?>

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-dashboard" name="overview"></i> Key KPI</h3>
                <a href="#overview" hidden="true" class="pull-right" data-toggle="collapse" data-target="#dashboard-overview"><span class="hidden-xs">Settings </span><i class="fa fa-gears"></i></a>
            </div>
            <div class="col-md-12">
                <form name="form"  action="<?php echo site_url('Dashboard/userconfig?type=overview-1'); ?>" method="post">
                    <div class="collapse" id="dashboard-overview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="float: right">
                        <select  multiple="multiple" id="dashboard-overview-choose" name="dashboard-overview-choose[]">
                            <?php
                            foreach($customize_list as $key => $value){
                                $check = ($value==1) ? "selected" : "";
                                echo "<option value='" . $key . "' $check> "  .strtoupper($key). "</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <div><input type="checkbox" id="allgame" name="allgame" >Apply for all games? </input></div>

                    </div>
                </form>
            </div>
            <?php
            echo '<div class="row">';
            foreach($data as $key => $value){
                echo '<div class="col-sm-3 col-xs-6">';
                echo '<div class="description-block border-right">';

                if($value['percent'] > 0) {
                    $h1 = "text-green";
                    $h2 = "fa fa-caret-up";
                }else if ($value['percent'] < 0){
                    $h1 = "text-red";
                    $h2 = "fa fa-caret-down";
                }else{
                    $h1 = "text-yellow";
                    $h2 = "fa fa-caret-left";
                }
                $value['percent'] = round($value['percent'],2) . "%";
                echo $value['kpi_name'].' <span class="description-percentage '.$h1.'"><i class="'.$h2.'"></i> '.$value['percent'].'</span>';
                echo '<h5 class="description-header">'.number_format($value['data']).'</h5>';
                echo '<span class="description-text">'.$value['description'].'</span>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            ?>
        </div>
    </div><!-- /.box -->
</div><!-- /.col -->
