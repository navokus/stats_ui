<?php 
	if($_SESSION['message']) {
?>
	<div class="col-md-6 col-md-offset-3">
	<?php echo '<blockquote><p class="text-red">'.$_SESSION['message'].'</p></blockquote>'; ?>
	</div>
<?php
		unset($_SESSION['message']);
	}
?>


<div class="box box-solid bg-green-gradient">
	<div class="box-header">
		<h3 class="box-title">THỐNG KÊ HÀNH VI</h3>

	</div><!-- /.box-header -->
	<form name="paying" action="<?php echo site_url('Behavior/statistic/') ?>" method="post">
	<div class="box-footer text-black" style="display: block;">
			<input type="hidden" name="game" value="<?php echo $this->session->userdata('default_game') ?>">
			<div class="col-md-3">
				<select class="form-control" name="options">
					<option value="">Chọn thời gian</option>
					<option value="3" <?php echo ((3 == $post['options']) ? 'selected' : '') ?> >Tháng này</option>
					<option value="2" <?php echo ((2 == $post['options']) ? 'selected' : '') ?> >Tuần này</option>
					<option value="1" <?php echo ((1 == $post['options']) ? 'selected' : '') ?> >Hôm nay</option>
					<option value="6" <?php echo ((6 == $post['options']) ? 'selected' : '') ?> >Tùy chọn Tháng</option>
					<option value="5" <?php echo ((5 == $post['options']) ? 'selected' : '') ?> >Tùy chọn Tuần</option>
					<option value="4" <?php echo ((4 == $post['options']) ? 'selected' : '') ?> >Tùy chọn Ngày</option>
				</select>
			</div><!-- /.col -->

			<div class="option_time option_disable hide">
				<div class="col-md-4">
					<input autocomplete="off" class="form-control" type="text" disabled >
				</div><!-- /.col -->
				
			</div>

			<div class="option_time option_day hide">
				<div class="col-md-4">
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<input autocomplete="off" class="form-control pull-right" type="text" id="dpd1" name="day[1]" value="<?php echo $post['day']['1'] ?>" >
                    </div>
				</div><!-- /.col -->
				
			</div>

			<div class="option_time option_week hide">
				<div class="col-md-4">
					<select class="form-control" id="wpw1" name="week[1]">
						<?php 
							foreach ($optionsWeek as $key => $value) {

								if ($post['week']['1'] == $key) {
									$selected = ' selected ';
								} else {
									$selected = '';
								}
								echo "<option value='". $key ."' ". $selected .">". $value ."</option>";
							}
						?>
					</select>
				</div><!-- /.col -->
				
			</div>

			<div class="option_time option_month ">
				<div class="col-md-4">
					<select class="form-control" id="mpm1" name="month[1]">												
						<?php 
							foreach ($optionsMonth as $key => $value) {

								if ($post['month'][1] == $key) {
									$selected = ' selected ';
								} else {
									$selected = '';
								}

								echo "<option value='". $key ."' ". $selected .">". $value ."</option>";
							}
						?>
					</select>
				</div><!-- /.col -->
				
			</div>


			<div class="col-md-1">
			  <button type="submit" class="btn btn-danger">Thống kê</button>
			</div><!-- /.col -->
		
	</div>
	</form>
</div>


<?php if($content) : ?>
<div class="row">
	<div class="col-md-12">
	<!-- Custom Tabs -->
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<?php  
					$aBehavior = array_reverse($_SESSION['behaviorStatistic']);
					$i = 1;
					foreach ($aBehavior as $key => $value) {
						list($gameCode, $times, $curDate) = explode('_', $value['header']);
						if($this->uri->segment(3) == $key) {
							$active = ' active ';
						} else if(!$this->uri->segment(3) && $i == 1) {
							$active = ' active ';
						} else {
							$active = '';
						}
						echo '
						<li class="'.$active.'">
							<a href="'. site_url('Behavior/statistic/' . $key) . '" >['.strtoupper($gameCode) .'] '. $times . ' ' . $curDate .'</a>
							<button onclick="window.location.href=\''.site_url('behavior_module/Statistic/delCacheStatistic/' . $key).'\'" class="btn btn-box-tool" data-toggle="tooltip" title="Remove" style="position:absolute; top:-5px; right:0px"><i class="fa fa-times"></i></button>
						</li>';
						
						$i++;

						if($i == 5) break;
					}
				?>


			</ul>

			
			<div class="tab-content">
			  <div class="tab-pane active">

				<!-- content here -->
				<div class="row" id="content">
					<?php echo $content; ?>
				</div>
			  </div><!-- /.tab-pane -->
			</div><!-- /.tab-content -->
			
		</div><!-- nav-tabs-custom -->
	</div><!-- /.col -->
</div>
<?php endif ?>
				                 
