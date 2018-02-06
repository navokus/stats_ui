<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 25/04/2016
 * Time: 14:24
 */
?>
<div class="box box-solid bg-green-gradient">
    <div class="box-header">
        <h3 class="box-title"><?php echo $body['title'];?></h3>
</div>
<?php echo $body['selection'];?>
</div>

<div class="row">
    <?php
    foreach($body['listContainer'] as $value) {
        echo '<div class="col-md-12">';
        echo '<div id="' . $value . '"></div>';
        echo '</div>';
    }
    ?>
</div>

<?php
if(!$body['charts'] && !$body['tables']){
    echo "<p>Hiện không có dữ liệu của thời gian trên, bạn vui lòng chọn ngày khác,
     hoặc liên hệ <b>[canhtq@vng.com.vn or quangctn@vng.com.vn or lamnt6@vng.com.vn]</b> để được hỗ trợ. Cảm ơn.</p>";
}
echo $body['charts'];
echo $body['tables'];
?>