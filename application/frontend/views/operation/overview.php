<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 22/06/2016
 * Time: 11:19
 */

$data = $overview['db_data']['data'];
$data_source = $overview['db_data']['data_source'];

$game_filter = $overview['game_filter'];

$day_number = $overview['config']['day_number'];
$kpi_config = $overview['config']['kpi_config'];
$day_arr = $overview['config']['day_arr'];
$game_list = (array_keys($data));

$percent_config = $overview['config']['percent_config'];

$header['game_code'] = "GameCode";
$header['game_name'] = "GameName";
$header['source'] = "Source";
$header[$day_arr[0]] = $day_arr[0];
$header[$day_arr[1]] = $day_arr[1];
$header['1daycompare_nag'] = "-1 day compare";
for($i=2;$i<count($day_arr);$i++){
    $header[$day_arr[$i]] = $day_arr[$i];
}
$html_data = array();
$game_not_report = array();
foreach($kpi_config as $_kpi_code => $kpi_description){
    $html_first = array();
    $html_after = array();
    $html_priority = array();
    for($i=0;$i<count($game_list);$i++){
        $_game_code = $game_list[$i];
        if(isset($game_not_report[$_kpi_code]) && in_array(strtoupper($_game_code), $game_not_report[$_kpi_code])){
            continue;
        }
        $value_check = isset($data[$_game_code][$_kpi_code][$day_arr[0]]) ? $data[$_game_code][$_kpi_code][$day_arr[0]] : 0;
        $total=0;
        $html_t1 = array();
        for($j=1;$j<count($day_arr);$j++){
            $_report_date = $day_arr[$j];
            $value_by_day = isset($data[$_game_code][$_kpi_code][$_report_date]) ? $data[$_game_code][$_kpi_code][$_report_date] : 0;

            $total += $value_by_day;
            if($j!=1){
                $html_t1[] = ($value_by_day);
            }
        }

        $value_1_day_ago = isset($data[$_game_code][$_kpi_code][$day_arr[1]]) ? $data[$_game_code][$_kpi_code][$day_arr[1]] : 0;

        if($value_1_day_ago==0){
            $percent_1=0;

        }else {
            $percent_1 = (($value_check - $value_1_day_ago) / $value_1_day_ago) * 100;
        }
        $cl = $value_check - $value_1_day_ago;
        $alert = false;
        $priority = false;

        if(
            ($value_check > 10 || $value_check ==0) &&
            ((abs($percent_1) >= 99 and abs($percent_1) <= 101) || (abs($percent_1) > 1 and abs($percent_1)%50==0) || (abs($percent_1) == 0) )
        ){
            $alert = true;
            $priority = true;
        }else{

            $percent_alert = get_percent_from_total($percent_config,$_kpi_code,$value_check);

            if(abs($percent_1) >= $percent_alert || $game_filter=='allgame'){
                $alert = true;
            }
        }

        $percent_1 = number_format($percent_1, 2);
        if ($percent_1 > 0)
            $percent_1 = "+" . $percent_1;

        $html_t2 = array();
        $total_value = $value_check + $value_1_day_ago;
        if($alert==true){
            $html_t2['gc'] = strtoupper($_game_code);
            $html_t2['gn'] = $data_source[$_game_code]['game_name'];
            $html_t2['dts'] = $data_source[$_game_code][$_kpi_code];
            $html_t2['vc'] = ud_format_number($value_check);
            $html_t2['v1dg'] = ud_format_number($value_1_day_ago);
            $html_t2['cp'] = $percent_1 . "% (" . ud_format_number($cl) . ")" ;
            for($p = 0;$p <count($html_t1); $p++){
                $html_t2['d'.$p] = ud_format_number($html_t1[$p]);
                $total_value += $html_t1[$p];
            }


            if($total_value == 0){
                $game_not_report[$_kpi_code][] = $_game_code;
                continue;
            }

            if($priority==true){
                $html_priority[] = $html_t2;
            }else{
                if($percent_1>=0){
                    $html_first[] = $html_t2;
                }else{
                    $html_after[] = $html_t2;
                }
            }
        }
    }

    $data_table = array_merge($html_priority, $html_first, $html_after);

    $view_data['data'] = $data_table;
    $view_data['header'] = $header;
    $view_data['btn_download'] = "false";
    $view_data['title'] = "Overview last " . $day_number . " days for " . $kpi_description;
    $view_data['id'] = "operation_overview_" . $_kpi_code;
    $view_data['exportTitle'] = "exportTitle";

    $html_data[$_kpi_code] = $view_data;
}

function ud_format_number($number){
    $number = number_format($number);
    return $number;
}

function get_percent_from_total($percent_config,$_kpi_code,$total){
    foreach($percent_config[$_kpi_code] as $key => $value){
        $key_number = intval($key);
        if($total > $key_number){
            return $value;
        }
    }
    return 2000;
}

?>
<div class="box box-primary">
    <div class="box-footer text-black text-left">
        <form name="form" action="" method="post" class="form-horizontal">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="input-group" id="inputDate">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input value="<?php echo $body['day']['kpidatepicker'];?>" id="kpidatepicker" name="kpidatepicker" class="form-control" onchange="this.form.submit()" />
				    	<span class="input-group-btn">
			            	<button type="submit" class="btn btn-danger" >Xem</button>
			        	</span>
                    </div>
                    <?php
                    if($game_filter == "allgame"){ $t_1 = "checked"; $t2="";}
                    else { $t_2 = "checked"; $t1="";}

                    ?>
                    <input type="radio" name="gamefilter" value="allgame" <?php echo $t_1; ?>> All Games &nbsp;
                    <input type="radio" name="gamefilter" value="issuegame" <?php echo $t_2; ?>> Issue Games
                </div>
            </div>
        </form>
    </div>
