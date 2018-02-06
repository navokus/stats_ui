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
                <h3 class="box-title"><i class="fa fa-th"></i> Detail Report Country (<a href="#" onclick="tableToExcel('kpi_table')">download</a>)  </h3>
                <div class="box-tools">
                	<a download="<?php echo $exportTitle . '.xls'?>" href="" id="dlink" style="display:none;"></a>
              </div>
            </div>
            <div class="box-body text-center first ">
            	<div class="outer">
  				<div class="inner">
            	<?php
                arsort($selectedCountry);
					
					$numberDays = count($days);
            		if($numberDays == 0 ){
            			echo "<p>Hiện không có dữ liệu của thời gian trên, bạn vui lòng chọn ngày khác,
     hoặc liên hệ <b>[canhtq@vng.com.vn or quangctn@vng.com.vn or lamnt6@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";
            		}else{
            			echo '<table class="table table-bordered table-bordered-gray table-striped text-right" id="kpi_table" width="100%" data-export-title="' . $exportTitle . '">';
            			
            			echo '<thead><tr>';
            			echo '<th class="text-center header" rowspan="2" style="width: 100px;"> KPI Name </th>';
            			foreach($days as $day){
            				$numberGroup = count($selectedCountry);
            				echo '<th class="text-center header" colspan="'. $numberGroup .'"> ' . $this->util->get_xcolumn_by_timming($day, $timing, true) . ' </th>';
            			}
            			echo '</tr>';
            			echo '<tr>';
            			for($i = 0; $i < $numberDays; $i++){
	            			foreach($selectedCountry as $country){
	            				echo '<th class="text-center header">' . $country . '</th>';
	            			}
            			}
            			echo '</tr></thead>';
            			foreach ($lstKpiTableConfig as $kpiId => $name) {
            				echo '<tr>';
            				echo '<th>' . $name . '</th>';
            				foreach($days as $day){
            					foreach($selectedCountry as $Countryname){
            					    if(isset($datatable[$day][$game_code][$kpiId][$Countryname])){
                                        echo '<td>' . number_format($datatable[$day][$game_code][$kpiId][$Countryname]) . '</td>';
                                    }else{
                                        echo '<td>' . 0 . '</td>';
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
