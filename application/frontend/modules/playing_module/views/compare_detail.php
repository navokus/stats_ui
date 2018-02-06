<?php 

  $curTotalAccount = $curTotalPlaytime = 0;
  $curIncrTotalAccount = $curIncrTotalPlaytime = 0;
  $curDecrTotalAccount = $curDecrTotalPlaytime = 0;
  $curStabTotalAccount = $curStabTotalPlaytime = 0;

  foreach ($aPlayDetailCompare as $grade => $v) {

    foreach ($aPlayDetailCompare[$grade][GRADE_INCR] as $k => $value) {

      if($k == 'pre_date'){
        // $preTotalAccount += $value['AccountTotal'];
        // $preTotalPlaytime += $value['PlaytimeTotal'];
      } else {
        $curIncrTotalAccount += $value['AccountTotal'];
        $curIncrTotalPlaytime += $value['PlaytimeTotal'];

        $curTotalAccount += $value['AccountTotal'];
        $curTotalPlaytime += $value['PlaytimeTotal'];
      }
        
    }

    foreach ($aPlayDetailCompare[$grade][GRADE_DECR] as $k => $value) {
      if($k == 'pre_date'){
        // $preTotalAccount += $value['AccountTotal'];
        // $preTotalPlaytime += $value['PlaytimeTotal'];
      } else {
        $curDecrTotalAccount += $value['AccountTotal'];
        $curDecrTotalPlaytime += $value['PlaytimeTotal'];

        $curTotalAccount += $value['AccountTotal'];
        $curTotalPlaytime += $value['PlaytimeTotal'];
      }
    }

    foreach ($aPlayDetailCompare[$grade][GRADE_STAB] as $k => $value) {
      if($k == 'pre_date'){
        // $preTotalAccount += $value['AccountTotal'];
        // $preTotalPlaytime += $value['PlaytimeTotal'];
      } else {
        $curStabTotalAccount += $value['AccountTotal'];
        $curStabTotalPlaytime += $value['PlaytimeTotal'];

        $curTotalAccount += $value['AccountTotal'];
        $curTotalPlaytime += $value['PlaytimeTotal'];
      }

    }
  }
?>

