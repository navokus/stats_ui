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
							<?php 
								if(count($groupInfo) > 0){
									echo '<h3 class="box-title"><i class="fa fa-th"></i> ' . $groupInfo["name"] . '</h3>';
								} else {
									echo '<h3 class="box-title"><i class="fa fa-th"></i> ' . $groupName . ' List</h3>';
								}
							?>
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
									ksort($availableGroup, SORT_STRING);
									foreach($availableGroup as $id => $name){
								?>
								<div class="col-md-2 col-sm-4 col-xs-6">
						            <label title="ID: <?php echo $id?>"> <input type="checkbox" name="selectedGroup[]" value="<?php echo $id ?>"
						            <?php if(array_key_exists($id, $selectedArr)){ echo 'checked="checked"';}?>>
						            	<?php 
						            		if(count($groupInfo) > 0 && $id != "other"){
						            			echo $groupInfo["prefix"] . " " . $name;
						            		} else {
						            			echo $name;
						            		}
						            	?>
						            </label>
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
									<button id="groupDownload" class="btn btn-primary btn-sm hidden">Download</button>
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
<div class="nav-tabs-custom" id="chart-data" data-group="<?php echo $body['groupId'];?>" data-timing="<?php echo $timing;?>" >
	<?php 
		$total = 0;
		foreach ($line["group"] as $key => $value) {
			$kpiTiming = $key; 
			if($key == "17") $kpiTiming = "w";
			if($key == "31") $kpiTiming = "m";
			if($key == "4") $kpiTiming = "1";
			if($key == "5") $kpiTiming = "7";
			if($key == "6") $kpiTiming = "30";
	?>
	<ul class="nav nav-tabs">
		<li class="active tab-selection"><a href="#tab_active<?php echo $kpiTiming ?>" data-toggle="tab" data-id="active<?php echo $kpiTiming ?>">Active</a></li>
		<?php if($body['groupId'] == "server") {?>
			<li class="tab-selection"><a href="#tab_ccu<?php echo $kpiTiming ?>" data-toggle="tab" data-id="ccu<?php echo $kpiTiming ?>">CCU</a></li>
		<?php }?>
		<li class="tab-selection"><a href="#tab_accregister<?php echo $kpiTiming ?>" data-toggle="tab" data-id="accregister<?php echo $kpiTiming ?>">Account Register</a></li>
		<!-- <li class="tab-selection"><a href="#tab_roleregister<?php echo $kpiTiming ?>" data-toggle="tab" data-id="roleregister<?php echo $kpiTiming ?>">Role Register</a></li> -->
		<li class="tab-selection"><a href="#tab_revenue<?php echo $kpiTiming ?>" data-toggle="tab" data-id="revenue<?php echo $kpiTiming ?>">Revenue</a></li>
		<li class="tab-selection"><a href="#tab_firstcharge<?php echo $kpiTiming ?>" data-toggle="tab" data-id="firstcharge<?php echo $kpiTiming ?>">Firstcharge</a></li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="tab_active<?php echo $kpiTiming ?>">
			<div class="row">
				<div class="col-md-12">
					<div id="a<?php echo $kpiTiming ?>" class="chart-area text-center" data-name="a<?php echo $kpiTiming ?>" data-is-render="false"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
				</div>								
			</div>
		</div>
		<div class="tab-pane" id="tab_ccu<?php echo $kpiTiming ?>">
			<div class="col-md-12">
				
				<div id="acu<?php echo $kpiTiming ?>" class="chart-area" data-name="acu<?php echo $kpiTiming ?>" data-is-render="false"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
				<div id="pcu<?php echo $kpiTiming ?>" class="chart-area" data-name="pcu<?php echo $kpiTiming ?>" data-is-render="false"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
			</div>
		</div>
		<div class="tab-pane" id="tab_accregister<?php echo $kpiTiming ?>">
			<div class="col-md-12">
				<div id="n<?php echo $kpiTiming ?>" class="chart-area" data-name="n<?php echo $kpiTiming ?>" data-is-render="false"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
			</div>
		</div>
		<div class="tab-pane" id="tab_revenue<?php echo $kpiTiming ?>">
			<div class="col-md-12">
				<div id="pu<?php echo $kpiTiming ?>" class="chart-area" data-name="pu<?php echo $kpiTiming ?>" data-is-render="false"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
				<div id="gr<?php echo $kpiTiming ?>" class="chart-area" data-name="gr<?php echo $kpiTiming ?>" data-is-render="false"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
			</div>
		</div>
		<div class="tab-pane" id="tab_firstcharge<?php echo $kpiTiming ?>">
			<div class="col-md-12">
				<div id="npu<?php echo $kpiTiming ?>" class="chart-area" data-name="npu<?php echo $kpiTiming ?>" data-is-render="false"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
				<div id="npu_gr<?php echo $kpiTiming ?>" class="chart-area" data-name="npu_gr<?php echo $kpiTiming ?>" data-is-render="false"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
			</div>
		</div>
	</div>
	<?php }?>
	<!-- /.tab-content -->
	<div id="group-table"  class="text-center">
		
	</div>
	<div class="clearfix"></div>
</div>