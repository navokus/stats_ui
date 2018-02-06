<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;

$prodHost = '10.60.22.2';
$prodPass = 'Hyi76tZpro';
$devHost = '10.40.1.21';
$devPass = '123456';


$devZDBHost = '10.40.1.21';
$devZDBPass = '123456';

$prodZDBHost = '10.30.56.128';
$prodZDBPass = 'K5L3Rw!2deOxw';


$mode = '';
$mode = 'prod';

if($mode == 'prod'){
	$db['default']['hostname'] = $prodHost;
	$db['default']['password'] = $prodPass;
	$db['ubstats']['hostname'] = $prodHost;
	$db['ubstats']['password'] = $prodPass;
    $db['ubstats_bk']['hostname'] = $prodHost;
    $db['ubstats_bk']['password'] = $prodPass;
	
	$db['zstatsDb']['hostname'] = $prodZDBHost;
	$db['zstatsDb']['password'] = $prodZDBPass;
	$db['zstatsDb']['username'] = 'dbgsum';
	
}else{
	$db['default']['hostname'] = $devHost;
	$db['default']['password'] = $devPass;
	
	$db['ubstats']['hostname'] = $devHost;
	$db['ubstats']['password'] = $devPass;
	
	$db['zstatsDb']['hostname'] = $devZDBHost;
	$db['zstatsDb']['password'] = $devZDBPass;
	
	$db['zstatsDb']['username'] = 'root';

    $db['ubstats_bk']['hostname'] = $devHost;
    $db['ubstats_bk']['password'] = $devPass;
}
		

// PROD
//$db['default']['hostname'] = '10.60.22.2';
//$db['default']['password'] = 'Hyi76tZpro';
//$db['ubstats']['hostname'] = '10.60.22.2';
//$db['ubstats']['password'] = 'Hyi76tZpro';

// DEV
//$db['default']['hostname'] = '10.40.1.21';
//$db['default']['password'] = '123456';
//$db['ubstats']['hostname'] = '10.40.1.21';
//$db['ubstats']['password'] = '123456';


$db['default']['username'] = 'root';
$db['default']['database'] = 'ubstats';
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

// config localhost
$db['local']['hostname'] = '127.0.0.1';
$db['local']['username'] = 'root';
$db['local']['password'] = 'root';
$db['local']['database'] = 'UB_Report';
$db['local']['dbdriver'] = 'mysql';
$db['local']['dbprefix'] = '';
$db['local']['pconnect'] = TRUE;
$db['local']['db_debug'] = TRUE;
$db['local']['cache_on'] = FALSE;
$db['local']['cachedir'] = '';
$db['local']['char_set'] = 'utf8';
$db['local']['dbcollat'] = 'utf8_general_ci';
$db['local']['swap_pre'] = '';
$db['local']['autoinit'] = TRUE;
$db['local']['stricton'] = FALSE;

$db['ubstats']['username'] = 'root';
$db['ubstats']['database'] = 'ubstats';
$db['ubstats']['dbdriver'] = 'mysql';
$db['ubstats']['dbprefix'] = '';
$db['ubstats']['pconnect'] = TRUE;
$db['ubstats']['db_debug'] = TRUE;
$db['ubstats']['cache_on'] = FALSE;
$db['ubstats']['cachedir'] = '';
$db['ubstats']['char_set'] = 'utf8';
$db['ubstats']['dbcollat'] = 'utf8_general_ci';
$db['ubstats']['swap_pre'] = '';
$db['ubstats']['autoinit'] = TRUE;
$db['ubstats']['stricton'] = FALSE;

$db['zstatsDb']['database'] = 'zstats_directbilling';
    $db['zstatsDb']['dbdriver'] = 'mysql';
