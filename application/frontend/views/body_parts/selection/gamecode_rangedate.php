<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 20/04/2016
 * Time: 09:08
 */

?>
<form name="form" action="" method="post">
    <div class="box-body">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-gamepad"></i></span>
                    <select class="form-control" name="default_game" id="slGameSelection"
                            onchange="this.form.submit()">
                        <?php
                        $list_games = $body['aGames'];
                        //var_dump($list_games);
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


            <div class="option_day">
                <div class="col-md-4 col-lg-4 col-xs-12">
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
    </div>
</form>
