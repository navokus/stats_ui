<div class="col-md-12">

    <div class="box box-solid box-<?php echo $color; ?>">
        <div class="box-header with-border">
            <h3 class="box-title">Từ <?php echo date('d/m/Y', strtotime($aPromo['FromDate'])); ?>
                đến <?php echo date('d/m/Y', strtotime($aPromo['ToDate'])); ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">

            <table class="table table-bordered table-striped">

                <tr>
                    <th class="text-center" style="border-right: 1px solid gray;">Đo lường</th>
                    <th class="text-center">Trước promotion</th>
                    <th class="text-center">Promotion</th>
                    <th class="text-center" style="border-right: 1px solid gray;">Sau promotion</th>
                    <th class="text-center" colspan="2">Đo lường % <br>(vs Trước promotion)</th>
                    <th class="text-center" colspan="2">Đo lường % <br>(vs Sau promotion)</th>
                </tr>

                <tr>
                    <td class="text-center" style="border-right: 1px solid gray;">User chi trả</td>
                    <td class="text-center"><?php echo number_format($aStatis['AccountTotalPayingPrevious']) ?></td>
                    <td class="text-center"><b><?php echo number_format($aStatis['AccountTotalPaying']) ?></b> <span
                            style='color:red'>/</span> <?php echo number_format($aStatis['AccountTotal']) ?></td>
                    <td class="text-center"
                        style="border-right: 1px solid gray;"><?php echo number_format($aStatis['AccountTotalPayingAfter']) ?></td>
                    <td class="text-center"><?php echo number_format($aStatis['AccountTotalPaying'] - $aStatis['AccountTotalPayingPrevious']) ?></td>
                    <td class="text-center text-light-blue">
                        <b><?php echo round(($aStatis['AccountTotalPaying'] - $aStatis['AccountTotalPayingPrevious']) / $aStatis['AccountTotalPayingPrevious'] * 100, 2) ?>
                            %</b></td>
                    <td class="text-center"><?php echo number_format($aStatis['AccountTotalPaying'] - $aStatis['AccountTotalPayingAfter']) ?></td>
                    <td class="text-center text-light-blue">
                        <b><?php echo round(($aStatis['AccountTotalPaying'] - $aStatis['AccountTotalPayingAfter']) / $aStatis['AccountTotalPayingAfter'] * 100, 2) ?>
                            %</b></td>
                </tr>

                <tr>
                    <td class="text-center" style="border-right: 1px solid gray;">Doanh thu</td>
                    <td class="text-center"><?php echo number_format($aStatis['RevenueTotalPrevious']) ?></td>
                    <td class="text-center"><?php echo number_format($aStatis['RevenueTotal']) ?></td>
                    <td class="text-center"
                        style="border-right: 1px solid gray;"><?php echo number_format($aStatis['RevenueTotalAfter']) ?></td>
                    <td class="text-center"><?php echo number_format($aStatis['RevenueTotal'] - $aStatis['RevenueTotalPrevious']) ?></td>
                    <td class="text-center text-light-blue">
                        <b><?php echo round(($aStatis['RevenueTotal'] - $aStatis['RevenueTotalPrevious']) / $aStatis['RevenueTotalPrevious'] * 100, 2) ?>
                            %</b></td>
                    <td class="text-center"><?php echo number_format($aStatis['RevenueTotal'] - $aStatis['RevenueTotalAfter']) ?></td>
                    <td class="text-center text-light-blue">
                        <b><?php echo round(($aStatis['RevenueTotal'] - $aStatis['RevenueTotalAfter']) / $aStatis['RevenueTotalAfter'] * 100, 2) ?>
                            %</b></td>
                </tr>

                <tr>
                    <td class="text-center" style="border-right: 1px solid gray;">User chơi game</td>
                    <td class="text-center"><?php echo number_format($aStatis['AccountTotalPlayingPrevious']) ?></td>
                    <td class="text-center"><?php echo "<b>" . number_format($aStatis['AccountTotalPlaying']) . "</b> <span style='color:red'>/</span> " . number_format($aStatis['AccountTotal']) ?></td>
                    <td class="text-center"
                        style="border-right: 1px solid gray;"><?php echo number_format($aStatis['AccountTotalPlayingAfter']) ?></td>
                    <td class="text-center"><?php echo number_format($aStatis['AccountTotalPlaying'] - $aStatis['AccountTotalPlayingPrevious']) ?></td>
                    <td class="text-center text-light-blue">
                        <b><?php echo round(($aStatis['AccountTotalPlaying'] - $aStatis['AccountTotalPlayingPrevious']) / $aStatis['AccountTotalPlayingPrevious'] * 100, 2) ?>
                            %</b></td>
                    <td class="text-center"><?php echo number_format($aStatis['AccountTotalPlaying'] - $aStatis['AccountTotalPlayingAfter']) ?></td>
                    <td class="text-center text-light-blue">
                        <b><?php echo round(($aStatis['AccountTotalPlaying'] - $aStatis['AccountTotalPlayingAfter']) / $aStatis['AccountTotalPlayingAfter'] * 100, 2) ?>
                            %</b></td>
                </tr>

                <tr>
                    <td class="text-center" style="border-right: 1px solid gray;">Thời gian chơi</td>
                    <td class="text-center"><?php echo number_format(ceil($aStatis['PlayingTimeTotalPrevious'] / 60)) ?></td>
                    <td class="text-center"><?php echo number_format(ceil($aStatis['PlayingTimeTotal'] / 60)) ?></td>
                    <td class="text-center"
                        style="border-right: 1px solid gray;"><?php echo number_format(ceil($aStatis['PlayingTimeTotalAfter'] / 60)) ?></td>
                    <td class="text-center"><?php echo number_format(ceil(($aStatis['PlayingTimeTotal'] - $aStatis['PlayingTimeTotalPrevious']) / 60)) ?></td>
                    <td class="text-center text-light-blue">
                        <b><?php echo round(($aStatis['PlayingTimeTotal'] - $aStatis['PlayingTimeTotalPrevious']) / $aStatis['PlayingTimeTotalPrevious'] * 100, 2) ?>
                            %</b></td>
                    <td class="text-center"><?php echo number_format(ceil(($aStatis['PlayingTimeTotal'] - $aStatis['PlayingTimeTotalAfter']) / 60)) ?></td>
                    <td class="text-center text-light-blue">
                        <b><?php echo round(($aStatis['PlayingTimeTotal'] - $aStatis['PlayingTimeTotalAfter']) / $aStatis['PlayingTimeTotalAfter'] * 100, 2) ?>
                            %</b></td>
                </tr>

                <tr>
                    <td class="text-center" style="border-right: 1px solid gray;">Active User</td>
                    <td class="text-center"><?php echo number_format($userActived['previous']['AccountTotal']) ?></td>
                    <td class="text-center"><?php echo number_format($userActived['current']['AccountTotal']) ?></td>
                    <td class="text-center"
                        style="border-right: 1px solid gray;"><?php echo number_format($userActived['after']['AccountTotal']) ?></td>

                    <?php
                    if ($userActived['current']['AccountTotal'] - $userActived['previous']['AccountTotal'] < 0) {
                        $text_color = 'text-red';
                    } else {
                        $text_color = 'text-light-blue';
                    }
                    ?>
                    <td class="text-center "><?php echo number_format($userActived['current']['AccountTotal'] - $userActived['previous']['AccountTotal']) ?></td>
                    <td class="text-center <?php echo $text_color; ?>">
                        <b><?php echo round(($userActived['current']['AccountTotal'] - $userActived['previous']['AccountTotal']) / $userActived['previous']['AccountTotal'] * 100, 2) ?>
                            %</b></td>

                    <?php
                    if ($userActived['current']['AccountTotal'] - $userActived['after']['AccountTotal'] < 0) {
                        $text_color = 'text-red';
                    } else {
                        $text_color = 'text-light-blue';
                    }
                    ?>
                    <td class="text-center"><?php echo number_format($userActived['current']['AccountTotal'] - $userActived['after']['AccountTotal']) ?></td>
                    <td class="text-center <?php echo $text_color; ?>">
                        <b><?php echo round(($userActived['current']['AccountTotal'] - $userActived['after']['AccountTotal']) / $userActived['after']['AccountTotal'] * 100, 2) ?>
                            %</b></td>
                </tr>

            </table>

        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

    <div class="box box-solid box-<?php echo $color; ?>">
        <div class="box-header with-border">
            <h3 class="box-title">Chi tiết loại User</h3>
            <!-- <button class="btn btn-<?php echo $color; ?> pull-right btn-xs" data-toggle="modal" data-target="#modal_cd_<?php echo $key; ?>"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Biểu đồ</button> -->
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">


            <table class="table table-bordered table-striped">

                <tr>
                    <th class="text-center" style="border-right: 1px solid gray;">Đối tượng</th>
                    <th class="text-center">Roles</th>
                    <th class="text-center">User chi trả</th>
                    <th class="text-center">Doanh thu</th>
                    <th class="text-center">User chơi game</th>
                    <th class="text-center">Thời gian chơi</th>

                </tr>

                <tr>
                    <td class="text-center" style="border-right: 1px solid gray;">Cũ</td>
                    <td class="text-center"><?php echo number_format($aStatisDetail['AccountTotalOld']) ?></td>
                    <td class="text-center"><?php echo number_format($aStatisDetail['AccountTotalPayingOld']) ?></td>
                    <td class="text-center"><?php echo number_format($aStatisDetail['RevenueTotalOld']) ?></td>
                    <td class="text-center"><?php echo number_format($aStatisDetail['AccountTotalPlayingOld']) ?></td>
                    <td class="text-center"><?php echo number_format(ceil($aStatisDetail['PlayingTimeTotalOld'] / 60)) ?></td>
                </tr>

                <tr>
                    <td class="text-center" style="border-right: 1px solid gray;">Mới</td>
                    <td class="text-center"><?php echo number_format($aStatisDetail['AccountTotalNew']) ?></td>
                    <td class="text-center"><?php echo number_format($aStatisDetail['AccountTotalPayingNew']) ?></td>
                    <td class="text-center"><?php echo number_format($aStatisDetail['RevenueTotalNew']) ?></td>
                    <td class="text-center"><?php echo number_format($aStatisDetail['AccountTotalPlayingNew']) ?></td>
                    <td class="text-center"><?php echo number_format(ceil($aStatisDetail['PlayingTimeTotalNew'] / 60)) ?></td>
                </tr>

                <tr style="font-weight: bold; border-top: 2px solid gray;">
                    <th class="text-center" style="border-right: 1px solid gray;">Tổng</th>
                    <th class="text-center"><?php echo number_format($aStatisDetail['AccountTotalNew'] + $aStatisDetail['AccountTotalOld']) ?></th>
                    <th class="text-center"><?php echo number_format($aStatisDetail['AccountTotalPayingNew'] + $aStatisDetail['AccountTotalPayingOld']) ?></th>
                    <th class="text-center"><?php echo number_format($aStatisDetail['RevenueTotalNew'] + $aStatisDetail['RevenueTotalOld']) ?></th>
                    <th class="text-center"><?php echo number_format($aStatisDetail['AccountTotalPlayingNew'] + $aStatisDetail['AccountTotalPlayingOld']) ?></th>
                    <th class="text-center"><?php echo number_format(ceil($aStatisDetail['PlayingTimeTotalNew'] / 60) + ceil($aStatisDetail['PlayingTimeTotalOld'] / 60)) ?></th>
                </tr>


            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

    <div class="box box-solid box-<?php echo $color; ?>">
        <div class="box-header with-border">
            <h3 class="box-title">Chi tiết doanh thu theo ngày</h3>
            <!-- <button class="btn btn-<?php echo $color; ?> pull-right btn-xs" data-toggle="modal" data-target="#modal_cd_<?php echo $key; ?>"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Biểu đồ</button> -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="col-md-12">
                <div id="container"></div>
            </div>
            <table class="table table-bordered table-bordered-gray table-striped" id="table-revenue-daily">
                <thead>
                <tr>
                    <th class="text-center" style="border-right: 1px solid gray;">Ngày</th>
                    <th class="text-center">User chi trả</th>
                    <th class="text-center">Doanh thu</th>
                    <th class="text-center">Doanh thu user cũ</th>
                    <th class="text-center">Doanh thu user mới</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($payPromotionDaily as $key => $value) : ?>
                    <tr>
                        <td class="text-center"
                            style="border-right: 1px solid gray;"><?php echo $value['CalculateValue'] ?></td>
                        <td class="text-center"><?php echo number_format($value['AccountTotalPaying']) ?></td>
                        <td class="text-center"><?php echo number_format($value['RevenueTotal']) ?></td>
                        <td class="text-center"><?php echo number_format($value['RevenueTotalOld']) ?></td>
                        <td class="text-center"><?php echo number_format($value['RevenueTotalNew']) ?></td>
                    </tr>
                    <?php
                    if (!$columnX) {
                        $columnX = "'" . $value['CalculateValue'] . "'";
                        $dataRevenue = $value['RevenueTotal'];
                        $dataUser = $value['AccountTotalPaying'];
                    } else {
                        $columnX .= ',' . "'" . $value['CalculateValue'] . "'";
                        $dataRevenue .= ',' . $value['RevenueTotal'];
                        $dataUser .= ',' . $value['AccountTotalPaying'];
                    }

                endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

    <div class="box box-solid box-<?php echo $color; ?>">
        <div class="box-header with-border">
            <h3 class="box-title">Target</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <form action="" name="form_target" method="post">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="text" class="form-control" name="name[]" placeholder="Name" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Target</label>
                        <input type="text" class="form-control" name="target[]" placeholder="Target">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Result</label>
                        <input type="text" class="form-control" name="result[]" placeholder="Target">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Description</label>
                        <input type="text" class="form-control" name="description[]" placeholder="Description">
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="form-group">
                        <label for="exampleInputEmail1">&nbsp;</label>
                        <button class="btn btn-success" type="submit">Add</button>
                    </div>
                </div>

                <table class="table table-bordered table-bordered-gray table-striped">
                    <thead>
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">Target</th>
                        <th class="text-center">Result</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Function</th>
                    </tr>
                    </thead>

                    <tbody>
                        <?php
                            $aTarget = json_decode($aPromo['Target'], TRUE);
                            foreach ($aTarget as $key => $target) {
                                echo '
                                <tr>
                                    <td class="text-center">'. $target['name'] .'<input type="hidden" class="form-control" name="name[]" value="'. $target['name'] .'" /></td>
                                    <td class="text-center">'. $target['target'] .'<input type="hidden" class="form-control" name="target[]" value="'. $target['target'] .'" /></td>
                                    <td class="text-center">'. $target['result'] .'<input type="hidden" class="form-control" name="result[]" value="'. $target['result'] .'" /></td>
                                    <td class="text-center">'. $target['description'] .'<input type="hidden" class="form-control" name="description[]" value="'. $target['description'] .'" /></td>
                                    <td class="text-center"><a href="'. site_url('Promotion/deleteTarget/' . $aPromo['GameCode'] . '/' . $aPromo['PromotionID'] . '/' . $key).'" class="btn btn-success btn-xs" >Delete</a></td>
                                </tr>
                                ';
                            }
                        ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

