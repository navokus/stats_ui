
<div class="col-md-12">

        <div class="box box-solid box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Cơ Cấu Doanh Thu Theo Nhóm</h3>
            <div class="btn-group pull-right ">
              <a class="btn btn-danger btn-xs " href="#chart" data-toggle="tab" ><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Biểu đồ</a>
              <a class="btn btn-danger btn-xs " href="#data"  data-toggle="tab" ><span class="fa fa-database" aria-hidden="true"></span> Dữ liệu</a>
            </div>
          </div><!-- /.box-header -->
          <div class="box-body tab-content no-padding">

          <div class="row tab-pane active" id="chart">
            <div class="col-md-6"  style="height: 400px">
              <div id="chart_user_<?php echo $key; ?>"></div>
            </div>
            <div class="col-md-6" style="height: 400px">
              <div id="chart_revenue_<?php echo $key; ?>"></div>
            </div>
          </div>

          <div class="tab-pane fade" id="data">
          <table class="table table-bordered table-striped no-margin">
            <tr>
              <th rowspan="2" style="border-right: 1px solid gray;vertical-align: middle;" class="text-center" >
                <?php 
                $table_mucnap = '<tr><th>Cấp</th><th>Mức nạp tiền</th></tr>';
                foreach ($aPayCompare as $grade => $value) {
                  $table_mucnap .= '<tr>';
                  $table_mucnap .= '<td>' . $value['cur_date']['GradeName'] . '</td>';
                  if($aGrade[$grade]['valueTo'] < 10000000000) {
                    $table_mucnap .= '<td>' . number_format($aGrade[$grade]['valueFrom']) . ' - ' . number_format($aGrade[$grade]['valueTo']) . '</td>'; 
                  } else {
                    $table_mucnap .= '<td> > '.number_format($aGrade[$grade]['valueFrom']) . '</td>'; 
                  }
                  $table_mucnap .= '</tr>';
                }
                ?>
                <a tabindex="0" class="btn btn-link no-padding no-margin text-black" role="button" data-toggle="popover" data-trigger="focus" title="" 
                data-content="<table class='table table-bordered table-striped no-padding no-margin'><?php echo $table_mucnap ?></table>" data-original-title="">Cấp <i class="fa fa-question-circle"></i></a>
              </th>
              <th colspan="5" class="text-center" style="border-right: 1px solid gray;">User</th>
              <th colspan="5" class="text-center" style="border-right: 1px solid gray;">Doanh Thu</th>
              <th colspan="2" class="text-center" >Export</th>
            </tr>

            <tr>
            <th class="text-center past" colspan="2"><?php echo $times ?> <?php echo $preDate ?></th>
              <th class="text-center" colspan="2"><?php echo $times ?> <?php echo $curDate ?></th>              
              <th class="text-center" style="border-right: 1px solid gray;">%</th>
              <th class="text-center past" colspan="2"><?php echo $times ?> <?php echo $preDate ?></th>
              <th class="text-center" colspan="2"><?php echo $times ?> <?php echo $curDate ?></th>              
              <th class="text-center" style="border-right: 1px solid gray;">%</th>
              <th class="text-center past" > <?php echo $preDate ?></th>
              <th class="text-center" > <?php echo $curDate ?></th>              
            </tr>

            <?php 

            if ($aPayCompare) {
              $sumCurDateAccountTotal = 0 ;
              $sumCurDateRevenueTotal = 0 ;
              $sumPreDateAccountTotal = 0 ;
              $sumPreDateRevenueTotal = 0 ;

              foreach ($aPayCompare as $grade => $value) {

                $preDateAccountTotal = $preDateRevenueTotal = 0;
                if(isset($value['pre_date'])){
                  $preDateAccountTotal = $value['pre_date']['AccountTotal'];
                  $preDateRevenueTotal = $value['pre_date']['RevenueTotal'];
                }

                $curDateAccountTotal = $curDateRevenueTotal = 0;
                if(isset($value['cur_date'])){
                  $curDateAccountTotal = $value['cur_date']['AccountTotal'];
                  $curDateRevenueTotal = $value['cur_date']['RevenueTotal'];
                }

                if($preDateAccountTotal != 0 && $curDateAccountTotal != 0) {

                  $percentAccount = ($curDateAccountTotal - $preDateAccountTotal) / $preDateAccountTotal;
                  $absPercentAccount = round($percentAccount * 100, 2);

                  if($percentAccount > 0) {
                    $percentAccount = '<span class="badge bg-green">' . $absPercentAccount. '%</span>';
                  } else if($percentAccount < 0) {
                    $percentAccount = '<span class="badge bg-red">' . $absPercentAccount. '%</span>';
                  } else {
                    $percentAccount = '<span class="badge bg-yellow">' . $absPercentAccount. '%</span>';
                  }

                } else
                  $percentAccount = 'N/A';

                if($preDateRevenueTotal != 0 && $curDateRevenueTotal != 0) {

                  $percentRevenue = ($curDateRevenueTotal - $preDateRevenueTotal) / $preDateRevenueTotal;
                  $absPercentRevenue = round(($percentRevenue * 100),2);

                  if($percentRevenue > 0) {
                    $percentRevenue = '<span class="badge bg-green">' . $absPercentRevenue. '%</span>';
                  } else if($percentRevenue < 0) {
                    $percentRevenue = '<span class="badge bg-red">' . $absPercentRevenue. '%</span>';
                  } else {
                    $percentRevenue = '<span class="badge bg-yellow">' . $absPercentRevenue. '%</span>';
                  }
                } else
                  $percentRevenue = 'N/A';

                $sumCurDateAccountTotal += $curDateAccountTotal;
                $sumCurDateRevenueTotal += $curDateRevenueTotal;
                $sumPreDateAccountTotal += $preDateAccountTotal;
                $sumPreDateRevenueTotal += $preDateRevenueTotal;

                // data for chart
                $dataChart['categories'][] = $value['cur_date']['GradeName'];
                $dataChart['series'][$curDate]['Account'][] = $curDateAccountTotal;
                $dataChart['series'][$curDate]['Revenue'][] = $curDateRevenueTotal;
                $dataChart['series'][$preDate]['Account'][] = $preDateAccountTotal;
                $dataChart['series'][$preDate]['Revenue'][] = $preDateRevenueTotal;
            ?>
            <tr>

              <td class="text-center" style="border-right: 1px solid gray;"><?php echo $value['cur_date']['GradeName'] ?></td>
              <td class="text-right past"><?php echo number_format($preDateAccountTotal); ?></td>
              <td class="text-right text-light-blue" ><strong><?php echo round(($preDateAccountTotal / $value['pre_date']['AccountTotalAllGrade']) * 100,2); ?>%</strong></td>
              <td class="text-right"><?php echo number_format($curDateAccountTotal); ?></td>
              <td class="text-right text-light-blue" ><strong><?php echo round(($curDateAccountTotal / $value['cur_date']['AccountTotalAllGrade']) * 100,2); ?>%</strong></td>
              <td class="text-right" style="border-right: 1px solid gray;">
                <?php echo $percentAccount; ?>
              </td>
              <td class="text-right past"><?php echo number_format($preDateRevenueTotal); ?></td>
              <td class="text-right text-light-blue"><strong><?php echo round(($preDateRevenueTotal / $value['pre_date']['RevenueTotalAllGrade']) * 100,2); ?>%</strong></td>
              <td class="text-right"><?php echo number_format($curDateRevenueTotal); ?></td>              
              <td class="text-right text-light-blue"><strong><?php echo round(($curDateRevenueTotal / $value['cur_date']['RevenueTotalAllGrade']) * 100,2); ?>%</strong></td>
              <td class="text-right" style="border-right: 1px solid gray;">
                <?php echo $percentRevenue; ?>
              </td>
              <td class="text-center"><a target="_blank" class="past" href="<?php echo base_url('index.php/paying_module/Compare/export_data/' . $fileExportPreDate . '_' . $grade ); ?>"><i class="fa fa-download"></i></a></td>
              <td class="text-center"><i><a target="_blank" href="<?php echo base_url('index.php/paying_module/Compare/export_data/' . $fileExportCurDate . '_' . $grade ); ?>"><i class="fa fa-download"></i></a></i></td>
              
            </tr>

            <?php
              }
            }
            ?>

            <tr style="font-weight: bold; border-top:2px solid gray">
              <td colspan="1" class="text-center" style="border-right: 1px solid gray;">Tổng</td>
              <td class="text-right past"><?php echo number_format($sumPreDateAccountTotal); ?></td>
              <td class="text-right"></td>
              <td class="text-right"><?php echo number_format($sumCurDateAccountTotal); ?></td>
              <td class="text-right"></td>
              <td class="text-right" style="border-right: 1px solid gray;">
                <?php 
                  $percentSumAccount = ($sumCurDateAccountTotal - $sumPreDateAccountTotal) / $sumPreDateAccountTotal;
                  $absPercentSumAccount = round(($percentSumAccount * 100),2) ;

                  if ($percentSumAccount > 0) {
                    echo '<span class="badge bg-green">' . $absPercentSumAccount . '%</span>';
                  } else if ($percentSumAccount < 0) {
                    echo '<span class="badge bg-red">' . $absPercentSumAccount . '%</span>';
                  } else {
                    echo '<span class="badge bg-yellow">0%</span>';
                  }
                ?>
              </td>
              <td class="text-right past"><?php echo number_format($sumPreDateRevenueTotal); ?></td>
              <td class="text-right"></td>
              <td class="text-right"><?php echo number_format($sumCurDateRevenueTotal); ?></td>
              <td class="text-right"></td>
              <td class="text-right" style="border-right: 1px solid gray;">
                <?php 
                  $percentSumRevenue = ($sumCurDateRevenueTotal - $sumPreDateRevenueTotal) / $sumPreDateRevenueTotal;
                  $absPercentSumRevenue = round(($percentSumRevenue * 100),2) ;

                  if ($percentSumRevenue > 0) {
                    echo '<span class="badge bg-green">' . $absPercentSumRevenue. '%</span>';
                  } else if ($percentSumRevenue < 0) {
                    echo '<span class="badge bg-red">' . $absPercentSumRevenue. '%</span>';
                  } else {
                    echo '<span class="badge bg-yellow">0%</span>';
                  }
                ?>
              </td>
              <td class="text-center"><i><a target="_blank" class="past" href="<?php echo base_url('index.php/paying_module/Compare/export_data/' . $fileExportPreDate ); ?>"><i class="fa fa-download"></i></a></i></td>
              <td class="text-center"><i><a target="_blank" href="<?php echo base_url('index.php/paying_module/Compare/export_data/' . $fileExportCurDate ); ?>"><i class="fa fa-download"></i></a></i></td>
            </tr>

          </table>
          </div>

          <script type="text/javascript">
            $(function () {
              $('#chart_user_<?php echo $key; ?>').highcharts({
                  colors: ['#dd4b39', '#00a65a'],
                  chart: {
                      type: 'column'
                  },
                  title: {
                      text: 'User'
                  },
                  xAxis: {
                      categories: [
                          <?php 
                            foreach ($dataChart['categories'] as $cate) {
                              echo "'".$cate."',";
                            } 
                          ?>                          
                      ],
                      crosshair: true
                  },
                  yAxis: {
                      min: 0,
                      title: {
                          text: 'Số lưọng user'
                      }
                  },
                  tooltip: {
                      headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                      pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name} : </td>' +
                          '<td style="padding:0"> <b>{point.y}</b></td></tr>',
                      footerFormat: '</table>',
                      shared: true,
                      useHTML: true
                  },
                  plotOptions: {
                      column: {
                          // pointPadding: 0.2,
                          borderWidth: 0
                      }
                  },
                  series: [
                  <?php 
                    foreach ($dataChart['series'] as $date => $rows) {
                      echo "{";
                      echo "name: '$date',";
                      echo "data: [";
                      
                      foreach ($rows['Account'] as $item) {
                        echo $item . ",";
                      }

                      echo "]";
                      echo "},";
                    }
                  ?>
                  ]
              });

              $('#chart_revenue_<?php echo $key; ?>').highcharts({
                  colors: ['#dd4b39', '#00a65a'],
                  chart: {
                      type: 'column'
                  },
                  title: {
                      text: 'Doanh Thu'
                  },
                  xAxis: {
                      categories: [
                          <?php 
                            foreach ($dataChart['categories'] as $cate) {
                              echo "'".$cate."',";
                            } 
                          ?>                          
                      ],
                      crosshair: true
                  },
                  yAxis: {
                      min: 0,
                      title: {
                          text: 'Doanh Thu'
                      }
                  },
                  tooltip: {
                      headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                      pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name} : </td>' +
                          '<td style="padding:0"> <b>{point.y}</b></td></tr>',
                      footerFormat: '</table>',
                      shared: true,
                      useHTML: true
                  },
                  plotOptions: {
                      column: {
                          // pointPadding: 0.2,
                          borderWidth: 0
                      }
                  },
                  series: [
                  <?php 
                    foreach ($dataChart['series'] as $date => $rows) {
                      echo "{";
                      echo "name: '$date',";
                      echo "data: [";
                      
                      foreach ($rows['Revenue'] as $item) {
                        echo $item . ",";
                      }

                      echo "]";
                      echo "},";
                    }
                  ?>
                  ]
              });
            });

          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->

      <?php if($content_paying_detail) echo $content_paying_detail; ?>
		
</div>
