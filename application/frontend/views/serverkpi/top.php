
<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_t1" data-toggle="tab" data-id="1">Daily</a></li>
		<li><a href="#tab_t7" data-toggle="tab" data-id="7">Last 7 Days</a></li>
		<li><a href="#tab_t30" data-toggle="tab" data-id="30">Last 30 Days</a></li>
	</ul>

	<div class="tab-content">
		<?php 
			$total = 0;
			foreach ($bar["server"] as $key => $value) {
		?>
		<div class="tab-pane <?php if($key == 1) echo "active"?>" id="tab_t<?php echo $key?>">

			<div class="row">
				<div class="col-md-12">
					<div class="box box-solid">
						<div class="box-header with-border">
			                <h3 class="box-title"><i class="fa fa-font-awesome"></i> Top Server's Reports</h3>
			            </div>
						<div class="box-footer">
							<div class="row">
								<!-- Nav tabs -->
								<div class="card">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#active<?php echo $key?>" aria-controls="active<?php echo $key?>" role="tab" data-toggle="tab">Active</a></li>
                                        <li role="presentation"><a href="#acc-register<?php echo $key?>" aria-controls="acc-register<?php echo $key?>" role="tab" data-toggle="tab">Account Register</a></li>
                                        <li role="presentation"><a href="#role-register<?php echo $key?>" aria-controls="role-register<?php echo $key?>" role="tab" data-toggle="tab">Role Register</a></li>
                                        <li role="presentation"><a href="#payment<?php echo $key?>" aria-controls="payment<?php echo $key?>" role="tab" data-toggle="tab">Revenue</a></li>
                                        <li role="presentation"><a href="#firstcharge<?php echo $key?>" aria-controls="firstcharge<?php echo $key?>" role="tab" data-toggle="tab">First Charge</a></li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="active<?php echo $key?>">
											<?php 
												$total += 1;
												$data = $bar["server"][$key]["a" . $key];
												$viewData["game"] = $game;
												$viewdata['id'] = "a" . $key;
												$viewdata['title'] = "Server\'s Active User";
												$viewdata['subTitle'] = "Date: " . $reportDate . " (source: " . $game["data_source"] . ")";
												$viewdata['kpi'] = "Active User";
												$viewdata['metric'] = "Active User";
												$viewdata['unit'] = "users";
												$viewdata['data'] = $data;
												$html = $this->load->view("body_parts/chart/barv2", $viewdata, TRUE);
												echo $html;
											?>
										</div>
                                        <div role="tabpanel" class="tab-pane" id="acc-register<?php echo $key?>">
                                        	<?php 
	                                        	if($bar["server"][$key]["n" . $key] != null) {
													$total += 1;
													$data = $bar["server"][$key]["n" . $key];
													$viewdata['id'] = "n" . $key;
													$viewdata['title'] = "Server\'s New Account Register";
													$viewdata['subTitle'] = "Date: " . $reportDate . " (source: " . $game["data_source"] . ")";
													$viewdata['kpi'] = "New Register";
													$viewdata['metric'] = "New Register";
													$viewdata['unit'] = "users";
													$viewdata['data'] = $data;
													$html = $this->load->view("body_parts/chart/barv2", $viewdata, TRUE);
													echo $html;
	                                        	} else {
	                                        		$html = $this->load->view("body_parts/contact", null, TRUE);
	                                        		echo $html;
	                                        	}
											?>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="role-register<?php echo $key?>">
                                        	<?php 
	                                        	if($bar["server"][$key]["nr" . $key] != null) {
													$total += 1;
													$data = $bar["server"][$key]["nr" . $key];
													$viewdata['id'] = "nr" . $key;
													$viewdata['title'] = "Server\'s New Role Register";
													$viewdata['subTitle'] = "Date: " . $reportDate . " (source: " . $game["data_source"] . ")";
													$viewdata['kpi'] = "New Role Register";
													$viewdata['metric'] = "New Role Register";
													$viewdata['unit'] = "roles";
													$viewdata['data'] = $data;
													$html = $this->load->view("body_parts/chart/barv2", $viewdata, TRUE);
													echo $html;
	                                        	} else {
	                                        		$html = $this->load->view("body_parts/contact", null, TRUE);
	                                        		echo $html;
	                                        	}
											?>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="payment<?php echo $key?>">
                                        	<?php 
                                        		$total += 1;
                                        		$userData["name"] = "Paying Users";
                                        		$userData["title"] = "Server\'s Paying Users";
                                        		$userData['subTitle'] = "Date: " . $reportDate . " (source: " . $game["data_source"] . ")";
                                        		$userData["data"] = $bar["server"][$key]["pu" . $key];
                                        		$userData["unit"] = "users";
                                        		$userData["type"] = "column";
                                        		$userData["valueDecimals"] = "1";
                                        		
                                        		$revenueData["name"] = "Revenue";
                                        		$revenueData["title"] = "Server\'s Revenue";
                                        		$revenueData['subTitle'] = "Date: " . $reportDate . " (source: " . $game["data_source"] . ")";
                                        		$revenueData["data"] = $bar["server"][$key]["gr" . $key];
                                        		$revenueData["unit"] = "VND";
                                        		$revenueData["type"] = "column";
                                        		$revenueData["valueDecimals"] = "1";

                                        		$datasets["revenue"] = $revenueData;
                                        		$datasets["user"] = $userData;
                                        		
                                        		$viewdata['id'] = "synchpu" . $key;
                                        		$viewdata['datasets'] = $datasets;
	                                        	$html = $this->load->view("body_parts/chart/server_synchronized", $viewdata, TRUE);
												echo $html;
											?>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="firstcharge<?php echo $key?>">
                                        	<?php 
	                                        	if($bar["server"][$key]["npu" . $key] != null) {
	                                        		$total += 1;
	                                        		$userData["name"] = "New Paying Users";
	                                        		$userData["title"] = "Server\'s New Paying Users";
	                                        		$userData['subTitle'] = "Date: " . $reportDate . " (source: " . $game["data_source"] . ")";
	                                        		$userData["data"] = $bar["server"][$key]["npu" . $key];
	                                        		$userData["unit"] = "users";
	                                        		$userData["type"] = "column";
	                                        		$userData["valueDecimals"] = "1";
	                                        		
	                                        		$revenueData["name"] = "New Paying Revenue";
	                                        		$revenueData["title"] = "Server\'s New Paying Revenue";
	                                        		$revenueData['subTitle'] = "Date: " . $reportDate . " (source: " . $game["data_source"] . ")";
	                                        		$revenueData["data"] = $bar["server"][$key]["npu_gr" . $key];
	                                        		$revenueData["unit"] = "VND";
	                                        		$revenueData["type"] = "column";
	                                        		$revenueData["valueDecimals"] = "1";
	
	                                        		$datasets["revenue"] = $revenueData;
	                                        		$datasets["user"] = $userData;
	                                        		
	                                        		$viewdata['id'] = "synchnpu" . $key;
	                                        		$viewdata['datasets'] = $datasets;
		                                        	$html = $this->load->view("body_parts/chart/server_synchronized", $viewdata, TRUE);
													echo $html;
												} else {
													$html = $this->load->view("body_parts/contact", null, TRUE);
													echo $html;
												}
											?>
										</div>
                                    </div>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php }?>
	</div>
	<!-- /.tab-content -->
	<?php if($total == 0){
		$html = $this->load->view("body_parts/contact", null, TRUE);
		echo $html;	
	} else {
		
		$html = $this->load->view("body_parts/table/reverse_table", $table, TRUE);
		echo $html;
	}?>
	<div class="clearfix"></div>
</div>