</div>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">

        <?php
        $active_before = isset($_COOKIE['odrevenue']) ? $_COOKIE['odrevenue'] : "compare";
        if($active_before == "compare"){
            $compare_tab_active = array(" class='active'"," active");
            $synchronized_tab_active = array("","");
        }else{
            $compare_tab_active = array("","");
            $synchronized_tab_active = array(" class='active'"," active");
        }

        $cookie = isset($_COOKIE['overview']) ? $_COOKIE['overview'] : "";
        $keys = array_keys($kpi_config);
        if(in_array($cookie, $keys)){
            $tab_active = $cookie;
        }else{
            $tab_active = $keys[0];
        }

        foreach($kpi_config as $_kpi_code => $kpi_description) {
            $active="";
            if($tab_active==$_kpi_code){
                $active= ' class="active"';
            }
            ?>
            <li<?php echo $active?>>
                <a href="#tab_<?php echo $_kpi_code?>" data-toggle="tab" onclick="changeTab('<?php echo $_kpi_code?>')"><?php echo $kpi_description?></a>
            </li>
        <?php
        }
        ?>
    </ul>

    <div class="tab-content">
        <?php
        foreach($kpi_config as $_kpi_code => $kpi_description) {
            $active="";
            if($tab_active==$_kpi_code){
                $active= ' active';
            }
            ?>
            <div class="tab-pane<?php echo $active?>" id="tab_<?php echo $_kpi_code?>">
                <section>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php
                                            if(isset($html_data[$_kpi_code])){
                                                $_data = $html_data[$_kpi_code];
                                                $_view_data['data'] = $_data['data'];
                                                $_view_data['header'] = $_data['header'];
                                                $_view_data['title'] = $_data['title'];
                                                $_view_data['id'] = $_data['id'];
                                                $_view_data['btn_download'] = $_data['btn_download'];
                                                $_view_data['exportTitle'] = $_data['exportTitle'];
                                                $html = $this->load->view("body_parts/table/common_table", $_view_data, TRUE);

                                            }else{
                                                $html = "";
                                            }
                                            if($html != "") {
                                                if (isset($game_not_report[$_kpi_code])) {
                                                    $t = "";
                                                    for ($i = 0; $i < count($game_not_report[$_kpi_code]); $i++) {
                                                        $g = strtoupper($game_not_report[$_kpi_code][$i]);
                                                        $t .= $g . ", ";
                                                    }
                                                    $t = substr($t, 0, -2) . ".";
                                                    ?>

                                                    <div class="alert alert-info alert-dismissible">
                                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                        The following games have not <strong> <?php echo strtoupper($_kpi_code) ?> </strong> report in last <?php echo $day_number?> days, and will not show in data table:<br>
                                                        <?php echo $t ?>
                                                    </div>
                                                <?php
                                                }
                                                echo $html;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.box -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </section>
            </div>

        <?php } ?>
    </div>
    <!-- /.tab-content -->
</div>

<?php
$search = isset($_GET['tb-search']) ? $_GET['tb-search'] : "";
?>
<script type="application/javascript">
    if ($('#operation_overview_gr1').length) {
        $('#operation_overview_gr1').DataTable({
            lengthChange: false,
            pageLength: 1000,
            responsive: true,
            /*scrollX: true,*/
            scrollY: "500px",
            oSearch: {sSearch: '<?php echo $search ?>'},
            fixedColumns:   {leftColumns: 1},
            order: [],
            buttons: []
        });
    }
    if ($('#operation_overview_a1').length) {
        $('#operation_overview_a1').DataTable({
            lengthChange: false,
            pageLength: 1000,
            responsive: true,
            scrollY: "500px",
            oSearch: {sSearch: '<?php echo $search ?>'},
            fixedColumns:   {leftColumns: 1},
            order: [],
            buttons: []
        });
    }
    if ($('#operation_overview_n1').length) {
        $('#operation_overview_n1').DataTable({
            lengthChange: false,
            pageLength: 1000,
            responsive: true,
            scrollY: "500px",
            oSearch: {sSearch: '<?php echo $search ?>'},
            fixedColumns:   {leftColumns: 1},
            order: [],
            buttons: []
        });
    }
    if ($('#operation_overview_npu1').length) {
        $('#operation_overview_npu1').DataTable({
            lengthChange: false,
            pageLength: 1000,
            responsive: true,
            scrollY: "500px",
            oSearch: {sSearch: '<?php echo $search ?>'},
            fixedColumns:   {leftColumns: 1},
            order: [],
            buttons: []
        });
    }
    if ($('#operation_overview_acu1').length) {
        $('#operation_overview_acu1').DataTable({
            lengthChange: false,
            pageLength: 1000,
            responsive: true,
            scrollY: "500px",
            oSearch: {sSearch: '<?php echo $search ?>'},
            fixedColumns:   {leftColumns: 1},
            order: [],
            buttons: []
        });
    }
    function changeTab(tab_id){
        createCookie("overview", tab_id, 1);
    }
</script>