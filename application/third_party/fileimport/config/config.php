<?php

// DB
$dsn = array(
    'phptype'  => 'pgsql',
    'username' => 'postgres',
    'password' => 'redhat',
    'hostspec' => '127.0.0.1',
    'database' => 'giotto_uat',
);


$db_options = array(
    'debug'       => 2,
    'portability' => MDB2_PORTABILITY_ALL,
);
$table_settings = 'fileimport_settings';
$table_run 		= 'fileimport_runs';
$table_grossista = 'fileimport_parser_grossista';
//$table_categories = 'eai_list_asset_categories';
//$table_activities = 'activities';

//API
$api_user = "admin";
$api_password = "scogliera";
//$api_url = "https://10.10.17.20/administration/API/interface.php?username=$api_user&password=$api_passowrd&action=export_reports&serverTimeStart=$min_date&serverTimeEnd=$max_date &fileFormat=xml";
$api_url = "https://10.10.17.20/administration/API/interface.php?username=$api_user&password=$api_password&action=export_reports&fileFormat=xml";


// XML PATH
	//$imported_path = "/tmp/bmcl15_importer/imported/";
$imported_path = "/Applications/MAMP/htdocs/giotto/assets/fileimport/imported/";

?>
