<?php
if ($_SESSION['message']) {
    ?>
    <div class="col-md-6 col-md-offset-3">
        <?php echo '<blockquote><p class="text-red">' . $_SESSION['message'] . '</p></blockquote>'; ?>
    </div>
    <?php
    unset($_SESSION['message']);
}
?>


<div class="box box-solid bg-green-gradient">
    <div class="box-header">
        <h3 class="box-title">THỐNG KÊ KHUYẾN MÃI</h3>

    </div>
    <!-- /.box-header -->
    <form name="promotion" action="<?php echo site_url('Promotion/statistic') ?>" method="post">
        <div class="box-footer text-black" style="display: block;">
            <div class="col-md-3">
                <select class="form-control equal" id="mpm2" name="month[2]">
                    <?php
                    foreach ($optionsMonth as $key => $value) {

                        if ($post['month'][2] == $key) {
                            $selected = ' selected ';
                        } else {
                            $selected = '';
                        }

                        echo "<option value='" . $key . "' " . $selected . ">" . $value . "</option>";
                    }
                    ?>
                </select>
            </div>
            <!-- /.col -->
            <div class="col-md-3">
                <select class="form-control" id="mpm1" name="month[1]">
                    <?php
                    foreach ($optionsMonth as $key => $value) {

                        if ($post['month'][1] == $key) {
                            $selected = ' selected ';
                        } else {
                            $selected = '';
                        }

                        echo "<option value='" . $key . "' " . $selected . ">" . $value . "</option>";
                    }
                    ?>
                </select>
            </div>
            <!-- /.col -->

            <div class="col-md-1">
                <button type="submit" class="btn btn-danger">Xem</button>
            </div>
            <!-- /.col -->

        </div>
    </form>
</div>

<?php if ($content) : ?>
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <?php

                    $aPromotion = array_reverse($_SESSION['promoStatis']);
                    $i = 1;
                    foreach ($aPromotion as $key => $value) {
                        $gameCode = $times = $preDate = $curDate = "";
                        list($gameCode, $times, $preDate, $curDate) = explode('_', $value['header']);
                        if ($this->uri->segment(3) == $key) {
                            $active = ' active ';
                        } else if (!$this->uri->segment(3) && $i == 1) {
                            $active = ' active ';
                        } else {
                            $active = '';
                        }

                        if ($preDate && $curDate) {
                            $pre_currDate = str_replace('-', '/', $preDate) . ' - ' . str_replace('-', '/', $curDate);
                        } else {
                            $pre_currDate = "";
                        }

                        echo '
						<li class="' . $active . '">
							<a href="' . site_url('Promotion/statistic/' . $key) . '" >' . $times . ' ' . $pre_currDate . '</a>
							<button onclick="window.location.href=\'' . site_url('promotion_module/Statistic/delCache/' . $key) . '\'" class="btn btn-box-tool" data-toggle="tooltip" title="Remove" style="position:absolute; top:-5px; right:0px"><i class="fa fa-times"></i></button>
						</li>';

                        $i++;

                        if ($i == 5) break;
                    }
                    ?>


                </ul>


                <div class="tab-content">
                    <div class="tab-pane active">

                        <!-- content here -->
                        <div class="row" id="content">
                            <?php echo $content; ?>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->

            </div>
            <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
<?php endif ?>
