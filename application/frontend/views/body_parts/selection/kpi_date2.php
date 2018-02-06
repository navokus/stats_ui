<?php

?>

<div class="box box-primary">
	<div class="box-footer text-black text-left">
		<form name="form" action="" method="post" class="form-horizontal">
			<div class="row">
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="input-group" id="inputDate">
			         	<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			        	<input value="<?php echo $body['day']['kpidatepicker'];?>" id="kpidatepicker" name="kpidatepicker" class="form-control" />
				    	<span class="input-group-btn">
			            	<button type="submit" class="btn btn-danger">Xem</button>
			        	</span>
			        </div>
		        </div>
	        </div>
			<!-- <div class="form-group hidden">
				<label for="inputPassword3" class="col-sm-2 control-label">Game: </label>
	
				<div class="col-sm-10">
					<input type="hidden" name="default_game" value="<?php echo $this->session->userdata('current_game') ?>">
				</div>
			</div> -->
		</form>
	</div>
</div>
