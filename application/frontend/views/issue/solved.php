<form role="form" name="add" method="post" action="">
<div class="row">
<div class="col-md-12">
  <!-- general form elements disabled -->
  <div class="box box-warning">
    <div class="box-header">
      <h3 class="box-title">Issue</h3>
    </div><!-- /.box-header -->
    
    <div class="box-body">      	        
		<div class="row">

		<?php  
			$errors = validation_errors();
			if ($errors) {
				echo "<blockquote>" . $errors . "</blockquote>";
			}
		?>
		
		<div class="col-md-3">
	        <div class="form-group">
	          <label>Root cause</label>
	          <input type="text" name="message" class="form-control" value="<?php echo $_POST['message'] ?>" placeholder="Enter message ...">
	        </div>
	    </div>

	    
		</div>
        
    </div><!-- /.box-body -->
    <div class="box-footer">
		<button type="submit" class="btn btn-primary">Solved</button>
	</div>
  </div><!-- /.box -->
</div>
</div>

</form>