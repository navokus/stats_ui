<?php 

  $curTotalAccount = $curTotalBehavior = 0;
  $curIncrTotalAccount = $curIncrTotalBehavior = 0;
  $curDecrTotalAccount = $curDecrTotalBehavior = 0;
  $curStabTotalAccount = $curStabTotalBehavior = 0;

  foreach ($aBehaviorDetailCompare as $grade => $v) {

    foreach ($aBehaviorDetailCompare[$grade][GRADE_INCR] as $k => $value) {

      if($k == 'pre_date'){
        // $preTotalAccount += $value['AccountTotal'];
        // $preTotalBehavior += $value['BehaviorTotal'];
      } else {
        $curIncrTotalAccount += $value['AccountTotal'];
        $curIncrTotalBehavior += $value['BehaviorTotal'];

        $curTotalAccount += $value['AccountTotal'];
        $curTotalBehavior += $value['BehaviorTotal'];
      }
        
    }

    foreach ($aBehaviorDetailCompare[$grade][GRADE_DECR] as $k => $value) {
      if($k == 'pre_date'){
        // $preTotalAccount += $value['AccountTotal'];
        // $preTotalBehavior += $value['BehaviorTotal'];
      } else {
        $curDecrTotalAccount += $value['AccountTotal'];
        $curDecrTotalBehavior += $value['BehaviorTotal'];

        $curTotalAccount += $value['AccountTotal'];
        $curTotalBehavior += $value['BehaviorTotal'];
      }
    }

    foreach ($aBehaviorDetailCompare[$grade][GRADE_STAB] as $k => $value) {
      if($k == 'pre_date'){
        // $preTotalAccount += $value['AccountTotal'];
        // $preTotalBehavior += $value['BehaviorTotal'];
      } else {
        $curStabTotalAccount += $value['AccountTotal'];
        $curStabTotalBehavior += $value['BehaviorTotal'];

        $curTotalAccount += $value['AccountTotal'];
        $curTotalBehavior += $value['BehaviorTotal'];
      }

    }
  }
?>

