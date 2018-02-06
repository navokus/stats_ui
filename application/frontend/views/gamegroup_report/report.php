<?php echo $body['selection'];?>
<script type="text/javascript">
    var api_url = <?php echo "'".$body ['api_url'] ."'" ?>;
</script>
<script type="text/javascript">
var selectedTab="all";
var selectedMonth="";
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
	<ul class="nav nav-tabs" id="top-games-report">
		<li class="active" id="gid-all"><a href="#tid-all" data-toggle="tab" data-id="all">All Games</a></li>
		<?php $idx=1?>
		<?php foreach ($body ['groups'] as $key => $value ):?>
				<li id=<?php echo "\"" ."gid-" .$value["owner"]."\"";?>><a href=<?php echo "\"#" ."tid-" .$value["owner"]."\"";?> data-toggle="tab" data-id=<?php echo "\"" ."" .strtoupper($value["owner"])."\"";?>><?php echo strtoupper($value["owner"]);?></a></li>
				<?php $idx++;?>
		<?php endforeach;?>
		<li class="pull-right">
		<div class="btn-group">
		<div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                        <?php $idx=0?>
                        	<?php foreach ($body ['dates'] as $key => $value ):?>
                        	<?php if($idx<2){$idx++; continue;}?>
                          <li><a href="#" id="<?php echo "group-rpt-month-".$idx;?>" data="<?php echo $body ['dates'][$idx];?>"><?php echo date('Y-m', strtotime($body ['dates'][$idx])); $idx++;?></a></li>
                          <?php endforeach;?>
                        </ul>
                      </div>
                      <button type="button" class="btn btn-info" id="group-rpt-month-1" data="<?php echo $body ['dates'][1];?>"><?php echo date('Y-m', strtotime($body ['dates'][1]));?></button>
                      <button type="button" class="btn btn-info" id="group-rpt-month-0" data="<?php echo $body ['dates'][0];?>"><?php echo date('Y-m', strtotime($body ['dates'][0]))."(current)";?></button>
                      
                    </div></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tid-all">
			<div id='lid-all' style='display:none'>
			  <img src='<?php echo base_url('public/frontend/dist/img/loading-animation.gif'); ?>'/>
			</div>
		</div>
		<?php $idx=1?>
		<?php foreach ($body ['groups'] as $key => $value ):?>
		<div <?php  if($idx==1) {echo "class=\"tab-pane\"";} else {echo "class=\"tab-pane\"";} ?> id=<?php echo "\"" ."tid-" .$value["owner"]."\"";?>>
			<div id=<?php echo "\"" ."lid-" .$value["owner"]."\"";?> style="display:none"><?php echo $value["owner"];?>
			  <img src='<?php echo base_url('public/frontend/dist/img/loading-animation.gif'); ?>'/>
			</div>
		</div>
		<?php $idx++;?>
		<?php endforeach;?>
		<div class="clearfix"></div>
	</div>
</div>
<script type="text/javascript">

$(document).ready(function() {
	var groupAll="all";
	selectedTab=groupAll;
	
	$("#gid-all").on("click", function(e){renderGameGroupReport(groupAll);});
	<?php $idx=0?>
	<?php foreach ($body ['groups'] as $key => $value ):?>$(<?php echo "\"" ."#group-rpt-month-" .$idx."\"";$idx++;?>).on("click", function(e){selectedMonth = $(this).attr('data');renderGameGroupReport(selectedTab);});<?php endforeach;?>
	
<?php foreach ($body ['groups'] as $key => $value ):?>$(<?php echo "\"" ."#gid-" .$value["owner"]."\"";?>).on("click", function(e){var group<?php echo $value["owner"];?> = <?php echo "\"" .$value["owner"]. "\";";?>renderGameGroupReport(group<?php echo $value["owner"];?>);});<?php endforeach;?>

renderGameGroupReport(groupAll);;
});
</script>