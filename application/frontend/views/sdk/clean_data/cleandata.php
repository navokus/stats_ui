<link rel="stylesheet" href="<?php echo base_url('public/frontend/bootstrap/css/bootstrap-select.min.css'); ?>" />
<script
    src="<?php echo base_url('public/frontend/bootstrap/js/bootstrap-select.min.js'); ?>"
    type="text/javascript">
</script>

<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.2.3/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.2.3/jquery-confirm.min.js"></script>-->


<div class="box box-primary>
    <div class=" box-body">
<form name="form" action="" method="post" onsubmit="return checkEmpty()">
    <div class="box-body">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-gamepad"></i></span>
                    <select class="form-control" name="default_game" id="slGameSelection">
                        <?php
                        $list_games = $body['aGames'];
                        foreach ($list_games as $value) {

                            if ($this->session->userdata('default_game') == $value ['GameCode']) {
                                $selected = ' selected ';
                            } else {
                                $selected = '';
                            }

                            echo "<option value='{$value['GameCode']}' {$selected} >{$value['GameName']} (" . strtoupper($value ['GameCode']) . ")</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="input-group" id="inputDate">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input value="<?php echo $body['day']['kpidatepicker'];?>" id="kpidatepicker" name="kpidatepicker" class="form-control" />
                        <span class="input-group-btn">
			            	<button type="submit" class="btn btn-danger" <!--onclick="return getConfirm()"-->Start</button>
			        	</span>
                    </div>
                </div>
            </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="sel1">Table Name</label>
                    <select class="selectpicker" multiple id="table_name" name="table_name" onchange="getValue()">
                        <?php foreach($list_table as $key=>$value) {  ?>
                            <option value="<?php echo $key?>" > <?php echo $value?></option>
                        <?php  } ?>
                    </select>
                    <input type="hidden" name="valueSelect" id="multiSelect">
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="input-group">
                    </br>
                    <input type="radio" id="clean" name="action" value="clean" <?php echo set_radio('action', 'clean', TRUE); ?>>
                    <label for="lesson-active">Clean Data</label>
                    <input type="radio" id="restore" name="action" value="restore">
                    <label for="lesson-active">Restore</label>

                </div>
            </div>

        </div>
        <!-- /.col -->
        </div>
</form>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#admin-master-games-table').DataTable({
            "paging": true,
            "ordering": true,
            "info": false,
            "searching": true,
            "pageLength": 7,
            "order": [[0, "asc"]]
        });
    });
</script>
<script type="text/javascript">
    function getValue() {
        $('#multiSelect').val(($('.selectpicker').val()));
    }
    function checkEmpty(){
        if ($('#multiSelect').val() == '' ){
            alert("Please choose table need back up or resotre")
            return false;
        }
        if (($('#clean').val() == '') && ($('#restore').val() == '' )){
            alert("Please choose action ")
            return false;
        }
    }
    /*function getConfirm() {
        $.confirm({
            title: 'Confirm!',
            content: 'Simple confirm!',
            buttons: {
                confirm: function () {
                    $.alert('Confirmed!');
                },
                cancel: function () {
                    $.alert('Canceled!');
                },

        });
    }*/
</script>

