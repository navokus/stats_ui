
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

          <div class="tab-pane fade" id="data">
				  <table class="table table-bordered table-striped no-margin">
            <tr>
              <th rowspan="2" style="border-right: 1px solid gray; vertical-align: middle;" class="text-center">Đối Tượng</th>
              <th colspan="2" class="text-center" style="border-right: 1px solid gray;">User</th>
              <th colspan="2" class="text-center">Hành Vi</th>
            </tr>
            <tr>
              <th class="text-center">Tổng User</th>
              <th class="text-center" style="border-right: 1px solid gray;">% User</th>
              <th class="text-center">Tổng Hành Vi</th>
              <th class="text-center">% Hành Vi</th>
            </tr>

            <?php 
              if ($aGrades) {
                foreach ($aGrades as $value) {
              ?>
              <tr>
                <th class="text-center" style="border-right: 1px solid gray;"><?php echo $value['ClassificationName'] ?></th>
                <td class="text-center"><?php echo number_format($value['AccountTotal']) ?></td>
                <td class="text-center text-light-blue" style="border-right: 1px solid gray;"><b><?php echo round(($value['AccountTotal'] / $value['AccountTotalAllClassification']) * 100,2) ?>%</b></td>
                <td class="text-center"><?php echo number_format($value['BehaviorTotal']) ?></td>
                <td class="text-center text-light-blue"><b><?php echo round(($value['BehaviorTotal'] / $value['BehaviorTotalAllClassification']) * 100,2) ?>%</b></td>
              </tr>
              <?php
                  // data draw chart
                  $dataChart['series']['Account'][$value['ClassificationName']] = round(($value['AccountTotal'] / $value['AccountTotalAllClassification']) * 100,2) ;
                  $dataChart['series']['Behavior'][$value['ClassificationName']] = round(($value['BehaviorTotal'] / $value['BehaviorTotalAllClassification']) * 100,2) ;
                }
              }
            ?>
            
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

          <div class="row tab-pane fade" id="percent">
            <div class="col-md-6"  style="height: 400px">
              <div id="percent_user_<?php echo $key; ?>"></div>
            </div>
            <div class="col-md-6" style="height: 400px">
              <div id="percent_Behavior_<?php echo $key; ?>"></div>
            </div>
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
                            echo "'". $value['ClassificationName'] ." ',";
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

              $('#chart_Behavior_<?php echo $key; ?>').highcharts({
                colors: ['#f39c12'],
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Hành Vi'
                },
                xAxis: {
                    categories: [
                        <?php 
                          foreach ($aGrades as $value) {
                            echo "'". $value['ClassificationName'] ." ',";
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
                 
                        borderWidth: 0
                    }
                },
                series: [
                {
                  type: 'column',
                  name: 'Hành Vi',
                  showInLegend: true,
                  data: [
                  <?php 
                  foreach ($aGrades as $value) {
                    echo $value['BehaviorTotal'] . ",";
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

                $('#percent_Behavior_<?php echo $key; ?>').highcharts({

                  title: {
                      text: 'Hành Vi'
                  },
                  series: [{
                      type: 'pie',
                      colors: ['#f39c12', '#dd4b39', '#00a65a', '#3c8dbc', '#00c0ef', '#D81B60', '#605ca8', '#f56954', '#001F3F', '#39CCCC'],
                      name: '',
                      data: [                        
                        <?php 
                          foreach ($dataChart['series']['Behavior'] as $grade => $value) {
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

	
</div>
