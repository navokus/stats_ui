<div class="box box-primary>
    <div class=" box-body">
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
            <!-- /.col -->
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title">Game</h3>
                        <div class="box-tools">
                            <form name="user" method="get" accept="">
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-primary btn-sm"
                                                onclick="window.location.href='<?php echo base_url('index.php/GameMaster/addGame'); ?>'">
                                            Add Game
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped" id="admin-master-games-table">
                    <thead>
                    <tr>
                        <th>Function</th>
                        <th>Game Name</th>
                        <th>Kpi Type</th>
                        <th>Group Id</th>
                        <th>Data Source</th>
                        <th>Create Date</th>
                    </tr>
                    </thead>
                    <?php
                    foreach ($list as $key => $value) {
                        ?>
                        <tr>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success btn-sm">View</button>
                                    <button type="button" class="btn btn-primary btn-sm"
                                            onclick="window.location.href='<?php echo base_url('index.php/GameMaster/editGame/' . $value['kpi_type'] . "/" . $value['group_id'] . "/" . $value['data_source']); ?>'">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm"
                                            onclick="if(confirm('Do you want to delete Game?')) window.location.href='<?php echo base_url('index.php/GameMaster/delGame/' . $value['kpi_type'] . "/" . $value['group_id'] . "/" . $value['data_source']); ?>'">
                                        Delete
                                    </button>
                                </div>
                            </td>
                            <td><?php echo $value['game_code'] ?></td>
                            <td><?php echo $value['kpi_type'] ?></td>
                            <td><?php echo $value['group_id'] ?></td>
                            <td><?php echo $value['data_source'] ?></td>
                            <td><?php echo $value['create_date'] ?></td>


                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
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