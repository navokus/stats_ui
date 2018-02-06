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
            </div>
            <div class="box-body text-center first">
            	<?php
            		if(count($data) == 0 ){

            			echo "<p>Hiện không có dữ liệu của thời gian trên, bạn vui lòng chọn ngày khác,
     hoặc liên hệ <b>[canhtq@vng.com.vn or quangctn@vng.com.vn or lamnt6@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";
            		}else{
            			echo '<table class="table table-bordered table-bordered-gray table-striped" id="' . $id .'" width="100%">';
            			$maxCount = 0;
            			foreach ($data as $key => $value) {
            				 
            				$count = count($value);
            				 
            				if($count < $maxCount){
            					$count = $maxCount;
            				}else{
            					$maxCount=$count;
            				}
            				 
            				if($key == 'log_date'){
            			
            					echo '<thead><tr>';
            					echo '<th class="text-center"></th>';
            					echo '<th class="text-center"> KPI </th>';
            			
            					for($i = 0; $i < $count; $i++){
            						 
            						echo '<th class="text-center">' . $value[$i] . '</th>';
            					}
            			
            					echo '</tr></thead>';
            				}else{
            			
            					echo '<tr>';
            			
            					if($key == 'a1'){
            						 
            						echo '<td style="vertical-align: middle;"><b>USER</b></td>';
            					}else if($key == 'pu1'){
            						echo '<td style="vertical-align: middle;"><b>REVENUE</b></td>';
            					}else{
            						 
            						echo '<td style="vertical-align: middle;"></td>';
            					}
            			
            					echo '<td><b>' . strtoupper($header[$key]) . '</b></td>';
            			
            					for($i = 0; $i < $count; $i++){
            						echo '<td>' . $value[$i] . '</td>';
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
