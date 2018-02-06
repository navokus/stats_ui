<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 20/04/2016
 * Time: 09:31
 */

?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-th"></i> <?php echo $title ?></h3>
                <div class="box-tools">
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
                        //var_dump($data);exit();
            			echo '<table class="table table-bordered table-bordered-gray table-striped" id="' . $id .'" width="100%" data-export-title="' . $exportTitle . '">';
            			$maxCount = 0;
            			foreach ($data as $key => $value) {
            				 
            				$count = count($value);
            				 
            				if($count < $maxCount){
            					$count = $maxCount;
            				}else{
            					$maxCount=$count;
            				}
            				 
            				if($key == 'log_date' || $key == "server" || $key == "channel" || $key == "package"){
            			
            					echo '<thead class="thead-inverse"><tr>';
            					echo '<th class="text-center"> KPI Name </th>';
            			
            					for($i = 0; $i < $count; $i++ ){
            						 
            						echo '<th class="text-center">' . $this->util->get_xcolumn_by_timming($value[$i], $timing, true)  . '</th>';
            					}
            			
            					echo '</tr></thead>';
            				}else{
            			
            					echo '<tr>';
            					echo '<td class="text-left"><b>' . $header[$key] . '</b></td>';
            			
            					for($i = 0; $i < $count; $i++ ){
            						if(isset($value[$i])){
            							echo '<td class="text-right">' .  $value[$i]  . '</td>';
            						}else{
            							echo '<td class="text-right"> 0 </td>';
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
