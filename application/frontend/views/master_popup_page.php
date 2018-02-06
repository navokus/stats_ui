<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="UTF-8">
    <title>Game Kpi Report - STATS</title>
    <meta
        content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'
        name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link
        href="<?php echo base_url('public/frontend/bootstrap/css/bootstrap.min.css'); ?>"
        rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link
        href="<?php echo base_url('public/frontend/dist/css/font-awesome.min.css'); ?>"
        rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link
        href="<?php echo base_url('public/frontend/dist/css/ionicons.min.css'); ?>"
        rel="stylesheet" type="text/css" />
    <!-- iCheck for checkboxes and radio inputs -->
    <link
        href="<?php echo base_url('public/frontend/plugins/iCheck/all.css'); ?>"
        rel="stylesheet" type="text/css" />
    <!-- dater picker -->
    <!-- daterange picker -->
    <link
        href="<?php echo base_url('public/frontend/plugins/daterangepicker/daterangepicker-bs3.css'); ?>"
        rel="stylesheet" type="text/css" />
    <!-- DataTables -->
    <link rel="stylesheet"
          href="<?php echo base_url('public/frontend/plugins/datatables/dataTables.bootstrap.css') ?>">
    <link rel="stylesheet"
          href="<?php echo base_url('public/frontend/plugins/datatables/extensions/export/css/buttons.dataTables.min.css') ?>">
    <link rel="stylesheet"
          href="<?php echo base_url('public/frontend/plugins/select2/select2.min.css') ?>">
    <link
        href="<?php echo base_url('public/frontend/plugins/datepicker/datepicker3.css'); ?>"
        rel="stylesheet" type="text/css" />
    <link
        href="<?php echo base_url('public/frontend/plugins/multiple-select-master/multiple-select.css'); ?>"
        rel="stylesheet" type="text/css" />
    <link
        href="<?php echo base_url('public/frontend/plugins/popup-master/assets/css/popup.css'); ?>"
        rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link
        href="<?php echo base_url('public/frontend/dist/css/AdminLTE.min.css'); ?>"
        rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
              page. However, you can choose any other skin. Make sure you
              apply the skin class to the body tag so the changes take effect.
        -->
    <link
        href="<?php echo base_url('public/frontend/dist/css/skins/_all-skins.min.css'); ?>"
        rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <link
        href="<?php echo base_url('public/frontend/dist/css/styles.css'); ?>"
        rel="stylesheet" type="text/css" />
    <link
        href="<?php echo base_url('public/frontend/dist/css/ub-styles.css'); ?>"
        rel="stylesheet" type="text/css" />

    <!-- jQuery 2.1.3 -->
    <script
        src="<?php echo base_url('public/frontend/plugins/jQuery/jQuery-2.1.4.min.js'); ?>"></script>
    <script
        src="<?php echo base_url('public/frontend/dist/js/highcharts.js'); ?>"></script>
    <script type="text/javascript">
        var url = "<?php echo base_url('/index.php'); ?>";
    </script>

</head>
<body>
    <?php if (isset($content)) echo $content; ?>
<script
    src="<?php echo base_url('public/frontend/bootstrap/js/bootstrap.min.js'); ?>"
    type="text/javascript"></script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
  Both of these plugins are recommended to enhance the
  user experience -->
<script
    src="<?php echo base_url('public/frontend/plugins/datepicker/bootstrap-datepicker.js'); ?>"
    type="text/javascript"></script>
<script
    src="<?php echo base_url('public/frontend/plugins/daterangepicker/moment.min.js'); ?>"
    type="text/javascript"></script>
<script
    src="<?php echo base_url('public/frontend/plugins/daterangepicker/daterangepicker.js'); ?>"
    type="text/javascript"></script>
<!-- DataTables -->
<script
    src="<?php echo base_url('public/frontend/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script
    src="<?php echo base_url('public/frontend/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>
<script
    src="<?php echo base_url('public/frontend/plugins/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.min.js') ?>"></script>

<script
    src="<?php echo base_url('public/frontend/plugins/datatables/extensions/export/js/buttons.html5.min.js') ?>"></script>
<script
    src="<?php echo base_url('public/frontend/plugins/datatables/extensions/export/js/buttons.print.min.js') ?>"></script>
<script
    src="<?php echo base_url('public/frontend/plugins/datatables/extensions/export/js/dataTables.buttons.min.js') ?>"></script>
<script
    src="<?php echo base_url('public/frontend/plugins/datatables/extensions/export/js/jszip.min.js') ?>"></script>
<script
    src="<?php echo base_url('public/frontend/plugins/datatables/extensions/export/js/pdfmake.min.js') ?>"></script>
<script
    src="<?php //echo base_url('public/frontend/plugins/datatables/extensions/export/js/vfs_fonts.js') ?>"></script>
<script
    src="<?php echo base_url('public/frontend/plugins/select2/select2.min.js') ?>"></script>


<script
    src="<?php echo base_url('public/frontend/plugins/slimScroll/jquery.slimscroll.min.js'); ?>"
    type="text/javascript"></script>
<script
    src="<?php echo base_url('public/frontend/plugins/iCheck/icheck.min.js'); ?>"
    type="text/javascript"></script>
<script
    src='<?php echo base_url('public/frontend/plugins/fastclick/fastclick.min.js'); ?>'>
    type="text/javascript"></script>
<script
    src="<?php echo base_url('public/frontend/plugins/multiple-select-master/multiple-select.js'); ?>"
    type="text/javascript"></script>
<script
    src="<?php echo base_url('public/frontend/plugins/popup-master/assets/js/jquery.popup.js'); ?>"
    type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('public/frontend/dist/js/app.js'); ?>"
        type="text/javascript"></script>
<script
    src="<?php echo base_url('public/frontend/dist/js/config.js'); ?>"
    type="text/javascript"></script>
<script
    src="<?php echo base_url('public/frontend/dist/js/dv-sparkline.js'); ?>"
    type="text/javascript"></script>

</body>
</html>