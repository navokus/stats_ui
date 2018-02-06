<?php 
/**
 * @author vinhdp
 */
?>
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
	                    	<button type="submit" class="btn btn-danger">View</button>
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
				            	<button type="submit" class="btn btn-danger">View</button>
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
				            	<button type="submit" class="btn btn-danger">View</button>
				        	</span>
				        </div>
		            </div>
		        </div>
		    </div>
	    </div>
	</form>
</div>