<?php 

  $curTotalAccount = $curTotalRevenue = 0;
  $curIncrTotalAccount = $curIncrTotalRevenue = 0;
  $curDecrTotalAccount = $curDecrTotalRevenue = 0;
  $curStabTotalAccount = $curStabTotalRevenue = 0;

  foreach ($aPayDetailCompare as $grade => $v) {

    foreach ($aPayDetailCompare[$grade][GRADE_INCR] as $k => $value) {

      if($k == 'pre_date'){
        // $preTotalAccount += $value['AccountTotal'];
        // $preTotalRevenue += $value['RevenueTotal'];
      } else {
        $curIncrTotalAccount += $value['AccountTotal'];
        $curIncrTotalRevenue += $value['RevenueTotal'];

        $curTotalAccount += $value['AccountTotal'];
        $curTotalRevenue += $value['RevenueTotal'];
      }
        
    }

    foreach ($aPayDetailCompare[$grade][GRADE_DECR] as $k => $value) {
      if($k == 'pre_date'){
        // $preTotalAccount += $value['AccountTotal'];
        // $preTotalRevenue += $value['RevenueTotal'];
      } else {
        $curDecrTotalAccount += $value['AccountTotal'];
        $curDecrTotalRevenue += $value['RevenueTotal'];

        $curTotalAccount += $value['AccountTotal'];
        $curTotalRevenue += $value['RevenueTotal'];
      }
    }

    foreach ($aPayDetailCompare[$grade][GRADE_STAB] as $k => $value) {
      if($k == 'pre_date'){
        // $preTotalAccount += $value['AccountTotal'];
        // $preTotalRevenue += $value['RevenueTotal'];
      } else {
        $curStabTotalAccount += $value['AccountTotal'];
        $curStabTotalRevenue += $value['RevenueTotal'];

        $curTotalAccount += $value['AccountTotal'];
        $curTotalRevenue += $value['RevenueTotal'];
      }

    }
  }
?>