$db['zstatsDb']['dbprefix'] = '';
$db['zstatsDb']['pconnect'] = TRUE;
$db['zstatsDb']['db_debug'] = TRUE;
$db['zstatsDb']['cache_on'] = FALSE;
$db['zstatsDb']['cachedir'] = '';
$db['zstatsDb']['char_set'] = 'utf8';
$db['zstatsDb']['dbcollat'] = 'utf8_general_ci';
$db['zstatsDb']['swap_pre'] = '';
$db['zstatsDb']['autoinit'] = TRUE;
$db['zstatsDb']['stricton'] = FALSE;

$db['stats_myplay']['hostname'] = '10.30.56.128';
$db['stats_myplay']['username'] = 'stats';
$db['stats_myplay']['password'] = 'Kd_36G7j!Rm2';
$db['stats_myplay']['database'] = 'stats_myplay';
$db['stats_myplay']['dbdriver'] = 'mysql';
$db['stats_myplay']['dbprefix'] = '';
$db['stats_myplay']['pconnect'] = TRUE;
$db['stats_myplay']['db_debug'] = TRUE;
$db['stats_myplay']['cache_on'] = FALSE;
$db['stats_myplay']['cachedir'] = '';
$db['stats_myplay']['char_set'] = 'utf8';
$db['stats_myplay']['dbcollat'] = 'utf8_general_ci';
$db['stats_myplay']['swap_pre'] = '';
$db['stats_myplay']['autoinit'] = TRUE;
$db['stats_myplay']['stricton'] = FALSE;

$db['ubstats_bk']['username'] = 'root';
$db['ubstats_bk']['database'] = 'ubstats_bk';
$db['ubstats_bk']['dbdriver'] = 'mysql';
$db['ubstats_bk']['dbprefix'] = '';
$db['ubstats_bk']['pconnect'] = TRUE;
$db['ubstats_bk']['db_debug'] = TRUE;
$db['ubstats_bk']['cache_on'] = FALSE;
$db['ubstats_bk']['cachedir'] = '';
$db['ubstats_bk']['char_set'] = 'utf8';
$db['ubstats_bk']['dbcollat'] = 'utf8_general_ci';
$db['ubstats_bk']['swap_pre'] = '';
$db['ubstats_bk']['autoinit'] = TRUE;
$db['ubstats_bk']['stricton'] = FALSE;



$db['share_fa_db']['hostname'] = '10.60.1.3';
$db['share_fa_db']['username'] = 'kpi_stats';
$db['share_fa_db']['password'] = 'AZx#123asd';
$db['share_fa_db']['database'] = 'KPI_STATS';
$db['share_fa_db']['dbdriver'] = 'mysql';
$db['share_fa_db']['dbprefix'] = '';
$db['share_fa_db']['pconnect'] = TRUE;
$db['share_fa_db']['db_debug'] = TRUE;
$db['share_fa_db']['cache_on'] = FALSE;
$db['share_fa_db']['cachedir'] = '';
$db['share_fa_db']['char_set'] = 'utf8';
$db['share_fa_db']['dbcollat'] = 'utf8_general_ci';
$db['share_fa_db']['swap_pre'] = '';
$db['share_fa_db']['autoinit'] = TRUE;
$db['share_fa_db']['stricton'] = FALSE;



$db['share_gs_db']['hostname'] = '10.60.41.6';
$db['share_gs_db']['username'] = 'adv_tracking_log';
$db['share_gs_db']['password'] = 'zgKGwXJfJRWrfnrW';
$db['share_gs_db']['database'] = 'adv_tracking';
$db['share_gs_db']['dbdriver'] = 'mysql';
$db['share_gs_db']['dbprefix'] = '';
$db['share_gs_db']['pconnect'] = TRUE;
$db['share_gs_db']['db_debug'] = TRUE;
$db['share_gs_db']['cache_on'] = FALSE;
$db['share_gs_db']['cachedir'] = '';
$db['share_gs_db']['char_set'] = 'utf8';
$db['share_gs_db']['dbcollat'] = 'utf8_general_ci';
$db['share_gs_db']['swap_pre'] = '';
$db['share_gs_db']['autoinit'] = TRUE;
$db['share_gs_db']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */