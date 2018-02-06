<?php
/**
 * Created by IntelliJ IDEA.
 * User: canhtq
 * Date: 22/06/2016
 * Time: 11:19
 */
?>
<script type="text/javascript">
	var timing =<?php echo "'".$body ['selected_tab'] ."'" ?>;
	var api_url = <?php echo "'".$body ['api_url'] ."'" ?>;
	var chart = <?php echo "'".$body ['chart'] ."'" ?>;
</script>
<div class="box box-primary">
    <?php echo $body['selection'];?>
    
    <div class="box-body">
    	<div class="row">
	        <div class="col-md-3 col-lg-3 col-xs-12">
	        	<div class="input-group">
		         	<span class="input-group-addon"><i class="fa fa-bar-chart"></i></span>
	        	<select id="selection_kpi_comparison" name="selection_kpi_comparison" onchange="renderKpiComparison()">
						<?php foreach ($body ['comparison_charts'] as $key => $value ):?> <option value="<?php echo $key ; ?>"> <?php echo $value ; ?></option><?php endforeach;?>
				</select>
				</div>
	        </div>
	     </div>
	     <div class="row">
	     	<div class="col-md-12">
	     	<div id="content"></div>
	     	</div>
	     </div>
 </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
  $("#selection_kpi_comparison").select2();
});
</script>
<div class="row">
                    <div class="col-md-12">
                       <?php echo $body['tables'];?>
                    </div>
</div>
<script type="text/javascript">
	    $(document).ready(function() {
	    	renderKpiComparison();
	    });
</script>