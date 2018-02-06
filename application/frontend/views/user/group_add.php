<div class="row">
<div class="col-md-12">
	<!-- general form elements disabled -->
	<div class="box box-warning">
        <?php echo form_open('', array('role' => 'form'));?>
            <div class="box-header">
			<h3 class="box-title">Add Group</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">         
                <?php if(validation_errors() != ''):?>
                    <div class="callout callout-warning">
                        <?php echo validation_errors(); ?>
                    </div>
                <?php endif;?>
                <!-- text input -->
			<div class="form-group">
				<label>Group Name</label>
                    <?php
																				
echo form_input ( array (
																						'class' => 'form-control',
																						'placeholder' => "Enter ...",
																						'name' => 'group_name' 
																				) );
																				?>
                </div>


			<div class="form-group">
				<label>Active</label>
                    <?php
																				
echo form_dropdown ( 'is_active', array (
																						'1' => 'Yes',
																						'0' => 'No' 
																				), '', 'class="form-control"' );
																				?>
                </div>

			<div class="form-group">
				<label>Game</label>
				<div class="row">
                        <?php
																								$iCount = 0;
																								$iRowsSize = round ( count ( $aGames ) / 2 );
																								?>
                        <?php foreach ($aGames as $aGame):?>
                            <?php echo '<div class="col-md-3">';?>
                            <div class="checkbox">
						<label>
                                    <?php echo form_checkbox('game_codes[]', $aGame['GameCode']);?>
                                    <?php echo $aGame['GameName'] . ' ('.strtoupper($aGame['GameCode']). ' - ' . strtoupper($aGame['owner']).    ')';?>
                                </label>
					</div>
                            <?php $iCount++;?>
                            <?php echo '</div>';?>
                        <?php endforeach;?>
                    </div>
			</div>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>
        <?php echo form_close();?>
    </div>
	<!-- /.box -->
</div>
</div>