<?php echo $body['selection'];?>
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
	<ul class="nav nav-tabs" id="top-games-report">
		<li class="active" id="report-top-pc-games"><a href="#tab_report-top-pc-games" data-toggle="tab" data-id="1">PC Games</a></li>
		<li id="report-top-mobile-games"><a href="#tab_report-top-mobile-games" data-toggle="tab" data-id="7">Mobile Games</a></li>
		<li id="report-top-all-games"><a href="#tab_report-top-all-games" data-toggle="tab" data-id="10">All Platform</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_report-top-pc-games">
			<div id='report-top-pc-games-loading' style='display:none'>
			  <img src='<?php echo base_url('public/frontend/dist/img/loading-animation.gif'); ?>'/>
			</div>
		</div>
		<div class="tab-pane" id="tab_report-top-mobile-games">
			<div id='report-top-mobile-games-loading' style='display:none'>
			  <img src='<?php echo base_url('public/frontend/dist/img/loading-animation.gif'); ?>'/>
			</div>
		</div>
		<div class="tab-pane" id="tab_report-top-all-games">
			<div id='report-top-all-games-loading' style='display:none'>
			  <img src='<?php echo base_url('public/frontend/dist/img/loading-animation.gif'); ?>'/>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
	<script type="text/javascript">
	    $(document).ready(function() {
	    	renderChartTopPC();
	    });
	    	    
</script>
</div>
