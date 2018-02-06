<?php ?>
<script type="text/javascript">
Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
    return {
        radialGradient: {
            cx: 0.5,
            cy: 0.3,
            r: 0.7
        },
        stops: [
            [0, color],
            [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
        ]
    };
});
</script>
<div class="nav-tabs-custom">
	<ul class="nav nav-tabs" id="channel">
		<li class="active"><a href="#tab_t1" data-toggle="tab" data-id="1">Daily</a></li>
		<li><a href="#tab_t7" data-toggle="tab" data-id="7">Last 7 Days</a></li>
		<li><a href="#tab_t30" data-toggle="tab" data-id="30">Last 30 Days</a></li>
	</ul>

	<div class="tab-content">
	
	<?php 
		$total = 0;
		$colors = $pie["colors"];
		foreach ($pie["channel"] as $key => $value) {
	?>
		<div class="tab-pane <?php if($key == 1) echo "active"?>" id="tab_t<?php echo $key?>">
			<section id="section-package">
				<div class="row">
					<?php if($pie["channel"][$key]["a" . $key] != null) {?>
					<div class="col-md-4">
						<div class="box box-warning">
							<!--<div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-money" name="revenue"></i> Revenue</h3>
                                </div>-->
							<div class="box-footer">
								<div class="row">
									<div class="col-md-12">
										<div id="container_a<?php echo $key?>"></div>
									</div>
								</div>
								<?php 
								$total += 1;
								$data = $pie["channel"][$key]["a" . $key];
								$viewdata['id'] = "container_a" . $key;
								$viewdata['title'] = "Active User By Login Channel";
								$viewdata['subTitle'] = "";
								$viewdata['data'] = $data;
								$viewdata['colors'] = $colors;
								$html = $this->load->view("body_parts/chart/pie_label", $viewdata, TRUE);
								echo $html;
								
								?>
							</div>
						</div>
						<!-- /.box -->
					</div>
					<?php }?>
					<?php if($pie["channel"][$key]["pu" . $key] != null) {?>
					<div class="col-md-4">
						<div class="box box-warning">
							<!--<div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-money" name="revenue"></i> Revenue</h3>
                                </div>-->
							<div class="box-footer">
								<div class="row">
									<div class="col-md-12">
										<div id="container_pu<?php echo $key?>"></div>
									</div>
								</div>
								<?php 
								$total += 1;
								$data = $pie["channel"][$key]["pu" . $key];
								$viewdata['id'] = "container_pu" . $key;
								$viewdata['title'] = "Paying User By Login Channel";
								$viewdata['subTitle'] = "";
								$viewdata['data'] = $data;
								$viewdata['colors'] = $colors;
								$html = $this->load->view("body_parts/chart/pie_label", $viewdata, TRUE);
								echo $html;
								
								?>
							</div>
						</div>
						<!-- /.box -->
					</div>
					<?php }?>
					<?php if($pie["channel"][$key]["gr" . $key] != null) {?>
					<div class="col-md-4">
						<div class="box box-warning">
							<!--<div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-money" name="revenue"></i> Revenue</h3>
                                </div>-->
							<div class="box-footer">
								<div class="row">
									<div class="col-md-12">
										<div id="container_gr<?php echo $key?>"></div>
									</div>
								</div>
								<?php 
								$total += 1;
								$data = $pie["channel"][$key]["gr" . $key];
								$viewdata['id'] = "container_gr" . $key;
								$viewdata['title'] = "Revenue By Login Channel";
								$viewdata['subTitle'] = "";
								$viewdata['data'] = $data;
								$viewdata['colors'] = $colors;
								$html = $this->load->view("body_parts/chart/pie_label", $viewdata, TRUE);
								echo $html;
								
								?>
							</div>
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
					<?php }?>
					<?php if($pie["channel"][$key]["n" . $key] != null) {?>
					<div class="col-md-4">
						<div class="box box-warning">
							<!--<div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-money" name="revenue"></i> Revenue</h3>
                                </div>-->
							<div class="box-footer">
								<div class="row">
									<div class="col-md-12">
										<div id="container_n<?php echo $key?>"></div>
									</div>
								</div>
								<?php 
								$total += 1;
								$data = $pie["channel"][$key]["n" . $key];
								$viewdata['id'] = "container_n" . $key;
								$viewdata['title'] = "New User By Login Channel";
								$viewdata['subTitle'] = "";
								$viewdata['data'] = $data;
								$viewdata['colors'] = $colors;
								$html = $this->load->view("body_parts/chart/pie_label", $viewdata, TRUE);
								echo $html;
								
								?>
							</div>
						</div>
						<!--ox bo /.box -->
					</div>
					<?php }?>
					<?php if($pie["channel"][$key]["npu" . $key] != null) {?>
					<div class="col-md-4">
						<div class="box box-warning">
							<!--<div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-money" name="revenue"></i> Revenue</h3>
                                </div>-->
							<div class="box-footer">
								<div class="row">
									<div class="col-md-12">
										<div id="container_npu<?php echo $key?>"></div>
									</div>
								</div>
								<?php 
								$total += 1;
								$data = $pie["channel"][$key]["npu" . $key];
								$viewdata['id'] = "container_npu" . $key;
								$viewdata['title'] = "First Charge User By Login Channel";
								$viewdata['subTitle'] = "";
								$viewdata['data'] = $data;
								$viewdata['colors'] = $colors;
								$html = $this->load->view("body_parts/chart/pie_label", $viewdata, TRUE);
								echo $html;
								
								?>
							</div>
						</div>
						<!-- /.box -->
					</div>
					<?php }?>
					<?php if($pie["channel"][$key]["npu_gr" . $key] != null) {?>
					<div class="col-md-4">
						<div class="box box-warning">
							<!--<div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-money" name="revenue"></i> Revenue</h3>
                                </div>-->
							<div class="box-footer">
								<div class="row">
									<div class="col-md-12">
										<div id="container_npu_gr<?php echo $key?>"></div>
									</div>
								</div>
								<?php 
								$total += 1;
								$data = $pie["channel"][$key]["npu_gr" . $key];
								$viewdata['id'] = "container_npu_gr" . $key;
								$viewdata['title'] = "First Charge Revenue By Login Channel";
								$viewdata['subTitle'] = "";
								$viewdata['data'] = $data;
								$viewdata['colors'] = $colors;
								$html = $this->load->view("body_parts/chart/pie_label", $viewdata, TRUE);
								echo $html;
								
								?>
							</div>
						</div>
						<!-- /.box -->
					</div>
					<?php }?>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>

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