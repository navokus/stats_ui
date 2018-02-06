<section class="invoice">
	<!-- title row -->
	<div class="row">
		<div class="col-xs-12">
			<h2 class="page-header">
				<i class="fa fa-warning"></i> Access Denied!
			</h2>
		</div>
		<!-- /.col -->
	</div>
	<!-- info row -->
	<div class="row invoice-info">
		<div class="col-md-12">
			You do not have permission on any <?php echo strtoupper($body['gameType']);?> game!
		</div>
	</div>
</section>

<script type="text/javascript">

	$(".content-header").remove();
	$("title").html("Stats Information!");
</script>