<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
/**
 *
 * This is a PHP standalone script to parse the imported file and send back its data as json format
 */

function parsing_date($str)
{
    return $str;
}

require_once 'MDB2.php';
include(dirname(__FILE__) . "/config/config.php");
include(dirname(__FILE__) . "/fileimport_grossista.php");
include(dirname(__FILE__) . "/remoteAddress.php");

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

} else {

    //$remoteAddr = new RemoteAddress();
    $swb_ip = $_POST['ip'];
    print $swb_ip;

    /* Insert a run log row to table. */
    $query = "SELECT * FROM $table_settings WHERE filetype = '$filetype' AND infotype = 'Importazione_sereno' AND disabled = 'f' ";
    $file_settings = $db->queryAll($query);

    /* check for file type */
    if (count($file_settings) >= 1) {

        /* move the uploded file to specific location */
        $info = pathinfo($_FILES['upload']['name']);
        $ext = $info['extension']; // get the extension of the file
        $path_data = explode(".", $_FILES['upload']['name']);
        $newname = $path_data[0] . time() . "." . $ext;
        $target = $imported_path . 'Importazione_sereno/' . $newname;
        move_uploaded_file($_FILES['upload']['tmp_name'], $target);

        /* Read the given fine */
        $Reader = new SpreadsheetReader($target);
        $file_data = array();
        foreach ($Reader as $Row) {
            $file_data[] = $Row;

        }

        $first_row = '';
        if (isset($file_data[0])) {
            $first_row = $file_data[0][0];
        }


        foreach ($file_settings as $settings) {
            $first_row_db[] = $settings['first_row'];
        }

        /*  process imported data */
        //$process_data = process_inorder($file_data,$db);

        //if($process_data['status']) {
        if (!isset($first_row_db)) {
            /* General import error */
            $response['error_type'] = 1;
            $response['error'] = 'Unauthorized file import';
        } else {

            if (in_array($first_row, $first_row_db)) {
                /* insert the current entry into runs table */
                $query = "INSERT INTO fileimport_runs (id_setting,start,filename,extra_fields) VALUES ('" . $settings['id'] . "','" . date('Y-m-d H:i:s') . "','" . $newname . "','')";
                $inserted_id = $db->exec($query);
                /* get the last inserted id */
                $new_query = "SELECT LASTVAL() as ins_id";
                $last_inserted = $db->queryAll($new_query);
                $inserted_id = $last_inserted[0]['ins_id'];

                $arr_preview = array();

                $conteggi = array('letti' => 0, 'nuovi' => 0, 'update' => 0, 'saltati' => 0, 'no_pod' => 0, 'altro' => 0);

                foreach ($file_data as $key => $each_item) {

                    $skip_update = false;
                    $skip_ins = false;
                    $no_pod = false;

                    if (($key == 0 && $each_item[0] == $first_row_db[0]) OR
                        ($key == 1 && $each_item[3] == 'Dati Cliente') OR
                        ($key == 2 && $each_item[0] == 'Data Contratto') OR
                        (count(array_filter($each_item)) == 0) OR
                        ($each_item[0] == 'Data del contratto' AND $each_item[1] == 'Scadenza recesso' AND $each_item[3] == 'Nome cliente') OR
                        ($each_item[55] == 'Nome del fornitore TELESELLING' AND $each_item[54] == 'Codice IBAN' AND $each_item[53] == 'istituto di credito del conto')
                    ) {
                        unset($file_data[$key]);
                    } else {
                        $conteggi["letti"]++;
                        $stato_preview = NULL;
                        $data_record = array();
                        if (count($each_item) >= 54) {
                            // check POD
                            if (!($each_item[30] != '' AND count($each_item[30]) > 0)) {
                                $skip_update = true;
                                $no_pod = true;

                                $stato_preview = 'POD mancante';
                                $conteggi["no_pod"]++;
                            }
                        } else {
                            $stato_preview = 'Numero colonne inatteso';
                            $skip_ins = true;
                            $conteggi["altro"]++;
                        }

                        //controllo esistenza colonna nazione
                        if (!isset($each_item[56])) {
                            $each_item[56] = null;
                        }
                        //var_dump($skip_update);
                        if (!$skip_ins) {
                            //var_dump($each_item);

                            // check esistenza inorder
                            $query = "SELECT * FROM import_inorder WHERE pod = '" . addslashes(utf8_encode(trim($each_item[30]))) . "' AND tipologia = 'SERENO' AND d_firma = '" . addslashes(utf8_encode(trim($each_item[0]))) . "' AND fornitore = '" . addslashes(utf8_encode(trim($each_item[55]))) . "'";
                            $check_pod_inorder = $db->queryAll($query);
                            if (isset($check_pod_inorder[0]) AND !$skip_update) { // update
                                // controllo se il contratto è già stato elaborato
                                if ($check_pod_inorder[0]['status'] == 'Da caricare' OR (($check_pod_inorder[0]['status'] == 'scartato' OR $check_pod_inorder[0]['status'] == 'pod_in_use') AND ($check_pod_inorder[0]['esito_caricamento'] = 'KO') )) {
                                    $query = "UPDATE  import_inorder SET 
                                                    id_run = '" . $inserted_id . "',
                                                    status = 'Da caricare',
                                                    caricamento_esito = NULL,
                                                    caricamento_esito_note = NULL,
                                                    d_firma = '" . addslashes(utf8_encode(trim($each_item[0]))) . "',
                                                    cod_prodotto = '" . addslashes(utf8_encode(trim($each_item[24]))) . "',
                                                    recesso_d_richiesta = '" . addslashes(utf8_encode(trim($each_item[2]))) . "',
                                                    recesso_note = NULL,
                                                    p_nome = '" . addslashes(utf8_encode(trim($each_item[3]))) . "',
                                                    p_cognome = '" . addslashes(utf8_encode(trim($each_item[4]))) . "',
                                                    o_ragione_sociale = '" . addslashes(utf8_encode(trim($each_item[6]))) . "',
                                                    o_piva = '" . addslashes(utf8_encode(trim($each_item[18]))) . "',
                                                    o_registro_imp = '" . addslashes(utf8_encode(trim($each_item[7]))) . "',
                                                    p_cf = '" . addslashes(utf8_encode(trim($each_item[17]))) . "',
                                                    o_ref_ruolo = '" . addslashes(utf8_encode(trim($each_item[5]))) . "',
                                                    p_nascita_data = '" . addslashes(utf8_encode(trim($each_item[10]))) . "',
                                                    p_nascita_nazione = '" . addslashes(utf8_encode(trim($each_item[56]))) . "',
                                                    p_nascita_provincia = '" . addslashes(utf8_encode(trim($each_item[9]))) . "',
                                                    p_nascita_comune = '" . addslashes(utf8_encode(trim($each_item[8]))) . "',
                                                    p_sesso = '" . addslashes(utf8_encode(trim($each_item[11]))) . "',
                                                    p_nascita_nazione_code = NULL,
                                                    p_cell = '" . addslashes(utf8_encode(trim($each_item[21]))) . "',
                                                    p_tel = '" . addslashes(utf8_encode(trim($each_item[19]))) . "',
                                                    p_fax = '" . addslashes(utf8_encode(trim($each_item[22]))) . "',
                                                    p_email = '" . addslashes(utf8_encode(trim($each_item[23]))) . "',
                                                    p_comune = '" . addslashes(utf8_encode(trim($each_item[12]))) . "',
                                                    p_cap = '" . addslashes(utf8_encode(trim($each_item[14]))) . "',
                                                    p_provincia = '" . addslashes(utf8_encode(trim($each_item[13]))) . "',
                                                    p_indirizzo = '" . addslashes(utf8_encode(trim($each_item[15]))) . "',
                                                    p_civico = '" . addslashes(utf8_encode(trim($each_item[16]))) . "',
                                                    imm_comune = '" . addslashes(utf8_encode(trim($each_item[27]))) . "',
                                                    imm_cap = '" . addslashes(utf8_encode(trim($each_item[29]))) . "',
                                                    imm_provincia = '" . addslashes(utf8_encode(trim($each_item[28]))) . "',
                                                    imm_toponimo = NULL,
                                                    imm_indirizzo = '" . addslashes(utf8_encode(trim($each_item[25]))) . "',
                                                    imm_civico = '" . addslashes(utf8_encode(trim($each_item[26]))) . "',
                                                    visura_lat = '" . addslashes(utf8_encode(trim($each_item[31]))) . "',
                                                    visura_long = '" . addslashes(utf8_encode(trim($each_item[32]))) . "',
                                                    allaccio_data = '" . addslashes(utf8_encode(trim($each_item[33]))) . "',
                                                    install_potenza_installata = '" . str_replace(',', '.', addslashes(utf8_encode(trim($each_item[34])))) . "',
                                                    username_portale_gse = '" . addslashes(utf8_encode(trim($each_item[35]))) . "',
                                                    password_portale_gse = '" . addslashes(utf8_encode(trim($each_item[36]))) . "',
                                                    codice_conv_conto_energia = '" . addslashes(utf8_encode(trim($each_item[37]))) . "',
                                                    tipo_cessione_energia = '" . addslashes(utf8_encode(trim($each_item[38]))) . "',
                                                    codice_conv_cessione_energia = '" . addslashes(utf8_encode(trim($each_item[39]))) . "',
                                                    pod = '" . addslashes(utf8_encode(trim($each_item[30]))) . "',
                                                    scambio_pa_f1 = '" . addslashes(utf8_encode(trim($each_item[40]))) . "',
                                                    scambio_pa_f2 = '" . addslashes(utf8_encode(trim($each_item[41]))) . "',
                                                    scambio_pa_f3 = '" . addslashes(utf8_encode(trim($each_item[42]))) . "',
                                                    scambio_ia_f1 = '" . addslashes(utf8_encode(trim($each_item[43]))) . "',
                                                    scambio_ia_f2 = '" . addslashes(utf8_encode(trim($each_item[44]))) . "',
                                                    scambio_ia_f3 = '" . addslashes(utf8_encode(trim($each_item[45]))) . "',
                                                    prod_ia_f1 = '" . addslashes(utf8_encode(trim($each_item[46]))) . "',
                                                    prod_ia_f2 = '" . addslashes(utf8_encode(trim($each_item[47]))) . "',
                                                    prod_ia_f3 = '" . addslashes(utf8_encode(trim($each_item[48]))) . "',
                                                    sdd_sottoscrittore_nome = '" . addslashes(utf8_encode(trim($each_item[49]))) . "',
                                                    sdd_sottoscrittore_cognome = '" . addslashes(utf8_encode(trim($each_item[50]))) . "',
                                                    sdd_cf = '" . addslashes(utf8_encode(trim($each_item[51]))) . "',
                                                    sdd_swift = '" . addslashes(utf8_encode(trim($each_item[52]))) . "',
                                                    sdd_banca = '" . addslashes(utf8_encode(trim($each_item[53]))) . "',
                                                    sdd_iban = '" . addslashes(utf8_encode(trim($each_item[54]))) . "',
                                                    fornitore = '" . addslashes(utf8_encode(trim($each_item[55]))) . "',
                                                    modified = now()
                                            WHERE id = '" . $check_pod_inorder[0]['id'] . "' ";
                                    $update_record = $db->exec($query);

                                    $stato_preview = 'Update inorder da caricare';
                                    $conteggi["update"]++;
                                } else {
                                    $stato_preview = "Il record risulta già elaborato"; //record già importato
                                    $conteggi["saltati"]++;
                                }
                            } else { // insert

                                if ($no_pod) {
                                    $status = 'no_pod';
                                    $stato_preview = 'Senza POD';
                                }
                                else {
                                    $conteggi["nuovi"]++;
                                    $status = 'Da caricare';
                                    $stato_preview = 'Nuovo';
                                }

                                $query = "INSERT INTO import_inorder (
                                                  id_run, status, caricamento_esito, caricamento_esito_note, d_firma, cod_prodotto, 
                                                  recesso_d_richiesta, recesso_note, p_nome, p_cognome, o_ragione_sociale, o_piva, 
                                                  o_registro_imp, p_cf, o_ref_ruolo, p_nascita_data, p_nascita_nazione, p_nascita_provincia, 
                                                  p_nascita_comune, p_sesso, p_nascita_nazione_code, p_cell, p_tel, p_fax, p_email, p_comune, p_cap, 
                                                  p_provincia, p_indirizzo, p_civico, imm_comune, imm_cap, imm_provincia, imm_toponimo, imm_indirizzo, 
                                                  imm_civico, visura_lat, visura_long, allaccio_data, install_potenza_installata, username_portale_gse, password_portale_gse, 
                                                  codice_conv_conto_energia, tipo_cessione_energia, codice_conv_cessione_energia, pod, scambio_pa_f1, scambio_pa_f2, 
                                                  scambio_pa_f3, scambio_ia_f1, scambio_ia_f2, scambio_ia_f3, prod_ia_f1, prod_ia_f2, prod_ia_f3, 
                                                  sdd_sottoscrittore_nome, sdd_sottoscrittore_cognome, sdd_cf, sdd_swift, sdd_banca, sdd_iban, 
                                                  codice, tipo_contratto, fornitore, tipologia, created, modified )
                                              VALUES (
                                                   '" . $inserted_id . "', '".$status."', NULL, NULL, '" . addslashes(utf8_encode(trim($each_item[0]))) . "', '" . addslashes(utf8_encode(trim($each_item[24]))) . "', 
                                                   '" . addslashes(utf8_encode(trim($each_item[2]))) . "', NULL, '" . addslashes(utf8_encode(trim($each_item[3]))) . "', '" . addslashes(utf8_encode(trim($each_item[4]))) . "', '" . addslashes(utf8_encode(trim($each_item[6]))) . "', '" . addslashes(utf8_encode(trim($each_item[18]))) . "', 
                                                   '" . addslashes(utf8_encode(trim($each_item[7]))) . "', '" . addslashes(utf8_encode(trim($each_item[17]))) . "', '" . addslashes(utf8_encode(trim($each_item[5]))) . "', '" . addslashes(utf8_encode(trim($each_item[10]))) . "', '" . addslashes(utf8_encode(trim($each_item[56]))) . "', '" . addslashes(utf8_encode(trim($each_item[9]))) . "', 
                                                   '" . addslashes(utf8_encode(trim($each_item[8]))). "', '" . addslashes(utf8_encode(trim($each_item[11]))) . "', null, '" . addslashes(utf8_encode(trim($each_item[21]))) . "', '" . addslashes(utf8_encode(trim($each_item[19]))) . "', '" . addslashes(utf8_encode(trim($each_item[22]))) . "', '" . addslashes(utf8_encode(trim($each_item[23]))) . "', '" . addslashes(utf8_encode(trim($each_item[12]))) . "', '" . addslashes(utf8_encode(trim($each_item[14]))) . "', 
                                                   '" . addslashes(utf8_encode(trim($each_item[13]))) . "', '" . addslashes(utf8_encode(trim($each_item[15]))) . "', '" . addslashes(utf8_encode(trim($each_item[16]))) . "', '" . addslashes(utf8_encode(trim($each_item[27]))) . "', '" . addslashes(utf8_encode(trim($each_item[29]))) . "', '" . addslashes(utf8_encode(trim($each_item[28]))) . "', NULL, '" . addslashes(utf8_encode(trim($each_item[25]))) . "', 
                                                   '" . addslashes(utf8_encode(trim($each_item[26]))) . "', '" . addslashes(utf8_encode(trim($each_item[31]))) . "', '" . addslashes(utf8_encode(trim($each_item[32]))) . "', '" . addslashes(utf8_encode(trim($each_item[33]))) . "', '" . str_replace(',', '.', addslashes(utf8_encode(trim($each_item[34])))). "', '" . addslashes(utf8_encode(trim($each_item[35]))) . "', '" . addslashes(utf8_encode(trim($each_item[36]))) . "', 
                                                   '" . addslashes(utf8_encode(trim($each_item[37]))) . "', '" . addslashes(utf8_encode(trim($each_item[38]))) . "', '" . addslashes(utf8_encode(trim($each_item[39]))) . "', '" . addslashes(utf8_encode(trim($each_item[30]))) . "', '" . addslashes(utf8_encode(trim($each_item[40]))) . "', '" . addslashes(utf8_encode(trim($each_item[41]))) . "', 
                                                   '" . addslashes(utf8_encode(trim($each_item[42]))) . "', '" . addslashes(utf8_encode(trim($each_item[43]))) . "', '" . addslashes(utf8_encode(trim($each_item[44]))) . "', '" . addslashes(utf8_encode(trim($each_item[45]))) . "', '" . addslashes(utf8_encode(trim($each_item[46]))) . "', '" . addslashes(utf8_encode(trim($each_item[47]))) . "', '" . addslashes(utf8_encode(trim($each_item[48]))) . "', 
                                                   '" . addslashes(utf8_encode(trim($each_item[49]))) . "', '" . addslashes(utf8_encode(trim($each_item[50]))) . "', '" . addslashes(utf8_encode(trim($each_item[51]))) . "', '" . addslashes(utf8_encode(trim($each_item[52]))) . "', '" . addslashes(utf8_encode(trim($each_item[53]))) . "', '" . addslashes(utf8_encode(trim($each_item[54]))) . "',
                                                   NULL, 'MANUTENZIONE', '" . addslashes(utf8_encode(trim($each_item[55]))) . "', 'SERENO', now(), now())";
                                $affected = $db->exec($query);



                            }
                        }


                        // inserimento record in tab storico
                        // si potrebbe evitare --- Da valutare successivamente
                        $query = "INSERT INTO fileimport_storico_sereno ( 
                                          id_run, data_contratto, id14_giorno, data_recesso, cl_nome, cl_cognome, cl_in_qualita_di, cl_ragione_sociale, cl_iscritta_al_registro_delle_imprese, 
                                          cl_nazione_nascita, cl_nato_a, cl_comune_di_nascita, cl_il, cl_sesso, cl_residente_in, cl_prov, cl_cap, cl_via, cl_n, 
                                          cl_codice_fiscale, cl_p_iva, cl_telefono_fisso, cl_cellulare, cl_fax, cl_email, codice_prodotto, 
                                          imp_indirizzo_via, imp_numero, imp_citta, imp_prov, imp_cap, imp_pod, imp_latitudine, imp_longitudine, 
                                          imp_data_conessione_impianto, imp_potenza_impianto, imp_username_portale_gse, imp_pwd_portale_gse, imp_codice_convenzione_conto_energia, imp_tipo_cessione_energia, 
                                          imp_codice_convenzione_cessione_energia, letture_mis_prelievo_scambio_f1, letture_mis_prelievo_scambio_f2,letture_mis_prelievo_scambio_f3, 
                                          letture_mis_immissioni_scambio_f1, letture_mis_immissioni_scambio_f2, letture_mis_immissioni_scambio_f3, 
                                          letture_mis_immissioni_produzioni_f1, letture_mis_immissioni_produzioni_f2, letture_mis_immissioni_produzioni_f3, 
                                          pag_nome, pag_cognome, pag_codice_fiscale, pag_codice_swift, pag_istituto_di_credito, pag_iban, fornitore, stato )
									  VALUES (
									      '" . $inserted_id . "','" . addslashes(utf8_encode(trim($each_item[0]))) . "','" . addslashes(utf8_encode(trim($each_item[1]))) . "','" . addslashes(utf8_encode(trim($each_item[2]))) . "', '" . addslashes(utf8_encode(trim($each_item[3]))) . "','" . addslashes(utf8_encode(trim($each_item[4]))) . "','" . addslashes(utf8_encode(trim($each_item[5]))) . "', '" . addslashes(utf8_encode(trim($each_item[6]))) . "','" . addslashes(utf8_encode(trim($each_item[7]))) . "', 
									      '" . addslashes(utf8_encode(trim($each_item[56]))) . "', '" . addslashes(utf8_encode(trim($each_item[8]))) . "', '" . addslashes(utf8_encode(trim($each_item[9]))) . "', '" . addslashes(utf8_encode(trim($each_item[10]))) . "', '" . addslashes(utf8_encode(trim($each_item[11]))) . "', '" . addslashes(utf8_encode(trim($each_item[12]))) . "','" . addslashes(utf8_encode(trim($each_item[13]))) . "', '" . addslashes(utf8_encode(trim($each_item[14]))) . "','" . addslashes(utf8_encode(trim($each_item[15]))) . "','" . addslashes(utf8_encode(trim($each_item[16]))) . "', 
									      '" . addslashes(utf8_encode(trim($each_item[17]))) . "','" . addslashes(utf8_encode(trim($each_item[18]))) . "','" . addslashes(utf8_encode(trim($each_item[19]))) . "', '" . addslashes(utf8_encode(trim($each_item[21]))) . "', '" . addslashes(utf8_encode(trim($each_item[22]))) . "','" . addslashes(utf8_encode(trim($each_item[23]))) . "', '" . addslashes(utf8_encode(trim($each_item[24]))) . "', 
									      '" . addslashes(utf8_encode(trim($each_item[25]))) . "','" . addslashes(utf8_encode(trim($each_item[26]))) . "', '" . addslashes(utf8_encode(trim($each_item[27]))) . "','" . addslashes(utf8_encode(trim($each_item[28]))) . "','" . addslashes(utf8_encode(trim($each_item[29]))) . "', '" . addslashes(utf8_encode(trim($each_item[30]))) . "','" . addslashes(utf8_encode(trim($each_item[31]))) . "','" . addslashes(utf8_encode(trim($each_item[32]))) . "', 
									      '" . addslashes(utf8_encode(trim($each_item[33]))) . "', '" . str_replace(',', '.', addslashes(utf8_encode(trim($each_item[34])))) . "', '" . addslashes(utf8_encode(trim($each_item[35]))) . "',  '" . addslashes(utf8_encode(trim($each_item[36]))) . "','" . addslashes(utf8_encode(trim($each_item[37]))) . "','" . addslashes(utf8_encode(trim($each_item[38]))) . "',
									      '" . addslashes(utf8_encode(trim($each_item[39]))) . "','" . addslashes(utf8_encode(trim($each_item[40]))) . "','" . addslashes(utf8_encode(trim($each_item[41]))) . "', '" . addslashes(utf8_encode(trim($each_item[42]))) . "', 
									      '" . addslashes(utf8_encode(trim($each_item[43]))) . "','" . addslashes(utf8_encode(trim($each_item[44]))) . "', '" . addslashes(utf8_encode(trim($each_item[45]))) . "', 
									      '" . addslashes(utf8_encode(trim($each_item[46]))) . "','" . addslashes(utf8_encode(trim($each_item[47]))) . "', '" . addslashes(utf8_encode(trim($each_item[48]))) . "', 
									      '" . addslashes(utf8_encode(trim($each_item[49]))) . "','" . addslashes(utf8_encode(trim($each_item[50]))) . "','" . addslashes(utf8_encode(trim($each_item[51]))) . "', '" . addslashes(utf8_encode(trim($each_item[52]))) . "','" . addslashes(utf8_encode(trim($each_item[53]))) . "','" . addslashes(utf8_encode(trim($each_item[54]))) . "', '" . addslashes(utf8_encode(trim($each_item[55]))) . "', '" . $stato_preview . "')";
                        $affected = $db->exec($query);

                        // utf8_encode for preview
                        foreach ($each_item as $ks => $v) {
                            $file_data[$key][$ks] = utf8_encode($v);
                        }

                        $file_data[$key]['stato_preview'] = $stato_preview;
                    }


                }

                /* set the response data */
                $data = array();
                $data['run_id'] = $inserted_id;
                $data['imported_data'] = $file_data;
                $data['conteggi'] = $conteggi;
                $response['data'] = $data;
                $response['status'] = TRUE;
            } else {

                /* General import error */
                $response['error_type'] = 1;
                $data['imported_data'] = array();
                $response['data'] = $data;
                $data['conteggi'] = null;
                $response['error'] = 'Field mis-match in imported file';
                $response['status'] = FALSE;

                /* delete the file from system */
                unlink($target);
            }
        }
        /*}else{
            // process error
            $response['error_type'] = 2;
            $response['error'] = 'Check the file types';
            $data['imported_data'] = $process_data['data'];
            $response['data'] = $data;
        }*/


    } else {

        /* General import error */
        $response['error_type'] = 1;
        $response['error'] = 'Unauthorized file import';

        /* delete the file from system */
        //unlink($target);
    }
}

echo json_encode($response);
exit;
