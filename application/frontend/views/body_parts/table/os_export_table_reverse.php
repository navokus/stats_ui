<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-th"></i> <?php echo $title ?> (<a href="#" onclick="tableToExcel('<?php echo $id?>')">download</a>)  </h3>
                <div class="box-tools hidden">
                	<a download="<?php echo $exportTitle . '.xls'?>" href="" id="dlink" style="display:none;"></a>
                	<a class="btn btn-box-tool" href="#" title="Copy to clipboard!"  id="copy">
						<img src="<?php echo base_url('public/frontend/dist/img/copy.png'); ?>" width="22px" height="22px" />
					</a>
                	<a class="btn btn-box-tool" href="#" title="Download excel file!" id="downloadExcel">
						<img src="<?php echo base_url('public/frontend/dist/img/export.gif'); ?>" width="20px" height="20px" />
					</a>
              </div>
            </div>
            <div class="box-body text-center first">
            	<?php
            		if(count($data) == 0 ){

            			echo "<p>Hiện không có dữ liệu của thời gian trên, bạn vui lòng chọn ngày khác,
     hoặc liên hệ <b>[canhtq@vng.com.vn or quangctn@vng.com.vn or lamnt6@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";
            		}else{
            			echo '<table class="table table-bordered table-bordered-gray table-striped" id="' . $id .'" width="100%" data-export-title="' . $exportTitle . '">';
            			$maxCount = 0;
            			foreach ($data as $key => $value) {
            				 
            				$count = count($value);
            				 
            				if($count < $maxCount){
            					$count = $maxCount;
            				}else{
            					$maxCount=$count;
            				}
            				 
            				if($key == 'log_date'){
            			
            					echo '<thead class="thead-inverse"><tr>';
            					echo '<th class="text-left" rowspan="2"> KPI </th>';
            			
            					for($i = 0; $i < $count; $i++ ){
            						 
            						echo '<th class="text-center" colspan="3">' . $value[$i] . '</th>';
            					}
            					echo '</tr>';
            					echo '<tr>';
            					for($i = 0; $i < $count; $i++ ){
	            					echo '<th class="text-center">Android</th>';
	            					echo '<th class="text-center">IOS</th>';
	            					echo '<th class="text-center">Other</th>';
            					}
            					echo '</tr>';
            					echo '</thead>';
            				}else{
            			
            					echo '<tr>';
            					echo '<td class="text-left"><b>' . $header[$key] . '</b></td>';
            					for($i = 0; $i < $count; $i++ ){
            						foreach($value[$i] as $k => $v){
            							echo '<td class="text-right">' . $this->util->ud_format_number($v) . '</td>';
            						}
            					}
            			
            					echo '</tr>';
            				}
            			}
            			echo '</table>';
            		}
            	?>
            </div>
        </div>
    </div>
    <span id="exportFileName" hidden="true"><?php echo $export_file_name ?></span>
</div>
