<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>
        <?php
        $list_games = $body['aGames'];
        foreach ( $list_games as $value ) {

            if ($this->session->userdata ( 'default_game' ) == $value ['GameCode']) {
                echo "{$value['GameName']}" . " - Game Kpi Report - STATS";
            }
        }
        ?>
    </title>
    <meta
        content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'
        name='viewport'>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('public/frontend/dist/img/fav32-stats.ico'); ?>" />
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
        href="<?php echo base_url('public/frontend/dist/css/ub-styles.css?time=0250'); ?>"
        rel="stylesheet" type="text/css" />
<link
        href="<?php echo base_url('public/frontend/dist/css/ub-responsive.css?time=0250'); ?>"
        rel="stylesheet" type="text/css" />
    <!-- jQuery 2.1.3 -->
    <script
        src="<?php echo base_url('public/frontend/plugins/jQuery/jQuery-2.1.4.min.js'); ?>"></script>
    <script src="<?php echo base_url('public/frontend/dist/js/highcharts.js'); ?>"></script>
    <script src="<?php echo base_url('public/frontend/dist/js/boost.js'); ?>"></script>
    <script src="<?php echo base_url('public/frontend/dist/js/exporting.js'); ?>"></script>

    <script type="text/javascript">
        var url = "<?php echo base_url('/index.php'); ?>";
    </script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-81047723-1', 'auto');
        ga('send', 'pageview');

    </script>

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
        src="<?php echo base_url('public/frontend/plugins/select2/select2.min.js') ?>"></script>

</head>
<body class="sidebar-mini skin-blue">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="<?php echo site_url('dashboard2'); ?>" class="logo hidden-xs"><b>KPI</b> <!-- (<?php $list_games = $body['aGames']; echo count($list_games)?> games) --></a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">

            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle  pull-left" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu  pull-left">
                <form name="form" action="" method="post">
                    <input type="hidden" value="all" name="game_type" id="gameType">
                    <ul class="nav navbar-nav">
                        <li class="games-menu <?php $type = $this->session->userdata('game_type'); if( $type == false || $type == "all") echo 'active'?>">
                            <a href="javascript:void(0);" class="games-menu-control" data-value="all" title="All Games">
                                <i class="fa fa-th"></i>  <span class="hidden-xs">All</span>
                            </a>
                        </li>
                        <li class="games-menu <?php $type = $this->session->userdata('game_type'); if($type == "pc") echo 'active'?>">
                            <a href="javascript:void(0);" class="games-menu-control" data-value="pc" title="Client Games">
                                <i class="fa fa-desktop"></i>  <span class="hidden-xs">Client</span>
                            </a>
                        </li>
                        <li class="games-menu <?php $type = $this->session->userdata('game_type'); if($type == "web") echo 'active'?>">
                            <a href="javascript:void(0);" class="games-menu-control" data-value="web" title="Web Games">
                                <i class="fa fa-globe fa-lg"></i>  <span class="hidden-xs">Web</span>
                            </a>
                        </li>
                        <li class="games-menu <?php $type = $this->session->userdata('game_type'); if($type == "mobile") echo 'active'?>">
                            <a href="javascript:void(0);" class="games-menu-control" data-value="mobile" title="Mobile Games">
                                <i class="fa fa-mobile fa-lg"></i>  <span class="hidden-xs">Mobile</span>
                            </a>
                        </li>
                    </ul>
                </form>
            </div>
        </nav>

    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">

                <!-- Optionally, you can add icons to the links -->
                <?php if (isset($menu)) echo $menu; ?>
            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <ol class="breadcrumb col-xs-12 pull-left">
                <li>
                    <a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>
                        <?php
                        if($body['breadcrumb']==null){
                            $list_games = $body['aGames'];
                            foreach ( $list_games as $value ) {
                                if ($this->session->userdata ( 'default_game' ) == $value ['GameCode']) {
                                    echo "{$value['GameName']}";
                                }
                            }
                        }else{
                            echo "{$body['breadcrumb']}";
                        }
                        ?>
                    </a>
                </li>
                <li><a href="#"><?php echo $body['title'];?></a></li>
                <li class="pull-right">
                    <marquee behavior="scroll" direction="left">
                        <!--<?php
                         if ($this->session->userdata ('default_game')=='jxm' && !$this->session->userdata("top_user")) { ?>
                             <a href="<?php echo site_url('behavior/top-user?from=dashboard'); ?>" target="_blank">Click here to view top paying user</a>
                        <?php } ?>-->
                    </marquee>
                </li>
            </ol>
            <div class="clearfix"></div>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Your Page Content Here -->
            <?php if (isset($content)) echo $content; ?>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">STATS Department</div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2016 <a href="#">VNG</a>.
        </strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- modal -->
<div class="modal fade" id="modelExport" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Export</h4>
            </div>
            <div class="modal-body" id="contentExportModel"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success">Download</button>
            </div>
        </div>
    </div>
</div>

<!-- REQUIRED JS SCRIPTS -->
<!-- Bootstrap 3.3.2 JS -->
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
    src="<?php echo base_url('public/frontend/plugins/table-export/tableExport.js'); ?>"
    type="text/javascript"></script>
<script
    src="<?php echo base_url('public/frontend/plugins/table-export/jquery.base64.js'); ?>"
    type="text/javascript"></script>
<script
    src="<?php echo base_url('public/frontend/plugins/table-export/html2canvas.js'); ?>"
    type="text/javascript"></script>

<!-- AdminLTE App -->
<script src="<?php echo base_url('public/frontend/dist/js/app.js'); ?>"
        type="text/javascript"></script>
<script
    src="<?php echo base_url('public/frontend/dist/js/config-v1.0.js?time=20171120'); ?>"
    type="text/javascript"></script>
<script type="text/javascript">
    $('body').prepend('<a href="#" class="back-to-top" title="Back to Top">Back to Top</a>');
</script>
</body>
</html>