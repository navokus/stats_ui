<?php
/**
 * Created by IntelliJ IDEA.
 * User: canhtq
 * Date: 30/06/2017
 * Time: 13:55
 */
?>

<form name="form" action="" method="post" id="selectionForm">
    <div class="box-body">
        <div class="row">
            <div class="col-md-3 col-lg-3 col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-gamepad"></i></span>
                    <select class="form-control" name="game" id="game"
                            onchange="this.form.submit()">
                        <?php
                        $list_games = $body['user_games'];
                        foreach ( $list_games as $value ) {
                            if ($body['default_game'] == $value ['GameCode']) {
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

            <div class="col-md-4 col-lg-4 col-xs-12 ">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input value="<?php echo $body['daterangepicker'];?>"  id="daterangepicker" name="daterangepicker" class="form-control" />
                    <span class="input-group-btn">
                    	<button type="submit" class="btn btn-danger">Xem</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</form>