<div class="box box-solid box-success">
  <div class="box-header with-border">
    <h3 class="box-title">Nhóm chuyển dịch:  <b><?php echo $timeName . ' ' . $curDate ?></b></h3>
    <div class="btn-group pull-right">
      <a class="btn btn-danger btn-xs " href="#chart_chuyendich" data-toggle="tab" ><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Biểu đồ</a>
      <a class="btn btn-danger btn-xs " href="#data_chuyendich"  data-toggle="tab" ><span class="fa fa-database" aria-hidden="true"></span> Dữ liệu</a>
    </div>
  </div><!-- /.box-header -->
  <div class="box-body no-padding tab-content">     
      
      <div class="tab-pane fade" id="data_chuyendich">
      <table class="table table-bordered table-striped no-margin">
          <tr>
            <th colspan="2" class="text-center" style="border-right: 1px solid gray;" >Xếp loại</th>
            <th colspan="4" class="text-center" style="border-right: 1px solid gray;"><span class="badge bg-green">Tăng Trưởng </span></th>
            <th colspan="4" class="text-center" style="border-right: 1px solid gray;"><span class="badge bg-yellow">Ổn Định </span></th>
            <th colspan="4" class="text-center"><span class="badge bg-red">Suy Giảm </span></th>
          </tr>

          <tr>
            <th class="text-center">VIP</th>
            <th class="text-center" style="border-right: 1px solid gray;">Tổng User</th>
            <th colspan="2" class="text-center">User <span class="badge bg-green"><?php echo round(($curIncrTotalAccount /  $curTotalAccount * 100),2)  ?>%</span></th>
            <th colspan="2" class="text-center" style="border-right: 1px solid gray;">Hành vi <span class="badge bg-green"><?php echo round(($curIncrTotalBehavior /  $curTotalBehavior * 100),2)  ?>%</span></th>
            <th colspan="2" class="text-center">User <span class="badge bg-yellow"><?php echo round(($curStabTotalAccount /  $curTotalAccount * 100),2) ?>%</span></th>
            <th colspan="2" class="text-center" style="border-right: 1px solid gray;">Hành vi <span class="badge bg-yellow"><?php echo round(($curStabTotalBehavior /  $curTotalBehavior * 100),2)  ?>%</span></th>
            <th colspan="2" class="text-center">User <span class="badge bg-red"><?php echo round(($curDecrTotalAccount /  $curTotalAccount * 100),2)  ?>%</span></th>
            <th colspan="2" class="text-center" >Hành vi <span class="badge bg-red"><?php echo round(($curDecrTotalBehavior /  $curTotalBehavior * 100),2)  ?>%</span></th>
          </tr>


          <?php 
          foreach ($aBehaviorDetailCompare as $grade => $value) {
              // data for chart
              $dataChart['categories'][] = $value[GRADE_INCR]['cur_date']['ClassificationName'];
              $dataChart['series']['Tăng Trưởng']['Account'][] = $value[GRADE_INCR]['cur_date']['AccountTotal'];
              $dataChart['series']['Ổn Định']['Account'][] = $value[GRADE_STAB]['cur_date']['AccountTotal'];
              $dataChart['series']['Suy Giảm']['Account'][] = $value[GRADE_DECR]['cur_date']['AccountTotal'];

              $dataChart['series']['Tăng Trưởng']['Behavior'][] = $value[GRADE_INCR]['cur_date']['BehaviorTotal'];
              $dataChart['series']['Ổn Định']['Behavior'][] = $value[GRADE_STAB]['cur_date']['BehaviorTotal'];
              $dataChart['series']['Suy Giảm']['Behavior'][] = $value[GRADE_DECR]['cur_date']['BehaviorTotal'];
          ?>


          <tr>
            <th class="text-center"><?php echo $value[GRADE_INCR]['cur_date']['ClassificationName']; ?></th>
            <th class="text-center" style="border-right: 1px solid gray;"><?php echo number_format(($value[GRADE_INCR]['cur_date']['AccountTotal'] + $value[GRADE_STAB]['cur_date']['AccountTotal'] + $value[GRADE_DECR]['cur_date']['AccountTotal'])) ?></th>
            <td class="text-center">
              <?php  
                if($value[GRADE_INCR]['cur_date']['AccountTotal'] > 0) {
              ?>
                  <a tabindex="0" class="btn btn-link text-green no-padding no-margin" role="button" data-toggle="popover" data-trigger="focus"  
                title="Tăng trưởng <?php echo $grade; ?> : <?php echo number_format($value[GRADE_INCR]['cur_date']['AccountTotal']); ?>" 
                data-content="
                  <table class='table table-bordered table-striped no-padding no-margin'>
                  <?php 
                    foreach ($aBehaviorDetailGrade[$grade][GRADE_INCR] as $IdGrade => $total) {
                  ?>
                    <tr>
                      <th class='text-center'><?php echo ($IdGrade != '') ? $IdGrade : 'Mới'; ?></th>
                      <th class='text-center'><?php echo number_format($total); ?></th>
                    </tr>
                  <?php
                    }
                  ?>
                  </table>
                "><?php echo number_format($value[GRADE_INCR]['cur_date']['AccountTotal']); ?></a>
                <span class="glyphicon glyphicon-hand-left" aria-hidden="true"></span>
              <?php 
                } else {
              ?>
                  <span class="text-green">0</span>
              <?php
                }
              ?>
            </td>
            <td class="text-center" >
              <span class="text-blue"><strong><?php echo round($value[GRADE_INCR]['cur_date']['AccountTotal'] / $curIncrTotalAccount * 100, 2); ?>%</strong></span>
            </td>
            <td class="text-center">
              <span class="text-green"><?php echo number_format($value[GRADE_INCR]['cur_date']['BehaviorTotal']); ?></span>
            </td>
            <td class="text-center" style="border-right: 1px solid gray;">
              <span class="text-blue"><strong><?php echo round($value[GRADE_INCR]['cur_date']['BehaviorTotal'] / $curIncrTotalBehavior * 100, 2); ?>%</strong></span>
            </td>
            <td class="text-center">
              <span class="text-yellow"><?php echo number_format($value[GRADE_STAB]['cur_date']['AccountTotal']); ?></span>
            </td>
            <td class="text-center">
              <span class="text-blue"><strong><?php echo round($value[GRADE_STAB]['cur_date']['AccountTotal'] / $curStabTotalAccount * 100, 2); ?>%</strong></span></td>
            <td class="text-center" >
              <span class="text-yellow"><?php echo number_format($value[GRADE_STAB]['cur_date']['BehaviorTotal']); ?></span>
            </td>
            <td class="text-center" style="border-right: 1px solid gray;">
              <span class="text-blue"><strong><?php echo round($value[GRADE_STAB]['cur_date']['BehaviorTotal'] / $curStabTotalBehavior * 100, 2); ?>%</strong></span>
            </td>
            <td class="text-center">
              <?php  
                if($value[GRADE_DECR]['cur_date']['AccountTotal'] > 0) {
              ?>
                  <a tabindex="0" class=" btn btn-link text-red no-padding no-margin" role="button" data-toggle="popover" data-trigger="focus"
                title="Suy giảm <?php echo $grade; ?> : <?php echo number_format($value[GRADE_DECR]['cur_date']['AccountTotal']); ?>" 
                data-content="
                  <table class='table table-bordered table-striped no-padding no-margin'>
                    <?php 
                      foreach ($aBehaviorDetailGrade[$grade][GRADE_DECR] as $IdGrade => $total) {
                    ?>
                    <tr>
                      <th class='text-center'><?php echo ($IdGrade != '') ? $IdGrade : 'Mới'; ?></th>
                      <th class='text-center'><?php echo number_format($total); ?></th>
                    </tr>
                    <?php
                      }
                    ?>
                  </table>
                "><?php echo number_format($value[GRADE_DECR]['cur_date']['AccountTotal']); ?></a>
                  <span class="glyphicon glyphicon-hand-left" aria-hidden="true"></span> 
              <?php 
                } else {
              ?>
                  <span class="text-red">0</span>
              <?php
                }
              ?>
            </td>
            <td class="text-center">
              <span class="text-blue"><strong><?php echo round($value[GRADE_DECR]['cur_date']['AccountTotal'] / $curDecrTotalAccount * 100, 2); ?>%</strong></span>
            </td>
            <td class="text-center">
              <span class="text-red"><?php echo number_format($value[GRADE_DECR]['cur_date']['BehaviorTotal']); ?></span>
            </td>
            <td class="text-center">
              <span class="text-blue"><strong><?php echo round($value[GRADE_DECR]['cur_date']['BehaviorTotal'] / $curDecrTotalBehavior * 100, 2); ?>%</strong></span>
            </td>
          </tr>
         
          <?php 
          } 
          ?>                        
      </table>
      </div>

      <div class="row tab-pane active" id="chart_chuyendich" >
        <div class="col-md-6"  style="height: 400px">
          <div id="ct_chart_user_<?php echo $key; ?>"></div>
        </div>
        <div class="col-md-6" style="height: 400px">
          <div id="ct_chart_Behavior_<?php echo $key; ?>"></div>
        </div>
      </div> 


      <script type="text/javascript">
        $(function () {
          $('#ct_chart_user_<?php echo $key; ?>').highcharts({
              colors: ['#00a65a', '#f39c12', '#dd4b39'],
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
              {
                type: 'pie',
                colors: ['#00a65a', '#f39c12', '#dd4b39'],
                name: '',
                data: [
                    {
                      name: 'Tăng trưởng ',
                      y: <?php echo round(($curIncrTotalAccount /  $curTotalAccount * 100),2) ?>,
                      sliced: false,
                      selected: false
                    },
                    ['ổn định ',   <?php echo round(($curStabTotalAccount /  $curTotalAccount * 100),2) ?>],
                    ['Suy giảm ',   <?php echo round(($curDecrTotalAccount /  $curTotalAccount * 100),2) ?>],
                ],
                center: [250, 50],
                size: 120,
                showInLegend: false,
                tooltip: {
                    pointFormat: ' <b> {point.percentage:.1f}%</b>'
                },
                dataLabels: {
                    format: '<b> {point.percentage:.1f} %',
                    enabled: true,
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
              },
              <?php 
                foreach ($dataChart['series'] as $status => $rows) {
                  echo "{";
                  echo "name: '$status',";
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

          $('#ct_chart_Behavior_<?php echo $key; ?>').highcharts({
              colors: ['#00a65a', '#f39c12', '#dd4b39'],
              chart: {
                  type: 'column'
              },
              title: {
                  text: 'Hành Vi'
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
              {
                type: 'pie',
                colors: ['#00a65a', '#f39c12', '#dd4b39'],
                name: '',
                data: [
                    {
                      name: 'Tăng trưởng ',
                      y: <?php echo round(($curIncrTotalBehavior /  $curTotalBehavior * 100),2) ?>,
                      sliced: false,
                      selected: false
                    },
                    ['ổn định ',   <?php echo round(($curStabTotalBehavior /  $curTotalBehavior * 100),2)  ?>],
                    ['Suy giảm ',   <?php echo round(($curDecrTotalBehavior /  $curTotalBehavior * 100),2) ?>],
                ],
                center: [260, 50],
                size: 120,
                showInLegend: false,
                tooltip: {
                    pointFormat: ' <b>{point.percentage:.1f}%</b>'
                },
                dataLabels: {
                    format: '<b> {point.percentage:.1f} %',
                    enabled: true,
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
              },
              <?php 
                foreach ($dataChart['series'] as $status => $rows) {
                  echo "{";
                  echo "name: '$status',";
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