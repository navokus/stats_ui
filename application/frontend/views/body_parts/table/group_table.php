<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 20/04/2016
 * Time: 09:31
 */
//var_dump($data);
?>
<?php if(count($data) != 0) {?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title pull-left"><i class="fa fa-th"></i> <?php echo $title ?> (<a href="#" onclick="tableToExcel('group')">download</a>)</h3>
                <div class="box-tools">
                	<a download="<?php echo $exportTitle . '.xls'?>" href="" id="dlink" style="display:none;"></a>
                	<!-- <a class="btn btn-box-tool" href="#" title="Copy to clipboard!"  id="copy">
						<img src="<?php echo base_url('public/frontend/dist/img/copy.png'); ?>" width="22px" height="22px" />
					</a> -->
                	<a class="btn btn-box-tool" href="#" title="Download excel file!" id="downloadExcelv2" onclick="tableToExcel('group')">
						<img src="<?php echo base_url('public/frontend/dist/img/export.gif'); ?>" width="20px" height="20px" />
					</a>
              </div>
            </div>
            <div class="box-body text-center first hide">
            	<div class="outer">
  				<div class="inner">
            	<?php
					$selectedArr = array();
					$table_header_config = $this->util->get_kpi_header_name();
					foreach($selectedGroup as $kpi => $groupArr){
						foreach($groupArr as $groupId => $name){
							if (!array_key_exists($groupId, $selectedArr)){
								$selectedArr[$groupId] = $name;
							}
						}
					}
					arsort($days);
					ksort($selectedArr);
					
					$numberDays = count($days);
            		if($numberDays == 0 ){
            			echo "<p>Hiện không có dữ liệu của thời gian trên, bạn vui lòng chọn ngày khác,
     hoặc liên hệ <b>[canhtq@vng.com.vn or quangctn@vng.com.vn or lamnt6@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";
            		}else{
            			echo '<table class="table table-bordered table-bordered-gray table-striped text-right" id="' . $id .'" width="100%" data-export-title="' . $exportTitle . '">';
            			
            			echo '<thead><tr>';
            			echo '<th class="text-center header" rowspan="2" style="width: 100px;"> KPI Name </th>';
            			foreach($days as $day){
            				$numberGroup = count($selectedArr);
            				
            				echo '<th class="text-center header" colspan="'. $numberGroup .'"> ' . $this->util->get_xcolumn_by_timming($day, $timing, true) . ' </th>';
            			}
            			echo '</tr>';
            			echo '<tr>';
            			for($i = 0; $i < $numberDays; $i++){
	            			foreach($selectedArr as $group){
	            				echo '<th class="text-center header">' . $group . '</th>';
	            			}
            			}
            			echo '</tr></thead>';
            			foreach ($data as $kpiName => $group) {
            				echo '<tr>';
            				echo '<th>' . $table_header_config[$kpiName] . '</th>';
            				foreach($days as $day){
            					foreach($selectedArr as $groupId => $name){
            						
            						if(strpos( $kpiName, "rr") === false && strpos( $kpiName, "acu") === false && strpos( $kpiName, "cr") === false){
            							echo '<td>' . number_format($group[$groupId][$day]) . '</td>';
            						}else{
            							if(strpos( $kpiName, "rr") === false && strpos( $kpiName, "cr") === false){
            								echo '<td>' . number_format($group[$groupId][$day], 2) . '</td>';
            							}else{
            								echo '<td>' . number_format($group[$groupId][$day], 2) . '%</td>';
            							}
            						}
            					}
            				}
            				echo '</tr>';
            			}
            			
            			echo '</table>';
            		}
            	?>
            	</div></div>
            </div>
        </div>
    </div>
    <span id="exportFileName" hidden="true"><?php echo $export_file_name ?></span>
</div>
<?php } ?>
