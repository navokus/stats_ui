<?php
foreach ($list_promotion as $month => $aGames) :

    foreach ($aGames as $game => $aPromotions) :

        foreach ($aPromotions as $key => $value) :
            $total_rows[$month][$game]++;
            $total_rows[$month]['all']++;
        endforeach;

        $total_rows[$month][$game]++;
        $total_rows[$month]['all']++;
    endforeach;
    $total_rows[$month]['all']++;
endforeach;
?>
<div class="col-md-12">

    <table class="table table-bordered table-bordered-gray no-margin">
        <tr>

            <th class="text-center">Tháng</th>
            <th class="text-center">Game</th>
            <th class="text-left">Chương trình</th>
            <th class="text-center">Role cũ</th>
            <th class="text-center">Role mới</th>
            <th class="text-center">User chi trả</th>
            <th class="text-center">Doanh thu</th>
            <th class="text-center">Thời gian chơi</th>

        </tr>
        <?php foreach ($list_promotion as $month => $aGames) :

            $html_rows .= '<tr>';
            $html_rows .= '
                        <td class="text-center border-bottom-2px" rowspan="' . $total_rows[$month]['all'] . '">' . $month . '</td>
                    ';

            $monthGameTotal = $monthPromotionTotal = $monthAccountTotalOld = $monthAccountTotalNew = $monthRevenueTotal = $monthPlayingtimeTotal = $monthAccountTotalPaying = 0;

            foreach ($aGames as $game => $aPromotions) :

                $accountTotalOld = $accountTotalNew = $revenueTotal = $playingtimeTotal = $accountTotalPaying = 0;

                $html_rows .= '
                            <td class="text-center border-bottom-2px" rowspan="' . $total_rows[$month][$game] . '" style="text-transform: uppercase;">' . $game . '</td>
                        ';
                foreach ($aPromotions as $key => $value) :


                    if ($key != 0) {
                        $html_rows .= '<tr>';
                    }

                    if ($key == count($aPromotions) - 1) {
//                                $border_bottom = ' border-bottom-2px ';
                    } else {
                        $border_bottom = '';
                    }

                    $html_rows .= '
                        <td class="' . $border_bottom . '" >' . $value['PromotionName'] . ' <br>
                            (<u>' . date('Y-m-d H:i', strtotime($value['FromDate'])) . '</u> - <u>' . date('Y-m-d H:i', strtotime($value['ToDate'])) . '</u>)
                            <a class="fa fa-arrow-circle-o-right" href=" ' . site_url('Promotion/index/'. $value['GameCode'] .'_'. $value['PromotionID'] .'?view=1') . '"></a>
                        </td>';
                    $html_rows .= '<td class="text-right ' . $border_bottom . '">' . number_format($value['AccountTotalOld']) . '</td>';
                    $html_rows .= '<td class="text-right ' . $border_bottom . '">' . number_format($value['AccountTotalNew']) . '</td>';
                    $html_rows .= '<td class="text-right ' . $border_bottom . '">' . number_format($value['AccountTotalPaying']) . '</td>';
                    $html_rows .= '<td class="text-right ' . $border_bottom . '">' . number_format($value['RevenueTotal']) . 'đ</td>';
                    $html_rows .= '<td class="text-right ' . $border_bottom . '">' . number_format($value['PlayingtimeTotal'] / 60) . ' giờ</td>';
                    $html_rows .= '</tr>';

                    $accountTotalOld += $value['AccountTotalOld'];
                    $accountTotalNew += $value['AccountTotalNew'];
                    $revenueTotal += $value['RevenueTotal'];
                    $playingtimeTotal += $value['PlayingtimeTotal'];
                    $accountTotalPaying += $value['AccountTotalPaying'];

                endforeach;
                $html_rows .= '
                <tr class="background_total_game">
                    <td class="border-bottom-2px"><b>Tổng cộng: ' . count($aPromotions) . ' chương trình</b></td>
                    <td class="text-right border-bottom-2px"><b>' . number_format($accountTotalOld) . '</b></td>
                    <td class="text-right border-bottom-2px"><b>' . number_format($accountTotalNew) . '</b></td>
                    <td class="text-right border-bottom-2px"><b>' . number_format($accountTotalPaying) . '</b></td>
                    <td class="text-right border-bottom-2px"><b>' . number_format($revenueTotal) . 'đ</b></td>
                    <td class="text-right border-bottom-2px"><b>' . number_format($playingtimeTotal / 60) . ' giờ</b></td>
                </tr>';

                $monthGameTotal ++;
                $monthPromotionTotal += count($aPromotions);
                $monthAccountTotalOld += $accountTotalOld;
                $monthAccountTotalNew += $accountTotalNew;
                $monthRevenueTotal += $revenueTotal;
                $monthPlayingtimeTotal += $playingtimeTotal;
                $monthAccountTotalPaying += $accountTotalPaying;
            endforeach;

            $html_rows .= '
            <tr class="background_total_month">
                <td class="border-bottom-2px"><b>' . $monthGameTotal . ' Game</b></td>
                <td class="border-bottom-2px"><b>Tổng cộng: ' . $monthPromotionTotal . ' chương trình</b></td>
                <td class="text-right border-bottom-2px"><b>' . number_format($monthAccountTotalOld) . '</b></td>
                <td class="text-right border-bottom-2px"><b>' . number_format($monthAccountTotalNew) . '</b></td>
                <td class="text-right border-bottom-2px"><b>' . number_format($monthAccountTotalPaying) . '</b></td>
                <td class="text-right border-bottom-2px"><b>' . number_format($monthRevenueTotal) . 'đ</b></td>
                <td class="text-right border-bottom-2px"><b>' . number_format($monthPlayingtimeTotal / 60) . ' giờ</b></td>
            </tr>';

        endforeach;

        echo $html_rows;

        ?>
    </table>
</div>


