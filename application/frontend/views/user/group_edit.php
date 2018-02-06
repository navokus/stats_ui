<div class="row">
<div class="col-md-12">
    <!-- general form elements disabled -->
    <div class="box box-warning">
        <?php echo form_open('', array('role' => 'form'));?>
            <div class="box-header">
                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="<?php echo site_url('User');?>">Users</a></li>
                    <li class="active">Group Edit</li>
                </ol>
                <h3 class="box-title">Edit Group</h3>
            </div><!-- /.box-header -->            
            <div class="box-body">         
                <?php if(validation_errors() != ''):?>
                    <div class="callout callout-warning">
                        <?php echo validation_errors(); ?>
                    </div>
                <?php endif;?>
                <!-- text input -->
                <div class="form-group">
                    <label>Group Name</label>
                    <?php echo form_input(array(
                        'class' => 'form-control',
                        'name' => 'group_name',
                        'value' => $this->input->post('group_name') ? $this->input->post('group_name') : $aGroup['GroupName']
                    ));?>
                </div>

                <div class="form-group">
                    <label>Active</label>
                    <?php echo form_dropdown('is_active', array(
                        '1' => 'Yes',
                        '0' => 'No'
                    ), $aGroup['Active'], 'class="form-control"');?>
                </div>

                <div class="form-group">
                    <label>Game</label>
                    <div class="row">
                        <?php 
                            $iCount = 0; 
                            $iRowsSize = round(count($aGames) / 4);
                        ?>
                        <?php foreach ($aGames as $aGame):?>
                            <?php echo '<div class="col-md-3">'?>
                            <div class="checkbox">
                                <label>
                                    <?php echo form_checkbox('game_codes[]', $aGame['GameCode'], in_array($aGame['GameCode'], $aGameCodes));?>
                                    <?php echo $aGame['GameName'] . ' (' . strtoupper($aGame['GameCode']) . ' - ' . strtoupper($aGame['owner']) . ' - Status:' . strtoupper($aGame['Status']) . ')';?>
                                </label>
                            </div>
                            <?php $iCount++;?>
                            <?php echo '</div>'?>
                        <?php endforeach;?>
                    </div>
                </div>
            </div><!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            
        <?php echo form_close();?>
    </div><!-- /.box -->
</div>
</div>