<div class="box box-solid box-success">
  <div class="box-header with-border">
    <h3 class="box-title">Nhóm chuyển dịch: <b><?php echo $timeName . ' ' . $curDate ; ?></b></h3>
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
            <th colspan="2" class="text-center" style="border-right: 1px solid gray;">Thời Gian Chơi <span class="badge bg-green"><?php echo round(($curIncrTotalPlaytime /  $curTotalPlaytime * 100),2)  ?>%</span></th>
            <th colspan="2" class="text-center">User <span class="badge bg-yellow"><?php echo round(($curStabTotalAccount /  $curTotalAccount * 100),2) ?>%</span></th>
            <th colspan="2" class="text-center" style="border-right: 1px solid gray;">Thời Gian Chơi <span class="badge bg-yellow"><?php echo round(($curStabTotalPlaytime /  $curTotalPlaytime * 100),2)  ?>%</span></th>
            <th colspan="2" class="text-center">User <span class="badge bg-red"><?php echo round(($curDecrTotalAccount /  $curTotalAccount * 100),2)  ?>%</span></th>
            <th colspan="2" class="text-center" >Thời Gian Chơi <span class="badge bg-red"><?php echo round(($curDecrTotalPlaytime /  $curTotalPlaytime * 100),2)  ?>%</span></th>
          </tr>
          
          <?php 
          foreach ($aPlayDetailCompare as $grade => $value) {
              // data for chart
              $dataChart['categories'][] = $value[GRADE_INCR]['cur_date']['GradeName'];
              $dataChart['series']['Tăng Trưởng']['Account'][] = $value[GRADE_INCR]['cur_date']['AccountTotal'];
              $dataChart['series']['Ổn Định']['Account'][] = $value[GRADE_STAB]['cur_date']['AccountTotal'];
              $dataChart['series']['Suy Giảm']['Account'][] = $value[GRADE_DECR]['cur_date']['AccountTotal'];

              $dataChart['series']['Tăng Trưởng']['Playtime'][] = $value[GRADE_INCR]['cur_date']['PlaytimeTotal'];
              $dataChart['series']['Ổn Định']['Playtime'][] = $value[GRADE_STAB]['cur_date']['PlaytimeTotal'];
              $dataChart['series']['Suy Giảm']['Playtime'][] = $value[GRADE_DECR]['cur_date']['PlaytimeTotal'];
          ?>
          <tr>
            <th class="text-center"><?php echo $value[GRADE_INCR]['cur_date']['GradeName']; ?></th>
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
                    foreach ($aPlayDetailGrade[$grade][GRADE_INCR] as $IdGrade => $total) {
                  ?>
                    <tr>
                      <th class='text-center'><?php echo ($IdGrade != '') ? $IdGrade : 'Mới'; ?></th>
                      <th class='text-center'><?php echo number_format($total); ?></th>
                    </tr>
                  <?php
                    }
                  ?>
                  </table>
                "><?php echo number_format($value[GRADE_INCR]['cur_date']['AccountTotal']); ?> <span class="fa fa-question-circle" aria-hidden="true"></span></a>

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
              <span class="text-green"><?php echo number_format($value[GRADE_INCR]['cur_date']['PlaytimeTotal']); ?></span>
            </td>
            <td class="text-center" style="border-right: 1px solid gray;">
              <span class="text-blue"><strong><?php echo round($value[GRADE_INCR]['cur_date']['PlaytimeTotal'] / $curIncrTotalPlaytime * 100, 2); ?>%</strong></span>
            </td>
            <td class="text-center">
              <span class="text-yellow"><?php echo number_format($value[GRADE_STAB]['cur_date']['AccountTotal']); ?></span>
            </td>
            <td class="text-center">
              <span class="text-blue"><strong><?php echo round($value[GRADE_STAB]['cur_date']['AccountTotal'] / $curStabTotalAccount * 100, 2); ?>%</strong></span></td>
            <td class="text-center" >
              <span class="text-yellow"><?php echo number_format($value[GRADE_STAB]['cur_date']['PlaytimeTotal']); ?></span>
            </td>
            <td class="text-center" style="border-right: 1px solid gray;">
              <span class="text-blue"><strong><?php echo round($value[GRADE_STAB]['cur_date']['PlaytimeTotal'] / $curStabTotalPlaytime * 100, 2); ?>%</strong></span>
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
                      foreach ($aPlayDetailGrade[$grade][GRADE_DECR] as $IdGrade => $total) {
                    ?>
                    <tr>
                      <th class='text-center'><?php echo ($IdGrade != '') ? $IdGrade : 'Mới'; ?></th>
                      <th class='text-center'><?php echo number_format($total); ?></th>
                    </tr>
                    <?php
                      }
                    ?>
                  </table>
                "><?php echo number_format($value[GRADE_DECR]['cur_date']['AccountTotal']); ?> <span class="fa fa-question-circle" aria-hidden="true"></span></a>
                  
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
              <span class="text-red"><?php echo number_format($value[GRADE_DECR]['cur_date']['PlaytimeTotal']); ?></span>
            </td>
            <td class="text-center">
              <span class="text-blue"><strong><?php echo round($value[GRADE_DECR]['cur_date']['PlaytimeTotal'] / $curDecrTotalPlaytime * 100, 2); ?>%</strong></span>
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
          <div id="ct_chart_Playtime_<?php echo $key; ?>"></div>
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
                  
                  foreach ($rows['Account'] as $item) {
                    echo $item . ",";
                  }

                  echo "]";
                  echo "},";
                }
              ?>
              ]
          });

          $('#ct_chart_Playtime_<?php echo $key; ?>').highcharts({
              colors: ['#00a65a', '#f39c12', '#dd4b39'],
              chart: {
                  type: 'column'
              },
              title: {
                  text: 'Thời Gian Chơi'
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
                      text: 'Thời Gian Chơi'
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
                      y: <?php echo round(($curIncrTotalPlaytime /  $curTotalPlaytime * 100),2) ?>,
                      sliced: false,
                      selected: false
                    },
                    ['ổn định ',   <?php echo round(($curStabTotalPlaytime /  $curTotalPlaytime * 100),2)  ?>],
                    ['Suy giảm ',   <?php echo round(($curDecrTotalPlaytime /  $curTotalPlaytime * 100),2) ?>],
                ],
                center: [250, 50],
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
                  
                  foreach ($rows['Playtime'] as $item) {
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