<div class="box box-solid box-success">
  <div class="box-header with-border">
    <h3 class="box-title">Nhóm chuyển dịch: <b> <?php echo $timeName . ' ' . $curDate; ?></b> </h3>
    <div class="btn-group pull-right">
      <a class="btn btn-danger btn-xs " href="#chart_chuyendich" data-toggle="tab" ><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Biểu đồ</a>
      <a class="btn btn-danger btn-xs " href="#data_chuyendich"  data-toggle="tab" ><span class="fa fa-database" aria-hidden="true"></span> Dữ liệu</a>
    </div>
  </div>
  <div class="box-body no-padding tab-content">

    <div class="row tab-pane active" id="chart_chuyendich" >
      <div class="col-md-6"  style="height: 400px">
        <div id="ct_chart_user_<?php echo $key; ?>"></div>
      </div>
      <div class="col-md-6" style="height: 400px">
        <div id="ct_chart_revenue_<?php echo $key; ?>"></div>
      </div>
    </div>

    <div class="tab-pane fade" id="data_chuyendich">
      <table class="table table-bordered table-striped no-margin">
          <tr>
            <th colspan="2" class="text-center" style="border-right: 1px solid gray;" >DS VIP</th>
            <th colspan="4" class="text-center" style="border-right: 1px solid gray;"><span class="badge bg-green">Tăng Trưởng </span></th>
            <th colspan="4" class="text-center" style="border-right: 1px solid gray;"><span class="badge bg-yellow">Ổn Định </span></th>
            <th colspan="4" class="text-center"><span class="badge bg-red">Suy Giảm </span></th>
          </tr>

          <tr>
            <th class="text-center">VIP</th>
            <th class="text-center" style="border-right: 1px solid gray;">Tổng User</th>
            <th colspan="2" class="text-center">User <span class="badge bg-green"><?php echo round(($curIncrTotalAccount /  $curTotalAccount * 100),2)  ?>%</span></th>
            <th colspan="2" class="text-center" style="border-right: 1px solid gray;">Doanh Thu <span class="badge bg-green"><?php echo round(($curIncrTotalRevenue /  $curTotalRevenue * 100),2)  ?>%</span></th>
            <th colspan="2" class="text-center">User <span class="badge bg-yellow"><?php echo round(($curStabTotalAccount /  $curTotalAccount * 100),2) ?>%</span></th>
            <th colspan="2" class="text-center" style="border-right: 1px solid gray;">Doanh Thu <span class="badge bg-yellow"><?php echo round(($curStabTotalRevenue /  $curTotalRevenue * 100),2)  ?>%</span></th>
            <th colspan="2" class="text-center">User <span class="badge bg-red"><?php echo round(($curDecrTotalAccount /  $curTotalAccount * 100),2)  ?>%</span></th>
            <th colspan="2" class="text-center" >Doanh Thu <span class="badge bg-red"><?php echo round(($curDecrTotalRevenue /  $curTotalRevenue * 100),2)  ?>%</span></th>
          </tr>

          <?php 
          foreach ($aPayDetailCompare as $grade => $value) {
              // data for chart
              $dataChart['categories'][] = $value[GRADE_INCR]['cur_date']['GradeName'];
              $dataChart['series']['Tăng Trưởng']['Account'][] = $value[GRADE_INCR]['cur_date']['AccountTotal'];
              $dataChart['series']['Ổn Định']['Account'][] = $value[GRADE_STAB]['cur_date']['AccountTotal'];
              $dataChart['series']['Suy Giảm']['Account'][] = $value[GRADE_DECR]['cur_date']['AccountTotal'];

              $dataChart['series']['Tăng Trưởng']['Revenue'][] = $value[GRADE_INCR]['cur_date']['RevenueTotal'];
              $dataChart['series']['Ổn Định']['Revenue'][] = $value[GRADE_STAB]['cur_date']['RevenueTotal'];
              $dataChart['series']['Suy Giảm']['Revenue'][] = $value[GRADE_DECR]['cur_date']['RevenueTotal'];
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
                  	foreach ($aPayDetailGrade[$grade][GRADE_INCR] as $IdGrade => $total) {
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
              <span class="text-green"><?php echo number_format($value[GRADE_INCR]['cur_date']['RevenueTotal']); ?></span>
            </td>
            <td class="text-center" style="border-right: 1px solid gray;">
              <span class="text-blue"><strong><?php echo round($value[GRADE_INCR]['cur_date']['RevenueTotal'] / $curIncrTotalRevenue * 100, 2); ?>%</strong></span>
            </td>
            <td class="text-center">
              <span class="text-yellow"><?php echo number_format($value[GRADE_STAB]['cur_date']['AccountTotal']); ?></span>
            </td>
            <td class="text-center">
              <span class="text-blue"><strong><?php echo round($value[GRADE_STAB]['cur_date']['AccountTotal'] / $curStabTotalAccount * 100, 2); ?>%</strong></span></td>
            <td class="text-center" >
              <span class="text-yellow"><?php echo number_format($value[GRADE_STAB]['cur_date']['RevenueTotal']); ?></span>
            </td>
            <td class="text-center" style="border-right: 1px solid gray;">
              <span class="text-blue"><strong><?php echo round($value[GRADE_STAB]['cur_date']['RevenueTotal'] / $curStabTotalRevenue * 100, 2); ?>%</strong></span>
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
                  		foreach ($aPayDetailGrade[$grade][GRADE_DECR] as $IdGrade => $total) {
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
              <span class="text-red"><?php echo number_format($value[GRADE_DECR]['cur_date']['RevenueTotal']); ?></span>
            </td>
            <td class="text-center">
              <span class="text-blue"><strong><?php echo round($value[GRADE_DECR]['cur_date']['RevenueTotal'] / $curDecrTotalRevenue * 100, 2); ?>%</strong></span>
            </td>
          </tr>
          <?php 
          } 
          ?>                        
      </table>
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
                        y: <?php echo round(($curIncrTotalAccount /  $curTotalAccount),2) * 100 ?>,
                        sliced: false,
                        selected: false
                      },
                      ['Ổn định ',   <?php echo round(($curStabTotalAccount /  $curTotalAccount),2) * 100 ?>],
                      ['Suy giảm ',   <?php echo round(($curDecrTotalAccount /  $curTotalAccount),2) * 100 ?>],
                      
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
                  echo "type: 'column',";
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

          $('#ct_chart_revenue_<?php echo $key; ?>').highcharts({
              colors: ['#00a65a', '#f39c12', '#dd4b39'],
              chart: {
                  type: 'column'
              },
              title: {
                  text: 'Doanh Thu'
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
                      text: 'Doanh thu'
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
                        y: <?php echo round(($curIncrTotalRevenue /  $curTotalRevenue * 100),2) ?>,
                        sliced: false,
                        selected: false
                      },
                      ['Ổn định ',   <?php echo round(($curStabTotalRevenue /  $curTotalRevenue * 100),2)  ?>],
                      ['Suy giảm ',   <?php echo round(($curDecrTotalRevenue /  $curTotalRevenue * 100),2) ?>],
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
