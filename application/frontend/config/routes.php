<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$route['default_controller'] = "welcome";
$route['404_override'] = '';


$route['info'] = "Info/index";


$route['device/index'] = "Device/index";
$route['device/os'] = "Device/os";

$route['package/index'] = "Package/index";
$route['server/index'] = "ServerKpi/index";
$route['server/rank'] = "ServerKpi/rank";
$route['server/detail'] = "ServerKpi/detail";

$route['channel/index'] = "LoginChannel/index";

$route['hourlyreport'] = "HourlyReport";
$route['kpi/mobile-export'] = "Kpi/mobile_export";

///// new ////
$route['dashboard'] = "Dashboard";
$route['dashboard2'] = "Dashboard/dashboard2";
$route['dashboard/(:any)'] = 'Dashboard/dashboard/$1';


$route['kpi/user'] = "UserKpi/active_user";
$route['kpi/revenue'] = "RevenueKpi/revenue";
$route['kpi/daily'] = "Kpi/getKpi";
$route['kpi/hourly'] = "HourlyReport/hourly";
$route['kpi/export'] = "Kpi/export_kpi";
$route['kpi/export-source'] = "Kpi/export_kpi_by_source";
$route['kpi/device'] = "DeviceIdKpi/index";
$route['kpi/exportMonthly'] = "Kpi/exportMonthly";

$route['mobile/device-os'] = "Device/os";
$route['mobile/package-install'] = "Package/installed";
$route['mobile/export'] = "Kpi/mobile_export";

$route['mobile/login-channel'] = "LoginChannel/channel";
$route['mobile/login-channel-detail'] = "LoginChannel/detail";

$route['behavior/odd'] = "Behavior/oneDayDetail";
$route['behavior/top-user'] = "Behavior/top_user";

$route['server/top'] = "ServerKpi/top";
$route['server/detail'] = "GroupKpi/detail";
$route['channel/detail'] = "GroupKpi/detail";

$route['group/detail'] = "GroupKpi/detail";
$route['group/stack'] = "GroupKpi/stack";
$route['group/pie'] = "GroupKpi/pie";
$route['retention/graph'] = "RetentionGraph/index";

$route['group/(:any)'] = "GroupKpi/stack/$1";
$route['ajax/group/(:any)/(:any)/(:any)'] = "GroupKpi/ajaxChart/$1/$2/$3";
$route['ajax/group-table/(:any)/(:any)'] = "GroupKpi/ajaxTable/$1/$2";
$route['ajax/convert/(:any)/(:any)'] = "GroupDataConvert/convertdata/$1/$2";

//admin
$route['operation/overview'] = "Operation/overview";
$route['operation/view-statistics'] = 'Operation/view_statistics';
$route['operation/migration-status'] = 'Operation/kpi_migration_status';
$route['operation/compare-by-source'] = 'Operation/compare_kpi_by_source';

// backend process
$route['backenprocess/(:any)/(:any)'] = "BackendProcess/sum_monthly/$1/$2";

$route['email-report/force-send'] = 'EmailDAReport/force_send';

$route['game/overview'] = 'ReportTotal/tool_view';
//sdk
$route['kpi/sdk/daily'] = "sdk/SdkGamekpi/daily";
$route['kpi/sdk/weekly'] = "sdk/SdkGamekpi/weekly";
$route['kpi/sdk/monthly'] = "sdk/SdkGamekpi/monthly";
$route['kpi/sdk/hourly'] = "sdk/SdkBehavior/oneDayDetail";
$route['mobile/sdk/login-channel'] = "sdk/SdkChannel/index";
$route['mobile/sdk/package-install'] = "sdk/SdkPackage/index";
$route['mobile/sdk/device-os'] = "sdk/SdkMobileOs/index";
$route['sdk/country'] = "sdk/SdkCountry";
$route['sdk/KpiDefine'] = "sdk/KpiDefine";
$route['sdk'] = "sdk/SdkGamekpi/daily";
$route['sdk/export'] = "sdk/SdkExport/export_kpi";
$route['sdk/export_mobile'] = "sdk/SdkExport/mobile_export";


//Login & Session
$route['checklogin/(:any)'] = "Login/checklogin/$1";
$route['kpi/compare'] = "Kpi/compare";
$route['Test/test/(:any)'] = "Test/test/$1";
/* End of file routes.php */
/* Location: ./application/config/routes.php */