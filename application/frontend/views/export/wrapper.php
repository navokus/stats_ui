<?php echo $body['kpi_selection'];?>

<?php

if(isset($_SESSION['export_nodata']) && $_SESSION['export_nodata'] == 'true'){
    unset($_SESSION['export_nodata']);
    echo "<p>Hiện không có dữ liệu của thời gian trên, bạn vui lòng chọn ngày khác,
     hoặc liên hệ <b>[canhtq@vng.com.vn or quangctn@vng.com.vn or lamnt6@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";
}else{
    echo $body['tables'];
}

?>