</div>

<script type="text/javascript">
    $(function () {
        $('#container').highcharts({
            colors: ['#00a65a', '#f39c12', '#7CB5EC', '#f56954'],
            chart: {
                zoomType: 'x'
            },
            title: {
                text: '<?php echo 'Biểu đồ Doanh Thu Khuyến mãi theo ngày' ?>'
            },
            subtitle: {
                text: 'Từ <?php echo date('d/m/Y', strtotime($aPromo['FromDate'])); ?>  đến <?php echo date('d/m/Y',strtotime($aPromo['ToDate'])); ?>'
            },
            xAxis: [{
                categories: [<?php echo $columnX; ?>],
                crosshair: true
            }],
            yAxis: [

                { // Secondary yAxis
                    title: {
                        text: 'USER',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    labels: {
                        // format: '{value}',
                        formatter: function () {
                            return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        },
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    opposite: true
                },
                { // Primary yAxis
                    labels: {
                        // format: '{value} ',
                        formatter: function () {
                            if (this.value >= 1000000000) {
                                this.value = this.value / 1000000000;
                                return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " Tỷ";
                            } else if (this.value >= 1000000) {
                                this.value = this.value / 1000000;
                                return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " Triệu";
                            } else {
                                return this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        },
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    title: {
                        text: 'DOANH THU',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    }
                }
            ],
            tooltip: {
                shared: true
            },
            series: [{
                name: 'Doanh Thu',
                type: 'column',
                yAxis: 1,
                data: [<?php echo $dataRevenue; ?>],
                tooltip: {
                    valueSuffix: ' VND'
                }

            }, {
                name: 'User chi trả',
                type: 'spline',
                data: [<?php echo $dataUser; ?>],
                tooltip: {
                    valueSuffix: ''
                },
                lineWidth: 3,

            }
            ]
        });
    });
</script>