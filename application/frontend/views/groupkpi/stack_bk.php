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
			                <!-- <option value="5" <?php echo((5 == $body['options']) ? 'selected' : '') ?> ><?php echo $body['cbb'];?>7</option>
			                <option value="6" <?php echo((6 == $body['options']) ? 'selected' : '') ?> ><?php echo $body['cbb'];?>30</option> -->
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
			                <h3 class="box-title"><i class="fa fa-th"></i> <?php echo $groupName; ?> List</h3>
			            </div>
						<div class="box-footer">
							<?php if(count($availableGroup) != 0) {?>
							<div class="row">
								<div class="sliding">
								<?php
									$selectedArr = array();
									foreach($selectedGroup as $kpi => $groupArr){
										foreach($groupArr as $id => $name){
											if (!array_key_exists($id, $selectedArr)){
												$selectedArr[$id] = $name;
											}
										}
									}
									foreach($availableGroup as $id => $name){
								?>
								<div class="col-md-2 col-sm-4 col-xs-6">
						            <label title="SID: <?php echo $id?>"> <input type="checkbox" name="selectedGroup[]" value="<?php echo $id ?>"
						            <?php if(array_key_exists($id, $selectedArr)){ echo 'checked="checked"';}?>> <?php echo $name; ?></label>
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
	<?php 
		$total = 0;
		foreach ($line["group"] as $key => $value) {
			$kpiName = $key; 
			if($key == "17") $kpiName = "w";
			if($key == "31") $kpiName = "m";
			if($key == "4") $kpiName = "1";
			if($key == "5") $kpiName = "7";
			if($key == "6") $kpiName = "30";
	?>
	<ul class="nav nav-tabs">
		<li class="active tab-selection"><a href="#tab_active<?php echo $key ?>" data-toggle="tab" data-id="active<?php echo $key ?>">Active</a></li>
		<?php if($body['groupId'] == "server") {?>
			<li class="tab-selection"><a href="#tab_ccu<?php echo $key ?>" data-toggle="tab" data-id="ccu<?php echo $key ?>">CCU</a></li>
		<?php }?>
		<li class="tab-selection"><a href="#tab_accregister<?php echo $key ?>" data-toggle="tab" data-id="accregister<?php echo $key ?>">Account Register</a></li>
		<!-- <li class="tab-selection"><a href="#tab_roleregister<?php echo $key ?>" data-toggle="tab" data-id="roleregister<?php echo $key ?>">Role Register</a></li> -->
		<li class="tab-selection"><a href="#tab_revenue<?php echo $key ?>" data-toggle="tab" data-id="revenue<?php echo $key ?>">Revenue</a></li>
		<li class="tab-selection"><a href="#tab_firstcharge<?php echo $key ?>" data-toggle="tab" data-id="firstcharge<?php echo $key ?>">Firstcharge</a></li>
	</ul>

	<div class="tab-content">

		<div class="tab-pane active" id="tab_active<?php echo $key ?>">
			<div class="row">
				<div class="col-md-12">
					<div id="active<?php echo $key ?>" class="chart-area text-center"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
					<?php 
						$total += 1;
						$data = $line["group"][$key]["a" . $kpiName];
						$dayArr = $line["log_date"];
						
						$viewdata['timing'] = $key;
						$viewdata["game"] = $game;
						$viewdata['id'] = "active" . $key;
						$viewdata['title'] = $this->util->get_main_chart_title(array("feature"=>"Active Users","game_info"=>$this->_gameInfo), $key);
						$viewdata['subTitle'] = $this->util->get_sub_chart_title(array("feature"=>"Active Users", "to"=>$body["toDate"], "from"=>$body["fromDate"], "game_info"=>$this->_gameInfo), $key);
						$viewdata['kpi'] = "Active User";
						$viewdata['metric'] = "Active User";
						$viewdata['unit'] = "user";
						$viewdata['data'] = $data;
						$viewdata['colors'] = $line["colors"];
						
						$viewdata['days'] = $dayArr;
						$viewdata['selectedGroup'] = $selectedGroup["a" . $kpiName];
						
						$html = $this->load->view("body_parts/chart/gstack_bar", $viewdata, TRUE);
						echo $html;
					?>
				</div>								
			</div>
		</div>
		<div class="tab-pane" id="tab_ccu<?php echo $key ?>">
			<div class="col-md-12">
				<?php if(count($line["group"][$key]["acu" . $kpiName]) == 0) {
					$html = $this->load->view("body_parts/contact", null, TRUE);
					echo '<div class="row">' . $html . '</div>';
				} else {
				?>
				<div id="acu<?php echo $key ?>" class="chart-area"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
				<?php 
					$total += 1;
					$data = $line["group"][$key]["acu" . $kpiName];
					$dayArr = $line["log_date"];
					
					$viewdata["timing"] = $key;
					$viewdata["game"] = $game;
					$viewdata['id'] = "acu" . $key;
					$viewdata['title'] = $this->util->get_main_chart_title(array("feature"=>"ACU","game_info"=>$this->_gameInfo), $key);
					$viewdata['subTitle'] = $this->util->get_sub_chart_title(array("feature"=>"ACU", "to"=>$body["toDate"], "from"=>$body["fromDate"], "game_info"=>$this->_gameInfo), $key);
					$viewdata['kpi'] = "ACU";
					$viewdata['metric'] = "ACU";
					$viewdata['unit'] = "user";
					$viewdata["valueDecimals"] = 2;
					$viewdata['data'] = $data;
					$viewdata['colors'] = $line["colors"];
					
					$viewdata['days'] = $dayArr;
					$viewdata['selectedGroup'] = $selectedGroup["acu" . $kpiName];
					
					$html = $this->load->view("body_parts/chart/gstack_bar", $viewdata, TRUE);
					echo $html;
					$viewdata["valueDecimals"] = 0;
				?>
				
				<div id="pcu<?php echo $key ?>" class="chart-area"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
				<?php 
					$total += 1;
					$data = $line["group"][$key]["pcu" . $kpiName];
					$dayArr = $line["log_date"];
					
					$viewdata["timing"] = $key;
					$viewdata["game"] = $game;
					$viewdata['id'] = "pcu" . $key;
					$viewdata['title'] = $this->util->get_main_chart_title(array("feature"=>"PCU","game_info"=>$this->_gameInfo), $key);
					$viewdata['subTitle'] = $this->util->get_sub_chart_title(array("feature"=>"PCU", "to"=>$body["toDate"], "from"=>$body["fromDate"], "game_info"=>$this->_gameInfo), $key);
					$viewdata['kpi'] = "PCU";
					$viewdata['metric'] = "PCU";
					$viewdata['unit'] = "user";
					$viewdata['data'] = $data;
					$viewdata['colors'] = $line["colors"];
					
					$viewdata['days'] = $dayArr;
					$viewdata['selectedGroup'] = $selectedGroup["pcu" . $kpiName];
					$html = $this->load->view("body_parts/chart/gstack_bar", $viewdata, TRUE);
					echo $html;
				?>
				<?php }?>
			</div>
		</div>
		<div class="tab-pane" id="tab_accregister<?php echo $key ?>">
			<div class="col-md-12">
				<div id="accregister<?php echo $key ?>" class="chart-area"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
				<?php 
					$total += 1;
					$data = $line["group"][$key]["n" . $kpiName];
					$dayArr = $line["log_date"];
					
					$viewdata["timing"] = $key;
					$viewdata["game"] = $game;
					$viewdata['id'] = "accregister" . $key;
					$viewdata['title'] = $this->util->get_main_chart_title(array("feature"=>"Acccount Register","game_info"=>$this->_gameInfo), $key);
					$viewdata['subTitle'] = $this->util->get_sub_chart_title(array("feature"=>"Account Register", "to"=>$body["toDate"], "from"=>$body["fromDate"], "game_info"=>$this->_gameInfo), $key);
					$viewdata['kpi'] = "Account Register";
					$viewdata['metric'] = "Account Register";
					$viewdata['unit'] = "user";
					$viewdata['data'] = $data;
					$viewdata['colors'] = $line["colors"];
					
					$viewdata['days'] = $dayArr;
					$viewdata['selectedGroup'] = $selectedGroup["n" . $kpiName];
					
					$html = $this->load->view("body_parts/chart/gstack_bar", $viewdata, TRUE);
					echo $html;
				?>
			</div>
		</div>
		
		<div class="tab-pane" id="tab_revenue<?php echo $key ?>">
			<div class="col-md-12">
				
				<?php if(count($line["group"][$key]["pu" . $kpiName]) == 0) {
					$html = $this->load->view("body_parts/contact", null, TRUE);
					echo '<div class="row">' . $html . '</div>';
				} else {
				?>
				<div id="paying<?php echo $key ?>" class="chart-area"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
				<?php 
					$total += 1;
					$data = $line["group"][$key]["pu" . $kpiName];
					$dayArr = $line["log_date"];
					
					$viewdata["timing"] = $key;
					$viewdata["game"] = $game;
					$viewdata['id'] = "paying" . $key;
					$viewdata['title'] = $this->util->get_main_chart_title(array("feature"=>"Paying User","game_info"=>$this->_gameInfo), $key);
					$viewdata['subTitle'] = $this->util->get_sub_chart_title(array("feature"=>"Paying User", "to"=>$body["toDate"], "from"=>$body["fromDate"], "game_info"=>$this->_gameInfo), $key);
					$viewdata['kpi'] = "Paying User";
					$viewdata['metric'] = "Paying User";
					$viewdata['unit'] = "user";
					$viewdata['data'] = $data;
					$viewdata['colors'] = $line["colors"];
					
					$viewdata['days'] = $dayArr;
					$viewdata['selectedGroup'] = $selectedGroup["pu" . $kpiName];
					
					$html = $this->load->view("body_parts/chart/gstack_bar", $viewdata, TRUE);
					echo $html;
				?>
				<div id="gr<?php echo $key ?>" class="chart-area"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
				<?php 
					$total += 1;
					$data = $line["group"][$key]["gr" . $kpiName];
					$dayArr = $line["log_date"];
					
					$viewdata["timing"] = $key;
					$viewdata["game"] = $game;
					$viewdata['id'] = "gr" . $key;
					$viewdata['title'] = $this->util->get_main_chart_title(array("feature"=>"Revenue","game_info"=>$this->_gameInfo), $key);
					$viewdata['subTitle'] = $this->util->get_sub_chart_title(array("feature"=>"Revenue", "to"=>$body["toDate"], "from"=>$body["fromDate"], "game_info"=>$this->_gameInfo), $key);
					$viewdata['kpi'] = "Revenue";
					$viewdata['metric'] = "Revenue";
					$viewdata['unit'] = "VND";
					$viewdata['data'] = $data;
					$viewdata['colors'] = $line["colors"];
					
					$viewdata['days'] = $dayArr;
					$viewdata['selectedGroup'] = $selectedGroup["gr" . $kpiName];
					
					$html = $this->load->view("body_parts/chart/gstack_bar", $viewdata, TRUE);
					echo $html;
				?>
				<?php }?>
			</div>
		</div>
		<div class="tab-pane" id="tab_firstcharge<?php echo $key ?>">
			<div class="col-md-12">
				<div id="firstcharge<?php echo $key ?>" class="chart-area"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
				<?php 
					$total += 1;
					$data = $line["group"][$key]["npu" . $kpiName];
					$dayArr = $line["log_date"];
					
					$viewdata["timing"] = $key;
					$viewdata["game"] = $game;
					$viewdata['id'] = "firstcharge" . $key;
					$viewdata['title'] = $this->util->get_main_chart_title(array("feature"=>"Firstcharge User","game_info"=>$this->_gameInfo), $key);
					$viewdata['subTitle'] = $this->util->get_sub_chart_title(array("feature"=>"Firstcharge User", "to"=>$body["toDate"], "from"=>$body["fromDate"], "game_info"=>$this->_gameInfo), $key);
					$viewdata['kpi'] = "Firstcharge User";
					$viewdata['metric'] = "Firstcharge User";
					$viewdata['unit'] = "user";
					$viewdata['data'] = $data;
					$viewdata['colors'] = $line["colors"];
					
					$viewdata['days'] = $dayArr;
					$viewdata['selectedGroup'] = $selectedGroup["npu" . $kpiName];
					
					$html = $this->load->view("body_parts/chart/gstack_bar", $viewdata, TRUE);
					echo $html;
				?>
				<div id="firstcharge_gr<?php echo $key ?>" class="chart-area"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
				<?php 
					$total += 1;
					$data = $line["group"][$key]["npu_gr" . $kpiName];
					$dayArr = $line["log_date"];
					
					$viewdata["timing"] = $key;
					$viewdata["game"] = $game;
					$viewdata['id'] = "firstcharge_gr" . $key;
					$viewdata['title'] = $this->util->get_main_chart_title(array("feature"=>"Firstcharge Revenue","game_info"=>$this->_gameInfo), $key);
					$viewdata['subTitle'] = $this->util->get_sub_chart_title(array("feature"=>"Firstcharge Revenue", "to"=>$body["toDate"], "from"=>$body["fromDate"], "game_info"=>$this->_gameInfo), $key);
					$viewdata['kpi'] = "Firstcharge Revenue";
					$viewdata['metric'] = "Firstcharge Revenue";
					$viewdata['unit'] = "VND";
					$viewdata['data'] = $data;
					$viewdata['colors'] = $line["colors"];
					
					$viewdata['days'] = $dayArr;
					$viewdata['selectedGroup'] = $selectedGroup["npu_gr" . $kpiName];
					
					$html = $this->load->view("body_parts/chart/gstack_bar", $viewdata, TRUE);
					echo $html;
				?>
			</div>
		</div>
	</div>
	<?php }?>
	<!-- /.tab-content -->
	<?php 
		$html = $this->load->view("body_parts/table/group_table", $table, TRUE);
		echo $html;
	?>
	<div class="clearfix"></div>
</div>