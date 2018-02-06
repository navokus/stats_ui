<div class="row">
<div class="col-md-6">
    <div class="box box-danger">        
        <div class="box-header">
            <ol class="breadcrumb">
                <li><a href="<?php echo site_url();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?php echo site_url('User');?>">Users</a></li>
                <li class="active">Group Detail</li>
            </ol>
            <h3 class="box-title">Group Detail </h3>
        </div>
        <form role="form" name="clonegroup" method="post" action="">
        <div class="box-body">
        <?php if(validation_errors() != ''):?>
                    <div class="callout callout-warning">
                        <?php echo validation_errors(); ?>
                    </div>
                <?php endif;?>
        <table class="table table-striped">
            <tbody>
                <tr>
                    <td>Group Name:</td>
                    <td>
                        <?php echo $aGroup['GroupName'];?> </a>
                    </td>
                </tr>
                <tr>
                    <td>Active:</td>
                    <td>
                        <?php echo $aGroup['Active'] ? 'Yes' : 'No';?>
                    </td>
                </tr>
                <tr>
                    <td>Edit:</td>
                    <td>
                        <a href="<?php echo base_url('index.php/User/group_edit/'.$aGroup['GroupId']) ;?>"><?php echo ' Edit' ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                    <div class="row">
                    <?php if(count($aGroupGames)):?>
                            	
                                <?php $i=1; foreach($aGroupGames as $aGroupGame):?>
                                   <div class="col-md-4">
                                        <?php echo $i .". ". $aGroupGame['GameName'];$i++?>
                                    </div>
                                <?php endforeach;?>
                            
                        <?php else:?>
                        <?php endif;?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Clone to new Group</td>
                    <td>
                        <div class="form-group">
					         <label>Group Name</label>
                    <?php echo form_input(array(
                        'class' => 'form-control',
                        'name' => 'group_name',
                        'value' => ''
                    ));?>
					     </div>
					     <button type="submit" class="btn btn-primary">Clone</button>
                    </td>
                </tr>
            </tbody>
        </table>
         </div>
         <div class="box-footer">
		
	</div>
	</form>
    </div>
</div>


<div class="col-md-6">
    <div class="box box-danger">        
        <div class="box-header">
            
            <h3 class="box-title">Users Ingroup</h3>
        </div>
        <div class="box-body">
        
        <table class="table table-striped">
            <tbody>
               
                <tr>
                    <td>
                    <div class="row">
                        <?php if(count($users)):?>
                            <ul>
                                <?php $i=1; foreach($users as $vusers):?>
                                    <div class="col-md-4">
                                        <?php echo $i .". ".$vusers['username'];$i++?>
                                    </div>
                                <?php endforeach;?>
                            </ul>
                        <?php else:?>
                        <?php endif;?>
                         </div>
                        
                    </td>
                </tr>
                
            </tbody>
        </table>
         </div>
    </div>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
	  $("#groupid").select2();
	});
</script>