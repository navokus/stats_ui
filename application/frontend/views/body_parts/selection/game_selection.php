<div class="box box-primary">
    <div class="box-footer text-black text-left">

        <form name="form" action="" method="post" class="form-horizontal">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
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

            </div>
        </form>
    </div>
</div>
