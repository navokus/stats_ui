<?php
echo $body['selection'];

if($nodata == true){
    echo "<p>Hiện không có dữ liệu của thời gian trên, bạn vui lòng chọn ngày khác,
     hoặc liên hệ <b>[canhtq@vng.com.vn or quangctn@vng.com.vn or lamnt6@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";
}else{
    $active_before = isset($_COOKIE['odrevenue']) ? $_COOKIE['odrevenue'] : "compare";
    if($active_before == "compare"){
        $compare_tab_active = array(" class='active'"," active");
        $synchronized_tab_active = array("","");
    }else{
        $compare_tab_active = array("","");
        $synchronized_tab_active = array(" class='active'"," active");
    }
    ?>

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li<?php echo $compare_tab_active[0] ?>>
                <a href="#tab_type1" data-toggle="tab" onclick="changeTab('compare')">Compare</a>
            </li>

            <li<?php echo $synchronized_tab_active[0] ?>>
                <a href="#tab_type2" data-toggle="tab" onclick="changeTab('synchronized')">Synchronized</a>
            </li>
        </ul>

        <div class="tab-content">
            <?php
            $tab_id = "type1";
            $data = isset($odd_data[$tab_id]) ? $odd_data[$tab_id] : array();
            ?>
            <div class="tab-pane<?php echo $compare_tab_active[1]?>" id="tab_<?php echo $tab_id?>">
                <?php
                $list = array(
                    array("gr1", "pu1"),
                    array("n1", "a1")
                );
                foreach($list as $section){
                    ?>
                    <section id="section-revenue">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box">
                                    <div class="box-footer">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php
                                                foreach($section as $kpi_code){
                                                    $kpi_data = $data[$kpi_code];
                                                    if(count($kpi_data)!=0){
                                                        ?>
                                                        <div class="col-md-12">
                                                            <div id="container_<?php echo $tab_id . "_" . $kpi_code?>"></div>
                                                        </div>

                                                        <?php
                                                        $_data = $data[$kpi_code];
                                                        $_data['id']= "container_" . $tab_id . "_" . $kpi_code;
                                                        $html = $this->load->view("body_parts/chart/compare2day", $_data, TRUE);
                                                        echo $html;
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                </div><!-- /.box -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </section>
                <?php
                }
                ?>
            </div>

            <div class="tab-pane<?php echo $synchronized_tab_active[1]?>" id="tab_type2">
                <div class="pull-right"><strong><?php echo $syncData['synchronized_title']; ?></strong></div>
                <?php echo $syncData['charts']; ?>
            </div>

        </div>
    </div>
<?php } ?>

<script>
    function changeTab(tab_id){
        createCookie("odrevenue", tab_id, 1);
    }
</script>