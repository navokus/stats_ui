
<div class="col-md-12">
    
        <div class="box box-solid box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Cơ Cấu Hành Vi Theo Nhóm</h3>
            <div class="btn-group pull-right ">
              <a class="btn btn-danger btn-xs " href="#chart" data-toggle="tab" ><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Biểu đồ</a>
              <a class="btn btn-danger btn-xs " href="#data"  data-toggle="tab" ><span class="fa fa-database" aria-hidden="true"></span> Dữ liệu</a>
            </div>
          </div><!-- /.box-header -->
          <div class="box-body no-padding tab-content">

          <div class="tab-pane fade" id="data">
          <table class="table table-bordered table-striped no-margin">
            <tr>
              <th rowspan="2" style="border-right: 1px solid gray;vertical-align: middle;" class="text-center" >
                
                <?php 
                $table_mucnap = '<tr><th>Cấp</th><th>TG chơi x 30% + Chi Trả x 70%</th></tr>';
                foreach ($aBehaviorCompare as $grade => $value) {
                  $table_mucnap .= '<tr>';
                  $table_mucnap .= '<td>' . $value['cur_date']['ClassificationName'] . '</td>';
                  if($value['cur_date']['ToValue'] < 1000000000) {
                    $table_mucnap .= '<td>' . number_format($value['cur_date']['FromValue']) . ' - ' . number_format($value['cur_date']['ToValue']) . '</td>'; 
                  } else {
                    $table_mucnap .= '<td> > '.number_format($value['cur_date']['FromValue']) . '</td>'; 
                  }
                  $table_mucnap .= '</tr>';
                }
                ?>
                <a tabindex="0" class="btn btn-link no-padding no-margin text-black" role="button" data-toggle="popover" data-trigger="focus" title="" 
                data-content="<table class='table table-bordered table-striped no-padding no-margin'><?php echo $table_mucnap ?></table>" data-original-title="">Cấp <i class="fa fa-question-circle"></i></a>
              </th>
              <th colspan="5" class="text-center" style="border-right: 1px solid gray;">User</th>
              <th colspan="5" class="text-center" style="border-right: 1px solid gray;">Hành vi</th>
              <th colspan="2" class="text-center" >Export</th>
            </tr>

            <tr>
              <th class="text-center past" colspan="2"><?php echo $times ?> <?php echo $preDate ?></th>
              <th class="text-center" colspan="2"><?php echo $times ?> <?php echo $curDate ?></th>
              <th class="text-center" style="border-right: 1px solid gray;">%</th>
              <th class="text-center past" colspan="2"><?php echo $times ?> <?php echo $preDate ?></th>
              <th class="text-center" colspan="2"><?php echo $times ?> <?php echo $curDate ?></th>
              <th class="text-center" style="border-right: 1px solid gray;">%</th>
              <th class="text-center past" ><?php echo $times ?> <?php echo $preDate ?></th>
              <th class="text-center" ><?php echo $times ?> <?php echo $curDate ?></th>
            </tr>

            <?php 

            if ($aBehaviorCompare) {
              $sumCurDateAccountTotal = 0 ;
              $sumCurDateBehaviorTotal = 0 ;
              $sumPreDateAccountTotal = 0 ;
              $sumPreDateBehaviorTotal = 0 ;

              foreach ($aBehaviorCompare as $grade => $value) {

                $preDateAccountTotal = $preDateBehaviorTotal = 0;
                if(isset($value['pre_date'])){
                  $preDateAccountTotal = $value['pre_date']['AccountTotal'];
                  $preDateBehaviorTotal = $value['pre_date']['BehaviorTotal'];
                }

                $curDateAccountTotal = $curDateBehaviorTotal = 0;
                if(isset($value['cur_date'])){
                  $curDateAccountTotal = $value['cur_date']['AccountTotal'];
                  $curDateBehaviorTotal = $value['cur_date']['BehaviorTotal'];
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

                if($preDateBehaviorTotal != 0 && $curDateBehaviorTotal != 0) {

                  $percentBehavior = ($curDateBehaviorTotal - $preDateBehaviorTotal) / $preDateBehaviorTotal;
                  $absPercentBehavior = round(($percentBehavior * 100),2);

                  if($percentBehavior > 0) {
                    $percentBehavior = '<span class="badge bg-green">' . $absPercentBehavior. '%</span>';
                  } else if($percentBehavior < 0) {
                    $percentBehavior = '<span class="badge bg-red">' . $absPercentBehavior. '%</span>';
                  } else {
                    $percentBehavior = '<span class="badge bg-yellow">' . $absPercentBehavior. '%</span>';
                  }
                } else
                  $percentBehavior = 'N/A';

                $sumCurDateAccountTotal += $curDateAccountTotal;
                $sumCurDateBehaviorTotal += $curDateBehaviorTotal;
                $sumPreDateAccountTotal += $preDateAccountTotal;
                $sumPreDateBehaviorTotal += $preDateBehaviorTotal;

                // data for chart
                $dataChart['categories'][] = $value['cur_date']['ClassificationName'];
                $dataChart['series'][$curDate]['Account'][] = $curDateAccountTotal;
                $dataChart['series'][$curDate]['Behavior'][] = $curDateBehaviorTotal;
                $dataChart['series'][$preDate]['Account'][] = $preDateAccountTotal;
                $dataChart['series'][$preDate]['Behavior'][] = $preDateBehaviorTotal;
            ?>
            <tr>
              <td class="text-center" style="border-right: 1px solid gray;"><?php echo $value['cur_date']['ClassificationName'] ?></td>
              <td class="text-center past"><?php echo number_format($preDateAccountTotal); ?></td>
              <td class="text-center text-light-blue" ><strong><?php echo round(($preDateAccountTotal / $value['pre_date']['AccountTotalAllClassification']) * 100,2); ?>%</strong></td>
              <td class="text-center"><?php echo number_format($curDateAccountTotal); ?></td>
              <td class="text-center text-light-blue" ><strong><?php echo round(($curDateAccountTotal / $value['cur_date']['AccountTotalAllClassification']) * 100,2); ?>%</strong></td>
              <td class="text-center" style="border-right: 1px solid gray;">
                <?php echo $percentAccount; ?>
              </td>
              <td class="text-center past"><?php echo number_format($preDateBehaviorTotal); ?></td>
              <td class="text-center text-light-blue"><strong><?php echo round(($preDateBehaviorTotal / $value['pre_date']['BehaviorTotalAllClassification']) * 100,2); ?>%</strong></td>
              <td class="text-center"><?php echo number_format($curDateBehaviorTotal); ?></td>              
              <td class="text-center text-light-blue"><strong><?php echo round(($curDateBehaviorTotal / $value['cur_date']['BehaviorTotalAllClassification']) * 100,2); ?>%</strong></td>
              <td class="text-center" style="border-right: 1px solid gray;">
                <?php echo $percentBehavior; ?>
              </td>
              <td class="text-center"><i><a target="_blank" class="past" href="<?php echo base_url('index.php/behavior_module/Compare/export_data/' . $fileExportPreDate . '_' . $grade ); ?>"><i class="fa fa-download"></i></a></i></td>
              <td class="text-center"><i><a target="_blank" href="<?php echo base_url('index.php/behavior_module/Compare/export_data/' . $fileExportCurDate . '_' . $grade ); ?>"><i class="fa fa-download"></i></a></i></td>
            </tr>

            <?php
              }
            }
            ?>

            <tr style="font-weight: bold; border-top:2px solid gray">
              <td class="text-center" style="border-right: 1px solid gray;">Tổng</td>
              <td class="text-center past"><?php echo number_format($sumPreDateAccountTotal); ?></td>
              <td class="text-center"></td>
              <td class="text-center"><?php echo number_format($sumCurDateAccountTotal); ?></td>
              <td class="text-center"></td>
              <td class="text-center" style="border-right: 1px solid gray;">
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
              <td class="text-center past"><?php echo number_format($sumPreDateBehaviorTotal); ?></td>
              <td class="text-center"></td>
              <td class="text-center"><?php echo number_format($sumCurDateBehaviorTotal); ?></td>
              <td class="text-center"></td>
              <td class="text-center" style="border-right: 1px solid gray;">
                <?php 
                  $percentSumBehavior = ($sumCurDateBehaviorTotal - $sumPreDateBehaviorTotal) / $sumPreDateBehaviorTotal;
                  $absPercentSumBehavior = round(($percentSumBehavior * 100),2) ;

                  if ($percentSumBehavior > 0) {
                    echo '<span class="badge bg-green">' . $absPercentSumBehavior. '%</span>';
                  } else if ($percentSumBehavior < 0) {
                    echo '<span class="badge bg-red">' . $absPercentSumBehavior. '%</span>';
                  } else {
                    echo '<span class="badge bg-yellow">0%</span>';
                  }
                ?>
              </td>
              <td class="text-center"><i><a target="_blank" class="past" href="<?php echo base_url('index.php/behavior_module/Compare/export_data/' . $fileExportPreDate ); ?>"><i class="fa fa-download"></i></a></i></td>
              <td class="text-center"><i><a target="_blank" href="<?php echo base_url('index.php/behavior_module/Compare/export_data/' . $fileExportCurDate ); ?>"><i class="fa fa-download"></i></a></i></td>
            </tr>

          </table>
          </div>


          
          <div class="row tab-pane active" id="chart">
            <div class="col-md-6"  style="height: 400px">
              <div id="chart_user_<?php echo $key; ?>"></div>
            </div>
            <div class="col-md-6" style="height: 400px">
              <div id="chart_Behavior_<?php echo $key; ?>"></div>
            </div>
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
                  // subtitle: {
                  //     text: 'Source: WorldClimate.com'
                  // },
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

              $('#chart_Behavior_<?php echo $key; ?>').highcharts({
                  colors: ['#dd4b39', '#00a65a'],
                  chart: {
                      type: 'column'
                  },
                  title: {
                      text: 'Hành Vi'
                  },
                  // subtitle: {
                  //     text: 'Source: WorldClimate.com'
                  // },
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
                          text: 'Hành Vi'
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
                      
                      foreach ($rows['Behavior'] as $item) {
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

      <?php if($content_behavior_detail) echo $content_behavior_detail; ?>
		
</div>
