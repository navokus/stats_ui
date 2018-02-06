<?php
$this->load->library('Util');
$current_user = $this->session->userdata('user');

$current_game = $this->input->post('default_game') ? $this->input->post('default_game') : $this->session->userdata['default_game'];


?>
<li class="active treeview">
    <a href="<?php echo site_url('kpi/sdk/daily'); ?>" class="treeview-item menu-logo">
        <span><b>KPI</b></span>
    </a>
</li>
<li class="active" >
    <ul class="treeview-menu">
        <li class="dashboard"><a href="<?php echo site_url('dashboard2'); ?>"><i class="fa fa-dashboard"></i><span> Back to Dashboard</span></a></li>
    </ul>
</li>
<li class="header">Report by MTO MobileSDK
</li>
<li class="active treeview">
    <a href="#" class="treeview-item">
        <i class="fa fa-bar-chart"></i> <span>Game KPIs</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="<?php echo site_url('kpi/sdk/hourly'); ?>"><i class="fa fa-circle-o"></i><span>Hourly Report</span></a>
        </li>
        <li><a href="<?php echo site_url('kpi/sdk/daily'); ?>"><i class="fa fa-circle-o"></i><span>Daily Report</span></a>
        </li>
        <li><a href="<?php echo site_url('kpi/sdk/weekly'); ?>"><i class="fa fa-circle-o"></i><span>Weekly Report</span></a>
        </li>
        <li><a href="<?php echo site_url('kpi/sdk/monthly'); ?>"><i class="fa fa-circle-o"></i><span>Monthly Report</span></a></li>
        <li><a href="<?php echo site_url('sdk/export'); ?>"><i class="fa fa-circle-o"></i><span>Export Data</span></a></li>

    </ul>
</li>
<li class="active treeview">
    <a href="#" class="treeview-item">
        <i class="fa fa-mobile"></i> <span>Group</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="<?php echo site_url('mobile/sdk/login-channel'); ?>"><i class="fa fa-circle-o"></i><span>Login Channel</span></a>
        </li>
        <li><a href="<?php echo site_url('mobile/sdk/package-install'); ?>"><i class="fa fa-circle-o"></i><span>Package Install</span></a>
        </li>
        <li><a href="<?php echo site_url('mobile/sdk/device-os'); ?>"><i class="fa fa-circle-o"></i><span>OS Platform</span></a></li>
        <li><a href="<?php echo site_url('sdk/export_mobile'); ?>"><i class="fa fa-circle-o"></i><span>Export Data</span></a></li>
    </ul>
</li>

<li class="active treeview">
    <a href="#" class="treeview-item">
        <i class="fa fa-server"></i> <span>Location</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="<?php echo site_url('sdk/country'); ?>"><i class="fa fa-circle-o"></i><span>Country Report</span></a>
        </li>
    </ul>
</li>


<li class="active treeview">
    <a href="#" class="treeview-item">
        <i class="fa fa-book"></i> <span>Documents</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="<?php echo base_url('index.php/sdk/KpiDefine'); ?>">
                <i class="fa fa-circle-o"></i>
                <span>Kpi Document</span>
            </a>
        </li>
    </ul>
</li>
<li class="active treeview">
    <a href="#" class="treeview-item">
        <i class="fa fa-users"></i> <span>Accounts</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li class="treeview">
            <a href="<?php echo base_url('index.php/Login/logout'); ?>">
                <i class="fa fa-circle-o"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</li>




