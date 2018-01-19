<?php


function process_grossista($data, $db_instance){
	header('Content-Type: application/json; charset=UTF-8');
	/* Get the list of assets categories . */
	
	$process_resposne = array();
	$process_resposne['status'] = true;
	$process_resposne['error'] ='';
	foreach ($data as $data_key => $data_each_item){
		if($data_key <> 0){
			if(count(array_filter($data_each_item)) == 0){
				unset($data[$data_key]);
			}/*else{
					$process_resposne['status'] = FALSE;
					$process_resposne['error'][] = $data_key;
			}*/
		}
	}
	$process_resposne['data'] = $data;
	return $process_resposne;
}