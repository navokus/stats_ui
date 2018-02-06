<?php

	$header = array_keys ($compare[$gameCode]);

  // check data
  $is_data = false;
  foreach ($compare[$gameCode] as $k => $v) {
    if ($compare[$gameCode][$k]['TotalUser']) {
      $is_data = true;
      break;
    }
  }

  if (!$is_data) {
    echo '<div class="text-center">Không có dữ liệu.</div>';
    die();
  }

?>
<table class="table table-bordered table-bordered-gray no-margin table-striped nowrap " id="table-compare-one-game">
	<thead>
		<tr>
        	<th class="text-center" style="width: 150px" rowspan="2"><?php echo $time; ?> / Game</th>
            <?php foreach ($header as $value) :
            	if ($compare[$gameCode][$value]) {
            ?>
              	<th class="text-center" colspan="2" ><?php echo $value ?></th>
            <?php
            	}
            ?>
            <?php endforeach; ?>
        </tr>
        <tr>
            <?php foreach ($header as $value) :
            	if ($compare[$gameCode][$value]) {
            ?>
            	<th class="text-center" ><?php echo strtoupper($gameCode); ?></th>
            	<th class="text-center" ><?php echo strtoupper($gameCodeCompare); ?></th>
            <?php
            	}
            ?>
            <?php endforeach; ?>
        </tr>
	</thead>
    <tbody>

        <?php
        	$i = 0;

        	foreach ($compare[$gameCode] as $k => $v) :

          		// total user
          		if ($i == 0) {
          			$row_user = '<tr><th class="text-center">Tổng User</th>';
          		}

          		// user paying
          		if ($i == 0) {
          			$row_user_pay = '<tr><th class="text-center">User chi trả</th>';
          		}

          		// Doanh thu
          		if ($i == 0) {
          			$row_revenue = '<tr><th class="text-center">Doanh thu</th>';
          		}

	          	// total playing time
          		if ($i == 0) {
          			$row_playtime = '<tr><th class="text-center">Thời gian chơi</th>';
          		}

				// avg playing time
				if ($i == 0) {
					$row_avPlaytime = '<tr><th class="text-center">TB Thời gian chơi</th>';
				}

          		if ($compare[$gameCode][$k]) {



  	      			$row_user .= '
  		      			<td class="text-right">'. number_format($compare[$gameCode][$k]['TotalUser']) .'</td>
  		              	<td class="text-right">'. number_format($compare[$gameCodeCompare][$k]['TotalUser']) .'</td>
  	      			';

  	      			$row_user_pay .= '
  		      			<td class="text-right">'. number_format($compare[$gameCode][$k]['TotalUserPaying']) .'</td>
  		              	<td class="text-right">'. number_format($compare[$gameCodeCompare][$k]['TotalUserPaying']) .'</td>
  	      			';


  	      			$row_revenue .= '
  		      			<td class="text-right">'. number_format($compare[$gameCode][$k]['TotalRevenue']) .'</td>
  		              	<td class="text-right">'. number_format($compare[$gameCodeCompare][$k]['TotalRevenue']) .'</td>
  	      			';

  	      			$row_playtime .= '
  		      			<td class="text-right">'. number_format($compare[$gameCode][$k]['TotalPlaytime']) .'</td>
  		              	<td class="text-right">'. number_format($compare[$gameCodeCompare][$k]['TotalPlaytime']) .'</td>
  	      			';

					$row_avPlaytime .= '
  		      			<td class="text-right">'. round($compare[$gameCode][$k]['TotalPlaytime'] / $compare[$gameCode][$k]['TotalUser'] , 2) .'</td>
  		              	<td class="text-right">'. round($compare[$gameCodeCompare][$k]['TotalPlaytime'] / $compare[$gameCodeCompare][$k]['TotalUser'], 2) .'</td>
  	      			';

	          		if ($i == count($compare[$gameCode]) - 1) {
	          			$row_user .= '</tr>';
	          			$row_user_pay .= '</tr>';
	          			$row_revenue .= '</tr>';
	          			$row_playtime .= '</tr>';
						$row_avPlaytime .= '</tr>';
	          		}
	          	}
        	$i++;
        	endforeach;

        	echo $row_user ;
    		echo $row_user_pay;
    		echo $row_revenue ;
    		echo $row_playtime;
    		echo $row_avPlaytime;

        ?>

  	</tbody>
</table>
