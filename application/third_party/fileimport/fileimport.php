<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_STRICT);
/**
 * 
 * This is a PHP standalone script to parse the imported file and send back its data as json format
 */

require_once 'MDB2.php';
include(dirname(__FILE__)."/config/config.php");
include(dirname(__FILE__)."/fileimport_grossista.php");
include(dirname(__FILE__)."/remoteAddress.php");

/* If you need to parse XLS files, include php-excel-reader */
require('spreadsheet-reader/php-excel-reader/excel_reader2.php');
require('spreadsheet-reader/SpreadsheetReader.php');

$filetype = $_POST['filetype'];
//$user_id  = $_POST['user_id'];
//$ip_address = $_POST['ip_address'];

$response = array();
$response['status'] = FALSE;

$db = MDB2::connect($dsn, $db_options);
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);
if (PEAR::isError($db)) {
	
	/* General import error */
	$response['error_type'] = 1;
	$response['error'] = 'Database error, Could not connect to PGSQL';
}else{
	
	//$remoteAddr = new RemoteAddress();
	$swb_ip = $_POST['ip'];
	print $swb_ip;
		
	/* Insert a run log row to table. */
	$query = "SELECT * FROM $table_settings WHERE filetype = '$filetype' AND position('$swb_ip' IN source_ips)>0 AND disabled = 'f' ";
	$file_settings = $db->queryAll($query);
	
	/* check for file type */
	if(count($file_settings) >=1){
		
		/* move the uploded file to specific location */
		$info = pathinfo($_FILES['upload']['name']);
		$ext = $info['extension']; // get the extension of the file
		$path_data = explode(".", $_FILES['upload']['name']);
		$newname = $path_data[0].time().".".$ext;
		$target = $imported_path.$newname;
		move_uploaded_file( $_FILES['upload']['tmp_name'], $target);
		
		/* Read the given fine */
		$Reader = new SpreadsheetReader($target);
		$file_data = array();
		foreach ($Reader as $Row)
		{
			$file_data[] = $Row;
		}
		
		$first_row ='';
		if(isset($file_data[0])){
			$first_row = $file_data[0][0];
		}
		foreach ($file_settings as $settings){
			$first_row_db[] = $settings['first_row'];
		}
		
		/*  process imported data */
		$process_data = process_grossista($file_data,$db);
		
		if($process_data['status']){
			if(!isset($first_row_db)){
				/* General import error */
				$response['error_type'] = 1;
				$response['error'] = 'Unauthorized file import';
			}else{
				if(in_array($first_row,$first_row_db)){
					/* insert the current entry into runs table */
					$query = "INSERT INTO fileimport_runs (id_setting,start,filename,extra_fields) VALUES ('".$settings['id']."','".date('Y-m-d H:i:s')."','".$newname."','')";
					$inserted_id = $db->exec($query);
					/* get the last inserted id */
					$new_query ="SELECT LASTVAL() as ins_id";
					$last_inserted = $db->queryAll($new_query);
					$inserted_id = $last_inserted[0]['ins_id'];
					foreach ($file_data as $key => $each_item){
						if($key <> 0){
							if(count(array_filter($each_item)) == 0){
								unset($file_data[$key]);
							}else{
								/* insert parsed output into fileimport_parser_grossista table */								
								$query = "INSERT INTO fileimport_parser_grossista (id_run
											d_import,
											d_process,
											operazione, riferimento_listino, pod, numero_presa, cognome, 
            nome, ragione_sociale, partita_iva, codice_fiscale, toponimo_descrizione, 
            toponimo, indirizzo, numero_civico, cat, citta, sigla_provincia, 
            telefono, decorrenza, termine, mercato_libero_vincolato, tensione, 
            tipo_contatore, potenza_disponibile, energia_annua_stimata_kwh, 
            distributore, calcola_energia_pulita, utilizzo, codice_pratica, 
            codice_assegnato_dal_dl, esito_richiesta, commento, data_disattivazione, 
            lettura_energia_attiva_f1, lettura_energia_attiva_f2, lettura_energia_attiva_f3, 
            lettura_energia_reattiva_f1, lettura_energia_reattiva_f2, lettura_energia_reattiva_f3, 
            lettura_potenza_f1, lettura_potenza_f2, lettura_potenza_f3, info_morosita, 
            created,modified)
										VALUES
										('".$inserted_id."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','".$each_item[0]."','".$each_item[1]."','".$each_item[2]."','".$each_item[3]."','".$each_item[4]."','".$each_item[5]."','".$each_item[6]."','".$each_item[7]."','".$each_item[8]."','".$each_item[9]."','".$each_item[10]."','".$each_item[11]."','".$each_item[12]."','".$each_item[13]."','".$each_item[14]."','".$each_item[15]."','".$each_item[16]."','".$each_item[17]."','".$each_item[18]."','".$each_item[19]."','".$each_item[20]."','".$each_item[21]."','".$each_item[22]."','".$each_item[23]."','".$each_item[24]."','".$each_item[25]."','".$each_item[26]."','".$each_item[27]."','".$each_item[28]."','".$each_item[29]."','".$each_item[30]."','".$each_item[31]."','".$each_item[32]."','".$each_item[33]."','".$each_item[34]."','".$each_item[35]."','".$each_item[36]."','".$each_item[37]."','".$each_item[38]."','".$each_item[39]."','".$each_item[40]."','".$each_item[41]."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')";
								
								$affected = $db->exec($query);
								
							}
						}
					}
			
					/* set the response data */
					$data = array();
					$data['run_id'] = $inserted_id;
					$data['imported_data'] = $file_data;
					$response['data'] = $data;
					$response['status'] = TRUE;
				}else{
					
					/* General import error */
					$response['error_type'] = 1;
					$response['error'] = 'Field mis-match in imported file';
					
					/* delete the file from system */
					unlink($target);
				}
			}
		}else{
			/* process error */
			$response['error_type'] = 2;
			$response['error'] = 'Check the file types';
			$data['imported_data'] = $process_data['data'];
			$response['data'] = $data;
		}
	
		
	
	
	
	}else{
		
		/* General import error */
		$response['error_type'] = 1;
		$response['error'] = 'Unauthorized file import';
		
		/* delete the file from system */
		//unlink($target);
	}
}

echo json_encode($response);exit;



