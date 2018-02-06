<div class="row">
    <div class="col-md-12">

        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title">Game</h3>
                <div class="box-tools">
                    <form name="user" method="get" accept="">
                        <div class="input-group">
                            <input type="text" name="like-GameName"
                                   value="<?php echo $_GET['like-GameName'] ?>"
                                   class="form-control input-sm pull-right" style="width: 150px;"
                                   placeholder="Search">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                                <button type="button" class="btn btn-primary btn-sm"
                                        onclick="window.location.href='<?php echo base_url('index.php/Game/addGame'); ?>'">Add
                                    Game</button>
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
        <table class="table table-striped" id="admin-games-table">
            <thead>
            <tr>
                <th>Function</th>
                <th>Name</th>
                <th>Code</th>
                <th>GameType2</th>
                <th>Owner</th>
                <th>Send Mail</th>
                <th>Status</th>
                <th>Market</th>
                <th>Created</th>
                <th>AlphaTest Date</th>
                <th>Open Date</th>
                <th>Contact Product</th>
                <th>Contact Techom</th>

            </tr>
            </thead>
            <?php
            foreach ( $list as $key => $value ) {
                ?>
                <tr>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success btn-sm">View</button>
                            <button type="button" class="btn btn-primary btn-sm"
                                    onclick="window.location.href='<?php echo base_url('index.php/Game/editGame/' . $value['GameCode']); ?>'">Edit</button>
                            <button type="button" class="btn btn-danger btn-sm"
                                    onclick="if(confirm('Do you want to delete Game?')) window.location.href='<?php echo base_url('index.php/Game/delGame/' . $value['GameCode']); ?>'">Delete</button>
                        </div>
                    </td>
                    <td><?php echo $value['GameName'] ?></td>
                    <td><?php echo $value['GameCode'] ?></td>
                    <td><?php echo $value['GameType2'] ?></td>
                    <td><?php echo $value['owner'] ?></td>
                    <td><?php echo $value['SendMail'] ?></td>
                    <td><?php echo $value['Status'] ?></td>
                    <td><?php echo $value['market'] ?></td>
                    <td><?php echo $value['CreatedDate'] ?></td>
                    <td><?php echo $this->util->db_date_to_user_date($value['AlphaTestDate']) ?></td>
                    <td><?php echo $this->util->db_date_to_user_date($value['OpenDate'])?></td>
                    <td><?php echo $value['ContactProduct'] ?></td>
                    <td><?php echo $value['ContactTechOm'] ?></td>

                </tr>
            <?php } ?>
        </table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#admin-games-table').DataTable( {
            "paging":   true,
            "ordering": true,
            "info":     false,
            "searching": true,
            "order": [[ 0, "asc" ]]
        } );
    } );
</script>