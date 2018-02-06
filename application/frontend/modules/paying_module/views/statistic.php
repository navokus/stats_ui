
<div class="col-md-12">
    
        <div class="box box-solid box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Thống Kê Theo Nhóm Đối Tượng</h3>
            <div class="btn-group pull-right ">
              <a class="btn btn-danger btn-xs " href="#chart" data-toggle="tab" ><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Biểu đồ</a>
              <a class="btn btn-danger btn-xs " href="#percent" data-toggle="tab" ><span class="fa fa-pie-chart" aria-hidden="true"></span> Tỷ lệ</a>
              <a class="btn btn-danger btn-xs " href="#data"  data-toggle="tab" ><span class="fa fa-database" aria-hidden="true"></span> Dữ liệu</a>
            </div>
          </div><!-- /.box-header -->
          <div class="box-body no-padding tab-content">

            <div class="row tab-pane active" id="chart">
              <div class="col-md-6"  style="height: 400px">
                <div id="chart_user_<?php echo $key; ?>"></div>
              </div>
              <div class="col-md-6" style="height: 400px">
                <div id="chart_revenue_<?php echo $key; ?>"></div>
              </div>
            </div>

            <div class="row tab-pane fade" id="percent">
              <div class="col-md-6"  style="height: 400px">
                <div id="percent_user_<?php echo $key; ?>"></div>
              </div>
              <div class="col-md-6" style="height: 400px">
                <div id="percent_revenue_<?php echo $key; ?>"></div>
              </div>
            </div>

            <div class="tab-pane fade" id="data">
    				  <table class="table table-bordered table-striped no-margin">
                <tr>
                  <th rowspan="2" style="border-right: 1px solid gray; vertical-align: middle;" class="text-center">Đối Tượng</th>
                  <th colspan="2" class="text-center" style="border-right: 1px solid gray;">User</th>
                  <th colspan="2" class="text-center" >Doanh Thu</th>
                </tr>
                <tr>
                  <th class="text-center">Tổng User</th>
                  <th class="text-center" style="border-right: 1px solid gray;">% User</th>
                  <th class="text-center">Tổng Doanh Thu</th>
                  <th class="text-center">% Doanh Thu</th>
                </tr>

                <?php 
                  if ($aGrades) {
                    foreach ($aGrades as $value) {
                  ?>
                  <tr>
                    <th class="text-center" style="border-right: 1px solid gray;"><?php echo $value['GradeName'] ?></th>
                    <td class="text-center"><?php echo number_format($value['AccountTotal']) ?></td>
                    <td class="text-center text-light-blue" style="border-right: 1px solid gray;"><b><?php echo round(($value['AccountTotal'] / $value['AccountTotalAllGrade']) * 100 ,2) ?>%</b></td>
                    <td class="text-center"><?php echo number_format($value['RevenueTotal']) ?></td>
                    <td class="text-center text-light-blue"><b><?php echo round(($value['RevenueTotal'] / $value['RevenueTotalAllGrade']) * 100 ,2) ?>%</b></td>
                  </tr>
                  <?php
                      // data draw chart
                      $dataChart['series']['Account'][$value['GradeName']] = round(($value['AccountTotal'] / $value['AccountTotalAllGrade']) * 100,2);
                      $dataChart['series']['Revenue'][$value['GradeName']] = round(($value['RevenueTotal'] / $value['RevenueTotalAllGrade']) * 100,2);
                    }
                  }
                ?>
              </table>
            </div>

            <script type="text/javascript">
              $(function () {

                $('#chart_user_<?php echo $key; ?>').highcharts({
                  colors: ['#f39c12'],
                  chart: {
                      type: 'column'
                  },
                  title: {
                      text: 'User'
                  },
                  xAxis: {
                      categories: [
                          <?php 
                            foreach ($aGrades as $value) {
                              echo "'". $value['GradeName'] ." ',";
                            } 
                          ?>                          
                      ],
                      crosshair: true
                  },
                  yAxis: {
                      min: 0,
                      title: {
                          text: 'Số lượng user'
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
                          // pointPadding : 0.2,
                          borderWidth: 0
                      }
                  },
                  series: [
                  {
                    type: 'column',
                    name: 'User',
                    showInLegend: true,
                    data: [
                    <?php 
                    foreach ($aGrades as $value) {
                      echo $value['AccountTotal'] . ",";
                    }
                    ?>
                    ],
                  },
                  ]
                });

                $('#chart_revenue_<?php echo $key; ?>').highcharts({
                  colors: ['#f39c12'],
                  chart: {
                      type: 'column'
                  },
                  title: {
                      text: 'Doanh Thu'
                  },
                  xAxis: {
                      categories: [
                          <?php 
                            foreach ($aGrades as $value) {
                              echo "'". $value['GradeName'] ." ',";
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
                   
                          borderWidth: 0
                      }
                  },
                  series: [
                  {
                    type: 'column',
                    name: 'Doanh thu',
                    showInLegend: true,
                    data: [
                    <?php 
                    foreach ($aGrades as $value) {
                      echo $value['RevenueTotal'] . ",";
                    }
                    ?>
                    ],
                  },
                  ]
                });

                // percent user
                $('#percent_user_<?php echo $key; ?>').highcharts({
                  title: {
                      text: 'User'
                  },
                  series: [{
                      type: 'pie',
                      colors: ['#f39c12', '#dd4b39', '#00a65a', '#3c8dbc', '#00c0ef', '#D81B60', '#605ca8', '#f56954', '#001F3F', '#39CCCC'],
                      name: '',
                      data: [
                          
                          <?php 
                            foreach ($dataChart['series']['Account'] as $grade => $value) {
                              echo "['$grade',  $value ],";
                            }
                          ?>
                      ],
                      // center: [250, 50],
                      size: 180,
                      showInLegend: true,
                      tooltip: {
                          pointFormat: ' <b> {point.percentage:.1f}%</b>'
                      },
                      dataLabels: {
                          format: ' {point.percentage:.1f} %',
                          enabled: true,
                          style: {
                              color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                          }
                      }
                  }
                  ]
                });

                $('#percent_revenue_<?php echo $key; ?>').highcharts({

                  title: {
                      text: 'Doanh Thu'
                  },
                  series: [{
                      type: 'pie',
                      colors: ['#f39c12', '#dd4b39', '#00a65a', '#3c8dbc', '#00c0ef', '#D81B60', '#605ca8', '#f56954', '#001F3F', '#39CCCC'],
                      name: '',
                      data: [                        
                        <?php 
                          foreach ($dataChart['series']['Revenue'] as $grade => $value) {
                            echo "['$grade',  $value ],";
                          }
                        ?>
                      ],
                      // center: [250, 50],
                      size: 180,
                      showInLegend: true,
                      tooltip: {
                          pointFormat: ' <b> {point.percentage:.1f}%</b>'
                      },
                      dataLabels: {
                          format: ' {point.percentage:.1f} %',
                          enabled: true,
                          style: {
                              color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                          }
                      }
                  }]
                });

              });
            </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->


      <div class="box box-solid box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Thống Kê Doanh Thu User</h3>
          <div class="btn-group pull-right ">
            <a class="btn btn-danger btn-xs " href="#chart_channel" data-toggle="tab" ><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Biểu đồ</a>
            <a class="btn btn-danger btn-xs " href="#percent_channel" data-toggle="tab" ><span class="fa fa-pie-chart" aria-hidden="true"></span> Tỷ lệ</a>
            <a class="btn btn-danger btn-xs " href="#data_channel"  data-toggle="tab" ><span class="fa fa-database" aria-hidden="true"></span> Dữ liệu</a>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body no-padding tab-content">

          <div class="tab-pane fade" id="data_channel">
            <table class="table table-bordered table-striped no-margin">
              <tr>
                <th style="border-right: 1px solid gray; vertical-align: middle;" class="text-center">Đối Tượng</th>
                <th class="text-center">User</th>
                <th class="text-center" style="border-right: 1px solid gray;">% User</th>
                <th class="text-center">Doanh Thu</th>
                <th class="text-center">% Doanh Thu</th>
              </tr>
              
              <?php 
                
                // reset data chart
                $dataChart['series'] = array();

                if ($aStatis) {
                  foreach ($aStatis as $value) {
                ?>
                <tr>
                  <th class="text-center" style="border-right: 1px solid gray;"><?php echo $value['ChargedTime'] ?></th>
                  <td class="text-center"><?php echo number_format($value['AccountTotal']) ?></td>
                  <td class="text-center text-light-blue" style="border-right: 1px solid gray;"><b><?php echo round(($value['AccountTotal'] / $value['AccountTotalAllChargedTime']) * 100,2) ?></b></td>
                  <td class="text-center"><?php echo number_format($value['RevenueTotal']) ?></td>
                  <td class="text-center text-light-blue"><b><?php echo round(($value['RevenueTotal'] / $value['RevenueTotalAllChargedTime']) * 100,2) ?></b></td>
                </tr>
                <?php
                    // data draw chart
                    $dataChart['series']['Account'][$value['ChargedTime']] = round(($value['AccountTotal'] / $value['AccountTotalAllChargedTime']) * 100,2) ;
                    $dataChart['series']['Revenue'][$value['ChargedTime']] = round(($value['RevenueTotal'] / $value['RevenueTotalAllChargedTime']) * 100,2);
                  }
                }
              ?>
            </table>
          </div>

          <div class="row tab-pane fade" id="percent_channel">
            <div class="col-md-6"  style="height: 400px">
              <div id="pay_percent_user_<?php echo $key; ?>"></div>
            </div>
            <div class="col-md-6" style="height: 400px">
              <div id="pay_percent_revenue_<?php echo $key; ?>"></div>
            </div>
          </div>
          
          <div class="row tab-pane active" id="chart_channel" >
    
              <div class="col-md-6"  style="height: 400px">
                <div id="pay_chart_user_<?php echo $key; ?>"></div>
              </div>
              <div class="col-md-6" style="height: 400px">
                <div id="pay_chart_revenue_<?php echo $key; ?>"></div>
              </div>
         
          </div>

          <script type="text/javascript">
            $(function () {

              $('#pay_chart_user_<?php echo $key; ?>').highcharts({
                colors: ['#f39c12'],
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'User'
                },
                xAxis: {
                    categories: ['First Pay','Re-Pay'],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Số lượng user'
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
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [
                {
                  type: 'column',
                  name: 'User',
                  showInLegend: true,
                  data: [
                  <?php 
                  foreach ($aStatis as $value) {
                    echo $value['AccountTotal'] . ",";
                  }
                  ?>
                  ],
                },

                ]
              });

              $('#pay_chart_revenue_<?php echo $key; ?>').highcharts({
                colors: ['#f39c12'],
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Doanh Thu'
                },
                xAxis: {
                    categories: ['First Pay','Re-Pay'],
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
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [
                {
                  type: 'column',
                  name: 'Doanh Thu',
                  showInLegend: true,
                  data: [
                  <?php 
                  foreach ($aStatis as $value) {
                    echo $value['RevenueTotal'] . ",";
                  }
                  ?>
                  ],
                },
                ]
              });

              // percent
              $('#pay_percent_user_<?php echo $key; ?>').highcharts({
                title: {
                    text: 'User'
                },
                series: [{
                    type: 'pie',
                    colors: ['#f39c12', '#dd4b39', '#00a65a', '#3c8dbc', '#00c0ef', '#D81B60', '#605ca8', '#f56954', '#001F3F', '#39CCCC'],
                    name: '',
                    data: [
                        <?php 
                          foreach ($dataChart['series']['Account'] as $grade => $value) {
                            echo "['$grade',  $value ],";
                          }
                        ?>
                    ],
                    tooltip: {
                        pointFormat: ' <b> {point.percentage:.1f}%</b>'
                    },
                    // center: [80, 50],
                    size:180,
                    dataLabels: {
                        format: ' {point.percentage:.1f} %',
                        enabled: true,
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    showInLegend: true,
                }
                ]
              });

              $('#pay_percent_revenue_<?php echo $key; ?>').highcharts({
                title: {
                    text: 'Doanh Thu'
                },
                series: [{
                    type: 'pie',
                    colors: ['#f39c12', '#dd4b39', '#00a65a', '#3c8dbc', '#00c0ef', '#D81B60', '#605ca8', '#f56954', '#001F3F', '#39CCCC'],
                    name: '',
                    data: [                        
                      <?php 
                        foreach ($dataChart['series']['Revenue'] as $grade => $value) {
                          echo "['$grade',  $value ],";
                        }
                      ?>
                    ],
                    tooltip: {
                        pointFormat: ' <b> {point.percentage:.1f}%</b>'
                    },
                    // center: [80, 50],
                    size:180,
                    dataLabels: {
                        format: ' {point.percentage:.1f} %',
                        enabled: true,
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    showInLegend: true,
                }, 
                ]
              });

            });
          </script>
        </div>
      </div>

      <div class="box box-solid box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Thống Kê Kênh Thanh Toán</h3>
          <div class="btn-group pull-right ">
            <a class="btn btn-danger btn-xs " href="#chart_channel_detail" data-toggle="tab" ><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Biểu đồ</a>
            <a class="btn btn-danger btn-xs " href="#percent_channel_detail" data-toggle="tab" ><span class="fa fa-pie-chart" aria-hidden="true"></span> Tỷ lệ</a>
            <a class="btn btn-danger btn-xs " href="#data_channel_detail"  data-toggle="tab" ><span class="fa fa-database" aria-hidden="true"></span> Dữ liệu</a>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body no-padding tab-content">
          
        <?php 
          $aChannel = array();
          foreach ($aStatisChannel['first_pay'] as $channel => $v) {
            $aChannel[$channel]['first_pay']['revenue'] = $v['RevenueTotal'];
            $aChannel[$channel]['first_pay']['percent'] = round(($v['RevenueTotal']/$v['RevenueTotalAllChargedTime']) * 100, 2);
          }

          foreach ($aStatisChannel['re_pay'] as $channel => $v) {
            $aChannel[$channel]['re_pay']['revenue'] = $v['RevenueTotal'];
            $aChannel[$channel]['re_pay']['percent'] = round(($v['RevenueTotal']/$v['RevenueTotalAllChargedTime']) * 100, 2);
          }
        ?>

        <div class="row tab-pane active" id="chart_channel_detail" >
            
          <div class="col-md-6"  style="height: 400px">
            <div id="firstpay_chart_revenue_<?php echo $key; ?>"></div>
          </div>
          <div class="col-md-6" style="height: 400px">
            <div id="repay_chart_revenue_<?php echo $key; ?>"></div>
          </div>
         
        </div>

        <div class="row tab-pane fade" id="percent_channel_detail">
          <div class="col-md-6"  style="height: 400px">
            <div id="firstpay_percent_revenue_<?php echo $key; ?>"></div>
          </div>
          <div class="col-md-6" style="height: 400px">
            <div id="repay_percent_revenue_<?php echo $key; ?>"></div>
          </div>
        </div>

        <div class="tab-pane fade" id="data_channel_detail">
          <table class='table table-bordered table-striped no-padding no-margin'>
            <tr>
              <th class='text-center'  style="border-right: 1px solid gray;" >User \ Kênh</th>
              <?php 
                $i = 1;
                foreach ($aChannel as $channel => $value) {

                  if ($i < count($aChannel)) {
                    $border = "style='border-right: 1px solid gray;'";
                  } else {
                    $border = "";
                  }

                  echo "<th class='text-center' colspan='2' {$border} >{$channel}</th>";
                  $i++;
                }
              ?>
            </tr>
            <tr>
              <th class='text-center'  style="border-right: 1px solid gray;">First Pay</th>
              <?php 
                $i = 1;
                foreach ($aChannel as $channel => $value) {

                  if ($i < count($aChannel)) {
                    $border = "style='border-right: 1px solid gray;'";
                  } else {
                    $border = "";
                  }

                  if (!$value['first_pay']['percent']) $value['first_pay']['percent'] = 0;
                  echo "<td class='text-right'>".number_format($value['first_pay']['revenue'])."</td>";
                  echo "<td class='text-right text-light-blue' {$border} ><strong>".$value['first_pay']['percent']."%</strong></td>";
                  $i++;
                }
              ?>
            </tr>
            <tr>
              <th class='text-center'  style="border-right: 1px solid gray;" >Re-Pay</th>
              <?php 
                $i = 1;
                foreach ($aChannel as $channel => $value) {

                  if ($i < count($aChannel)) {
                    $border = "style='border-right: 1px solid gray;'";
                  } else {
                    $border = "";
                  }

                  if (!$value['re_pay']['percent']) $value['re_pay']['percent'] = 0;
                  echo "<td class='text-right'>".number_format($value['re_pay']['revenue'])."</td>";
                  echo "<td class='text-right text-light-blue' {$border}><strong>".$value['re_pay']['percent']."%</strong></td>";
                  $i++;
                }
              ?>
            </tr>

          </table>
        </div>

        <script type="text/javascript">
            $(function () {

              // first pay
              $('#firstpay_chart_revenue_<?php echo $key; ?>').highcharts({
                colors: ['#f39c12'],
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'First Pay Channel'
                },
                xAxis: {
                    categories: [
                      <?php 
                        foreach ($aStatisChannel['first_pay'] as $channel => $v) :
                          echo "'{$channel}',";
                      ?>
                      <?php endforeach; ?>
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
                {
                  type: 'column',
                  name: 'Doanh Thu',
                  showInLegend: true,
                  data: [
                  <?php 
                    foreach ($aStatisChannel['first_pay'] as $channel => $v) :
                      echo "{$v['RevenueTotal']},";
                  ?>
                  <?php endforeach; ?>
                  ],
                },

                ]
              });
              
              // re pay
              $('#repay_chart_revenue_<?php echo $key; ?>').highcharts({
                colors: ['#f39c12'],
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Re-Pay Channel'
                },
                xAxis: {
                    categories: [
                      <?php 
                        foreach ($aStatisChannel['re_pay'] as $channel => $v) :
                          echo "'{$channel}',";
                      ?>
                      <?php endforeach; ?>
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
                {
                  type: 'column',
                  name: 'Doanh Thu',
                  showInLegend: true,
                  data: [
                  <?php 
                    foreach ($aStatisChannel['re_pay'] as $channel => $v) :
                      echo "{$v['RevenueTotal']},";
                  ?>
                  <?php endforeach; ?>
                  ],
                },

                ]
              });

              // percent first pay
              $('#firstpay_percent_revenue_<?php echo $key; ?>').highcharts({
                
                title: {
                    text: 'First Pay Channel'
                },
                
                series: [{
                    type: 'pie',
                    colors: ['#f39c12', '#dd4b39', '#00a65a', '#3c8dbc', '#00c0ef', '#D81B60', '#605ca8', '#f56954', '#001F3F', '#39CCCC'],
                    name: '',
                    data: [
                        <?php 
                          foreach ($aStatisChannel['first_pay'] as $channel => $v) {
                            echo "['$channel',  " . round(($v['RevenueTotal']/$v['RevenueTotalAllChargedTime']) * 100, 2) . " ],";
                          }
                        ?>
                    ],
                    tooltip: {
                        pointFormat: ' <b> {point.percentage:.1f}%</b>'
                    },
                    // center: [100, 50],
                    size:180,
                    dataLabels: {
                        format: ' {point.percentage:.1f} %',
                        enabled: true,
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    showInLegend: true,
                },                ]
              });
              
              // percent re pay
              $('#repay_percent_revenue_<?php echo $key; ?>').highcharts({
                
                title: {
                    text: 'Re-Pay Channel'
                },
                series: [{
                    type: 'pie',
                    colors: ['#f39c12', '#dd4b39', '#00a65a', '#3c8dbc', '#00c0ef', '#D81B60', '#605ca8', '#f56954', '#001F3F', '#39CCCC'],  
                    name: '',
                    data: [
                        <?php 
                          foreach ($aStatisChannel['re_pay'] as $channel => $v) {
                            echo "['$channel',  " . round(($v['RevenueTotal']/$v['RevenueTotalAllChargedTime']) * 100, 2) . " ],";
                          }
                        ?>
                    ],
                    tooltip: {
                        pointFormat: ' <b> {point.percentage:.1f}%</b>'
                    },
                    // center: [100, 50],
                    size:180,
                    dataLabels: {
                        format: ' {point.percentage:.1f} %',
                        enabled: true,
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    showInLegend: true,
                }
                ]
              });

            });
          </script>

        </div>
      </div>

</div>

