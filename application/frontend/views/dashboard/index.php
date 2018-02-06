<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 07/06/2016
 * Time: 10:50
 */
if(isset($_SESSION['dashboard_nodata']) && $_SESSION['dashboard_nodata'] == 'true'){
    unset($_SESSION['dashboard_nodata']);
    echo "<p>Hiện không có dữ liệu của thời gian trên, bạn vui lòng chọn ngày khác,
     hoặc liên hệ <b>[canhtq@vng.com.vn or quangctn@vng.com.vn or lamnt6@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";
}else{
    echo $overview_html;
    echo $trendchart_html;
}
?>

