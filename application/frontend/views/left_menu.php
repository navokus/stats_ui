<?php
$this->load->library('Util');
$current_user=$this->session->userdata('user');

$current_game=$this->input->post('default_game') ? $this->input->post('default_game') : $this->session->userdata['default_game'];


?>
<li class="active treeview">
	<a href="<?php echo site_url('dashboard2'); ?>" class="treeview-item menu-logo">
		<span><b>KPI</b></span>
	</a>
</li>
<li class="active" >
    <ul class="treeview-menu">
    	<li class="dashboard"><a href="<?php echo site_url('dashboard2'); ?>"><i class="fa fa-dashboard"></i><span> Dashboard</span></a></li>
    </ul>
</li>
<?php
//generate menu by game_code


foreach($left_menu as $group_id => $v){
    $group_name = $v['group_name'];
    $class_1 = $v['class_1'];
    $class_2 = $v['class_2'];

    $report_detail = $v['report_detail'];

    echo '<li class="active treeview">';
    echo '<a href="#" class="treeview-item">';
    echo '<i class="'.$class_1.'"></i> <span>'.$group_name.'</span> <i class="'.$class_2.'"></i>';
    echo '</a>';
    echo '<ul class="treeview-menu">';

    foreach($report_detail as $report_id => $v_1){
        $report_name = $v_1['report_name'];
        $url = $v_1['report_url'];
        $class_1 = $v_1['class_1'];
        $class_2 = $v_1['class_2'];
        echo '<li class=""><a href="' .site_url($url) . '"><i class="fa fa-circle-o"></i><span> '.$report_name.'</span></a></li>';
    }
    if(strcmp($group_id,"gamekpi")==0){
        echo '<li class=""><a href="' .site_url("kpi/exportMonthly") . '"><i class="fa fa-circle-o"></i><span>Monthly Report</span></a></li>';
    }

    echo '</ul>';
}

?>

<li class="active treeview">
	<a href="#" class="treeview-item">
		<i class="fa fa-book"></i> <span>Documents</span> <i class="fa fa-angle-left pull-right"></i>
	</a>
	<ul class="treeview-menu">
		<li>
		    <a href="<?php echo base_url('index.php/KpiDefine'); ?>" >
		        <i class="fa fa-circle-o"></i>
		        <span>Kpi Document</span>
		    </a>
		</li>
	</ul>
</li>
<?php if ($user['GroupId'] == 1) { ?>

    <li class="active treeview">
        <a href="#" class="treeview-item">
            <i class="fa fa-tasks"></i> <span>Operation</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo site_url('operation/overview'); ?>"><i class="fa fa-circle-o"></i><span> Overview</span></a></li>
            <li><a href="<?php echo site_url('operation/view-statistics'); ?>"><i class="fa fa-circle-o"></i><span> View statistics</span></a></li>
            <li><a href="<?php echo site_url('operation/migration-status'); ?>"><i class="fa fa-circle-o"></i><span> Migration status</span></a></li>
            <li><a href="<?php echo site_url('operation/compare-by-source'); ?>"><i class="fa fa-circle-o"></i><span> Kpi comparison</span></a></li>
        </ul>
    </li>

    <li class="active treeview">
        <a href="#" class="treeview-item">
            <i class="fa fa-gears"></i> <span>Settings</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo site_url('User/users'); ?>"><i class="fa fa-circle-o"></i><span> User</span></a></li>
            <li><a href="<?php echo site_url('User'); ?>"><i class="fa fa-circle-o"></i><span> Group User</span></a></li>
            <li><a href="<?php echo site_url('Game'); ?>"><i class="fa fa-circle-o"></i><span> Game</span></a></li>
            <li><a href="<?php echo site_url('Game/update_report_menu'); ?>"><i class="fa fa-circle-o"></i><span> Game Report</span></a></li>
            <?php if ($user['GroupId'] == 1) { ?>
            <li><a href="<?php echo site_url('GameMaster'); ?>"><i class="fa fa-circle-o"></i><span> Game Master</span></a></li>
            <li><a href="<?php echo site_url('CleanData'); ?>"><i class="fa fa-circle-o"></i><span> Clean Data</span></a></li>
            <?php }?>
        </ul>
    </li>


<?php } ?>
<li class="active treeview">
	<a href="#" class="treeview-item">
		<i class="fa fa-users"></i> <span>Accounts</span> <i class="fa fa-angle-left pull-right"></i>
	</a>
	<ul class="treeview-menu">
		<li class="treeview" >
		    <a href="<?php echo base_url('index.php/Login/logout'); ?>" >
		        <i class="fa fa-circle-o"></i>
		        <span>Logout</span>
		    </a>
		</li>
	</ul>
</li>




