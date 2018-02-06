<div class="box box-primary">
    <?php 
    	$html = $this->load->view('body_parts/selection/kpi', $viewdata, TRUE);
    	echo $html;
    ?>
</div>
<div class="box box-primary">
    <div class="row">
		<div class="col-md-12">
			<div class="box box-solid">
				<div class="box-header with-border">
	                <h3 class="box-title"><i class="fa fa-th"></i> Channel List</h3>
	            </div>
				<div class="box-footer">
					<div class="row">
						<form name="form" action="" method="post">
						
							<?php
								$selectedArr = array();
								foreach($selectedGroup as $kpi => $groupArr){
									foreach($groupArr as $group){
										if (!in_array($group, $selectedArr)){
											$selectedArr[] = $group;
										}
									}
								}
								foreach($availableGroup as $group){
							?>
							<div class="col-md-1">
					            <label><input type="checkbox" name="selectedChannel[]" value="<?php echo $group ?>" 
					            <?php if(in_array($group, $selectedArr)){ echo 'checked';}?>> <?php if($group == "" ) {echo "Empty";} else { echo $group; }?></label>
							</div>
							<?php 
								}
							?>
							<div class="col-md-12">
								<button id="checkAll" class="btn btn-primary btn-sm">Check All</button>
								<button id="clearAllCheckBoxes" class="btn btn-primary btn-sm">Clear All</button>
								<input type="submit" class="btn btn-primary btn-sm" value="Submit">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
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
	
		<li class="active"><a href="#tab_active<?php echo $key ?>" data-toggle="tab" data-id="active<?php echo $key ?>">Active</a></li>
		<!-- <li><a href="#tab_ccu<?php echo $key ?>" data-toggle="tab" data-id="ccu<?php echo $key ?>">CCU</a></li> -->
		<li><a href="#tab_accregister<?php echo $key ?>" data-toggle="tab" data-id="accregister<?php echo $key ?>">Account Register</a></li>
		<!-- <li><a href="#tab_roleregister<?php echo $key ?>" data-toggle="tab" data-id="roleregister<?php echo $key ?>">Role Register</a></li> -->
		<li><a href="#tab_revenue<?php echo $key ?>" data-toggle="tab" data-id="revenue<?php echo $key ?>">Revenue</a></li>
		<li><a href="#tab_firstcharge<?php echo $key ?>" data-toggle="tab" data-id="firstcharge<?php echo $key ?>">Firstcharge</a></li>
		
	</ul>

	<div class="tab-content">

		<div class="tab-pane active" id="tab_active<?php echo $key ?>">
			<div class="row">
				<div class="col-md-12">
					<div id="active<?php echo $key ?>"></div>
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
						
						$viewdata['days'] = $dayArr;
						$viewdata['selectedGroup'] = $selectedGroup["a" . $kpiName];
						
						$html = $this->load->view("body_parts/chart/line", $viewdata, TRUE);
						echo $html;
					?>
				</div>								
			</div>
		</div>
		<div class="tab-pane" id="tab_ccu<?php echo $key ?>">
			<div class="col-md-12">
				<div id="acu<?php echo $key ?>"></div>
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
					
					$viewdata['days'] = $dayArr;
					$viewdata['selectedGroup'] = $selectedGroup["acu" . $kpiName];
					
					$html = $this->load->view("body_parts/chart/line", $viewdata, TRUE);
					echo $html;
					$viewdata["valueDecimals"] = 0;
				?>
				
				<div id="pcu<?php echo $key ?>"></div>
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
					
					$viewdata['days'] = $dayArr;
					$viewdata['selectedGroup'] = $selectedGroup["pcu" . $kpiName];
					$html = $this->load->view("body_parts/chart/line", $viewdata, TRUE);
					echo $html;
				?>
			</div>
		</div>
		<div class="tab-pane" id="tab_accregister<?php echo $key ?>">
			<div class="col-md-12">
				<div id="accregister<?php echo $key ?>"></div>
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
					
					$viewdata['days'] = $dayArr;
					$viewdata['selectedGroup'] = $selectedGroup["n" . $kpiName];
					
					$html = $this->load->view("body_parts/chart/line", $viewdata, TRUE);
					echo $html;
				?>
			</div>
		</div>
		<div class="tab-pane" id="tab_roleregister<?php echo $key ?>">
			<div class="col-md-12">
				<div id="roleregister<?php echo $key ?>"></div>
				<?php 
					
					$data = $line["group"][$key]["nr" . $kpiName];
					if(count($data) > 0){
						
						$total += 1;
						
						$dayArr = $line["log_date"];
						
						$viewdata["timing"] = $key;
						$viewdata["game"] = $game;
						$viewdata['id'] = "roleregister" . $key;
						$viewdata['title'] = $this->util->get_main_chart_title(array("feature"=>"Role Register","game_info"=>$this->_gameInfo), $key);
						$viewdata['subTitle'] = $this->util->get_sub_chart_title(array("feature"=>"Role Register", "to"=>$body["toDate"], "from"=>$body["fromDate"], "game_info"=>$this->_gameInfo), $key);
						$viewdata['kpi'] = "Role Register";
						$viewdata['metric'] = "Role Register";
						$viewdata['unit'] = "role";
						$viewdata['data'] = $data;
						
						$viewdata['days'] = $dayArr;
						$viewdata['selectedGroup'] = $selectedGroup["nr" . $kpiName];
						
						$html = $this->load->view("body_parts/chart/line", $viewdata, TRUE);
						echo $html;
					}else{
						$html = $this->load->view("body_parts/contact", null, TRUE);
						echo '<div class="row">' . $html . '</div>';
					}
					
				?>
			</div>
		</div>
		<div class="tab-pane" id="tab_revenue<?php echo $key ?>">
			<div class="col-md-12">
				<div id="paying<?php echo $key ?>"></div>
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
					
					$viewdata['days'] = $dayArr;
					$viewdata['selectedGroup'] = $selectedGroup["pu" . $kpiName];
					
					$html = $this->load->view("body_parts/chart/line", $viewdata, TRUE);
					echo $html;
				?>
				<div id="gr<?php echo $key ?>"></div>
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
					
					$viewdata['days'] = $dayArr;
					$viewdata['selectedGroup'] = $selectedGroup["gr" . $kpiName];
					
					$html = $this->load->view("body_parts/chart/line", $viewdata, TRUE);
					echo $html;
				?>
			</div>
		</div>
		<div class="tab-pane" id="tab_firstcharge<?php echo $key ?>">
			<div class="col-md-12">
				<div id="firstcharge<?php echo $key ?>"></div>
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
					
					$viewdata['days'] = $dayArr;
					$viewdata['selectedGroup'] = $selectedGroup["npu" . $kpiName];
					
					$html = $this->load->view("body_parts/chart/line", $viewdata, TRUE);
					echo $html;
				?>
				<div id="firstcharge_gr<?php echo $key ?>"></div>
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
					
					$viewdata['days'] = $dayArr;
					$viewdata['selectedGroup'] = $selectedGroup["npu_gr" . $kpiName];
					
					$html = $this->load->view("body_parts/chart/line", $viewdata, TRUE);
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