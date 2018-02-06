<div class="box box-primary">
    <form name="form" action="" method="post" id="selectionForm">
        <div class="box-body">
            <div class="row">
                <div class="col-md-3 col-lg-3 col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-gamepad"></i></span>
                        <select class="form-control" name="default_game" id="slGameSelection"
                                onchange="this.form.submit()">
                            <?php
                            $list_games = $body['aGames'];
                            foreach ( $list_games as $value ) {
                                if ($this->session->userdata ( 'default_game' ) == $value ['GameCode']) {
                                    $selected = ' selected ';
                                } else {
                                    $selected = '';
                                }

                                echo "<option value='{$value['GameCode']}' {$selected} >{$value['GameName']} (" . strtoupper ( $value ['GameCode'] ) . ")</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-lg-2 col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                        <select class="form-control" name="options" id="slTiming" style="width: 100%;" onchange="this.form.submit()">
                            <option value="4" <?php echo((4 == $body['options']) ? 'selected' : '') ?> >Daily</option>
                            <option value="17" <?php echo((17 == $body['options']) ? 'selected' : '') ?> >Weekly</option>
                            <option value="31" <?php echo((31 == $body['options']) ? 'selected' : '') ?> >Monthly</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xs-12 option_time option_day hide">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input value="<?php echo $body['day']['default_range_date'];?>"  id="daterangepicker" name="daterangepicker" class="form-control" />
                        <span class="input-group-btn">
	                    	<button type="submit" class="btn btn-danger">Xem</button>
	                    </span>
                    </div>
                </div>
                <div class="option_time option_week hide">
                    <div class="col-md-3 col-lg-3 col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <select class="form-control" id="wpw2" name="week[2]" style="width: 100%;">
                                <?php
                                foreach ($body['optionsWeek'] as $key => $value) {

                                    if ($body['week']['2'] == $key) {
                                        $selected = ' selected ';
                                    } else {
                                        $selected = '';
                                    }

                                    echo "<option value='". $key ."' ". $selected .">". $value ."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <select class="form-control" id="wpw1" name="week[1]" style="width: 100%;">
                                <?php
                                foreach ($body['optionsWeek'] as $key => $value) {

                                    if ($body['week']['1'] == $key) {
                                        $selected = ' selected ';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option value='" . $key . "' " . $selected . ">" . $value . "</option>";
                                }
                                ?>
                            </select>
                            <span class="input-group-btn">
				            	<button type="submit" class="btn btn-danger">Xem</button>
				        	</span>
                        </div>
                    </div>
                </div>
                <div class="option_time option_month hide">
                    <div class="col-md-2 col-lg-3 col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <select class="form-control" id="mpm2" name="month[2]" style="width: 100%;">
                                <?php
                                foreach ($body['optionsMonth'] as $key => $value) {

                                    if ($body['month'][2] == $key) {
                                        $selected = ' selected ';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option value='". $key ."' ". $selected .">". $value ."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-lg-3 col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <select class="form-control" id="mpm1" name="month[1]" style="width: 100%;">
                                <?php
                                foreach ($body['optionsMonth'] as $key => $value) {
                                    if ($body['month'][1] == $key) {
                                        $selected = ' selected ';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option value='" . $key . "' " . $selected . ">" . $value . "</option>";
                                }
                                ?>
                            </select>
                            <span class="input-group-btn">
				            	<button type="submit" class="btn btn-danger">Xem</button>
				        	</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-th"></i> Country List</h3>
                        </div>
                        <div class="box-footer">
                            <?php if(count($countrygroup) != 0) {?>
                                <div class="row">
                                    <div class="sliding">
                                        <?php
                                        $selectedArr = array();
                                        foreach($selectedCountry as $nameSelected){
                                            if (!in_array($nameSelected, $selectedArr)){
                                                $selectedArr[] = $nameSelected;
                                            }
                                        }
                                        foreach($countrygroup as $name){

                                            ?>
                                            <div class="col-md-2 col-sm-4 col-xs-6">
                                                <label title="SID: <?php echo $name?>"> <input type="checkbox" name="selectedCountry[]" value="<?php echo $name ?>"
                                                        <?php if(in_array($name, $selectedArr))
                                                        {
                                                            echo 'checked="checked"';}?>>
                                                    <?php echo $name; ?></label>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-12">
                                        <button id="checkAll" class="btn btn-primary btn-sm">Check All</button>
                                        <button id="clearAllCheckBoxes" class="btn btn-primary btn-sm">Clear All</button>
                                        <button id="formSubmit" class="btn btn-primary btn-sm">Submit</button>
                                        <a href="#" class="show_hide btn btn-sm btn-primary pull-right"><span class="fa fa-plus expand-btn"></span><span class="fa fa-minus expand-btn hidden"></span></a>
                                    </div>
                                </div>
                            <?php } else {
                                $html = $this->load->view("body_parts/contact", null, TRUE);
                                echo '<div class="row">' . $html . '</div>';
                            }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active tab-selection"><a href="#tab_active" data-toggle="tab" data-id="active">Active</a></li>
            <li class="tab-selection"><a href="#tab_revenue" data-toggle="tab" data-id="revenue">Revenue</a></li>
        </ul>

        <div class="tab-content">

            <div class="tab-pane active" id="tab_active">
                <div class="row">
                    <div class="col-md-12">
                        <div id="active" class="chart-area text-center"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
                        <?php
                        $data = $dataActive;
                        $viewdata['timing'] = $timing;
                        $viewdata["game"] = $game;
                        $viewdata['title'] = $this->util->get_main_chart_title(array("feature"=>"Active Users","game_info"=>$this->_gameInfo), $timing);
                        $viewdata['subTitle'] = $this->util->get_sub_chart_title(array("feature"=>"Active Users", "to"=>$body["toDate"], "from"=>$body["fromDate"], "game_info"=>$this->_gameInfo), $key);
                        $viewdata['kpi'] = "Active User";
                        $viewdata['metric'] = "Active User";
                        $viewdata['unit'] = "user";
                        $viewdata['data'] = $data;
                        $viewdata['colors'] = $colors;
                        $viewdata['id']='active';
                        $viewdata['days'] = $array_date;
                        $viewdata['selectedCountry'] = $selectedCountry;

                        $html = $this->load->view("sdk/report_country/gstack_bar", $viewdata, TRUE);
                        echo $html;
                        ?>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="tab_revenue">
                <div class="col-md-12">

                    <?php if(!isset($dataPu)) {
                        $html = $this->load->view("sdk/body_parts/contact", null, TRUE);
                        echo '<div class="row">' . $html . '</div>';
                    } else {
                        ?>
                        <div id="paying" class="chart-area"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
                        <?php
                        $data = $dataPu;
                        $viewdata['timing'] = $timing;
                        $viewdata["game"] = $game;
                        $viewdata['id'] = "paying";
                        $viewdata['title'] = $this->util->get_main_chart_title(array("feature"=>"Paying User","game_info"=>$this->_gameInfo), $timing);
                        $viewdata['subTitle'] = $this->util->get_sub_chart_title(array("feature"=>"Paying User", "to"=>$body["toDate"], "from"=>$body["fromDate"], "game_info"=>$this->_gameInfo), $key);
                        $viewdata['kpi'] = "Paying User";
                        $viewdata['metric'] = "Paying User";
                        $viewdata['unit'] = "user";
                        $viewdata['data'] = $data;
                        $viewdata['colors'] = $colors;
                        $viewdata['days'] = $array_date;
                        $viewdata['selectedCountry'] = $selectedCountry;
                        $html = $this->load->view("sdk/report_country/gstack_bar", $viewdata, TRUE);
                        echo $html;
                        ?>
                        <div id="rev" class="chart-area"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
                        <?php
                        $data = $dataRev;
                        $viewdata["timing"] = $timing;
                        $viewdata["game"] = $game;
                        $viewdata['title'] = $this->util->get_main_chart_title(array("feature"=>"Revenue","game_info"=>$this->_gameInfo), $timing);
                        $viewdata['subTitle'] = $this->util->get_sub_chart_title(array("feature"=>"Revenue", "to"=>$body["toDate"], "from"=>$body["fromDate"], "game_info"=>$this->_gameInfo), $key);
                        $viewdata['kpi'] = "Revenue";
                        $viewdata['metric'] = "Revenue";
                        $viewdata['unit'] = "VND";
                        $viewdata['data'] = $data;
                        $viewdata['colors'] = $colors;
                        $viewdata['id']='rev';
                        $viewdata['days'] = $array_date;
                        $viewdata['selectedCountry'] = $selectedCountry;

                        $html = $this->load->view("sdk/report_country/gstack_bar", $viewdata, TRUE);
                        echo $html;
                        ?>
                    <?php }?>
                </div>
            </div>
        </div>
    <!-- /.tab-content -->
    <?php
    if(count($table['datatable'])>0){
        $table['timing']=$timing;
        $table['days']=$viewdata['days'];
        $table['selectedCountry']=$viewdata['selectedCountry'];
        $table['title']=$viewdata['title'];
        echo $this->load->view("sdk/body_parts/table/table_country", $table, TRUE);
    }
    ?>
    <div class="clearfix"></div>
</div>