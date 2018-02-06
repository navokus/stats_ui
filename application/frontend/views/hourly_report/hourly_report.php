<div class="box box-primary">
    <?php echo $body['selection'];?>
</div>

<?php
if(!$body['charts'] && !$body['tables']){
    echo "<p>Hiện không có dữ liệu của thời gian trên, bạn vui lòng chọn ngày khác,
     hoặc liên hệ <b>[canhtq@vng.com.vn or quangctn@vng.com.vn or lamnt6@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";
}else {
    ?>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_chart" data-toggle="tab">Chart</a>
            </li>
            <li>
                <a href="#tab_table" data-toggle="tab">Table</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="tab_chart">
                <?php echo $body['charts']; ?>
            </div>

            <div class="tab-pane" id="tab_table">
                <?php echo $body['table']; ?>
            </div>
        </div>
    </div>

<?php } ?>

