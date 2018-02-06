<?php echo $body['selection'];?>

<div class="row">
	<div class="col-md-12">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<?php
					$count = 0;
					foreach ($table as $k => $v) {
						if($count != 0){
							echo '<li><a href="#tab_' . $k . '" data-toggle="tab">' . ucfirst(str_replace("_kpi", "", $k)) . '</a></li>';
						}else{
							echo '<li class="active"><a href="#tab_' . $k . '" data-toggle="tab">' . ucfirst(str_replace("_kpi", "", $k)) . '</a></li>';
						}
						
						$count ++;
					}
				?>
				<li class="box-tools pull-right ub-controls">
					<a class="btn btn-box-tool" href="#" title="Download excel file!" id="downloadExcel">
						<img src="<?php echo base_url('public/frontend/dist/img/export.gif'); ?>" width="20px" height="20px" />
					</a>
				</li>
				<li class="box-tools pull-right ub-controls">
					<a class="btn btn-box-tool" href="#" title="Copy to clipboard!"  id="copy">
						<img src="<?php echo base_url('public/frontend/dist/img/copy.png'); ?>" width="22px" height="22px" />
					</a>
				</li>
			</ul>
			<div class="tab-content">
				<?php
				$count = 0;
				foreach ($table as $k => $v) {
					if($count != 0){
						echo '<div class="tab-pane" id="tab_' . $k . '">';
					}else{
						echo '<div class="tab-pane active" id="tab_' . $k . '">';
					}
					
					if(count($table[$k]) == 0){
						echo "<p>Hiện không có dữ liệu của thời gian trên, bạn vui lòng chọn ngày khác,
	     hoặc liên hệ <b>[canhtq@vng.com.vn or quangctn@vng.com.vn or lamnt6@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";
					}else{
						/* echo '<button class="btn btn-primary" id="download">';
						echo '<img src="' . base_url('public/frontend/dist/img/excel.png') . '"/>';
						echo '</button>'; */
						echo '<div class="table-responsive kpi-table">';
						echo '<table class="table table-striped table-bordered" id="' . $k .'" width="100%" data-export-title="' . $table[$k]["exportTitle"] . '">';
						$maxCount = 0;
                        //var_dump($table[$k]);exit();
						foreach ($table[$k] as $key => $value) {
						
							if($key == "exportTitle"){
								continue;
							}
							$count = count($value);
						
							if($count < $maxCount){
								$count = $maxCount;
							}else{
								$maxCount = $count;
							}
						
							if($key == 'log_date'){
						
								echo '<thead class="thead-inverse">';
								echo '<tr>';
								
								/* if($k == "all_kpi"){
								
									echo '<th class="text-center"></th>';
								} */
								echo '<th class="text-left" style="width: auto;">KPI Name</th>';
						
								for($i = 0; $i < 4; $i++){
						
									if($i > 0){
										//echo '<th class="text-center" colspan="2">' . $value[$i] . '</th>';
										echo '<th class="text-center tableexport-ignore">' . $value[$i + 3] . '</th>';
										echo '<th class="text-center">' . $value[$i] . '</th>';
									}else{
										echo '<th class="text-center">' . $value[$i] . '</th>';
									}
								}
								echo '</tr>';
								echo '</thead>';
							}else{
								echo '<tr>';
								/* if($k == "all_kpi"){
									if($key == 'a1'){
							
										echo '<td style="vertical-align: middle;"><b>USER</b></td>';
									}else if($key == 'pu1'){
										echo '<td style="vertical-align: middle;"><b>REVENUE</b></td>';
									}else{
							
										echo '<td style="vertical-align: middle;"></td>';
									}
								} */
								echo '<td class="text-left" style="width: auto;"><b>' .$key . '</b></td>';
						
								for($i = 0; $i < 4; $i++){
									if($i > 0){
										echo '<td class="text-right tableexport-ignore">' . $value[$i + 3] . '</td>';
										echo '<td class="text-right">' . $value[$i] . '</td>';
									}else{
										echo '<td class="text-right">' . $value[$i] . '</td>';
									}
								}
								echo '</tr>';
							}
						}
						echo '</table>';
						echo '</div>';
					}
					
					/* echo '<div class="text-left">';
					echo '<a id="excel_' . $k . '" class="btn btn-social-icon btn-dropbox"><i class="fa fa-file-excel-o"></i></a>';
					echo '<a id="print_' . $k . '" class="btn btn-social-icon btn-dropbox"><i class="fa fa-print"></i></a>';
					echo '</div>'; */
					echo '<div class="clearfix"></div>';
					echo '</div>';
					
					$count ++;
				}
				?>
				<!-- /.tab-pane -->
			</div>
			<!-- /.tab-content -->
		</div>
	</div>
</div>
