<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 20/06/2016
 * Time: 16:00
 */
if($rawdata != null){
    $collapse = "collapsed-box";
    $button = "fa-plus";
}else{
    $button = "fa-minus";
}
?>

<div class="box box-primary <?php //echo $collapse;?>">
    <div class="box-footer text-black text-left">
        <form name="form" action="" method="post">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-gamepad"></i></span>
                        <select class="form-control" name="default_game" id="slGameSelection"
                                onchange="this.form.submit()">
                            <?php
                            $list_games = $body['aGames'];
                            foreach ( $list_games as $value ) {

                                if ($this->session->userdata ( 'default_game' ) == $value ['GameCode']) {
                                    $selected = ' selected ';
                                } else {
                                    $selected = '';
                                }

                                echo "<option value='{$value['GameCode']}' {$selected} >{$value['GameName']} (" . strtoupper ( $value ['GameCode'] ) . ")</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <?php if (isset($server_list) && count($server_list) != 0){
                    ?>
                    <div class="col-md-2 col-sm-4 col-xs-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-desktop"></i></span>
                            <select class="form-control" name="server_id" id="slServerSelection"
                                    onchange="this.form.submit()">
                                <?php
                                $count = count($server_list);
                                $count = ($count > 1) ? $count . " servers" : $count . " server";


                                echo "<option value='not_select'>Total $count</option>";
                                foreach ( $server_list as $server_id ) {

                                    if ($this->session->userdata ( 'server_id' ) == $server_id) {
                                        $selected = ' selected ';
                                    } else {
                                        $selected = '';
                                    }

                                    echo "<option value='$server_id' {$selected} >$server_id</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" id="game_before" name="game_before" value="<?php echo $this->session->userdata('default_game')?>">
                    </div>

                <?php   }

                ?>

                <!-- /.col -->

                <div class="option_day">
                    <div class="col-md-4 col-lg-4 col-xs-6">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input value="<?php echo $body['day']['default_range_date'];?>"  id="daterangepicker" name="daterangepicker" class="form-control" />
	                            <span class="input-group-btn">
			            	        <button type="submit" class="btn btn-danger">Xem</button>
			        	        </span>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


