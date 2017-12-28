<div class="col-md-12">
	<div class="widget stacked ">
		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>Clienti Dettagio</h3>
		</div>
		<!-- /.widget-header -->

		<div class="widget-content">
			<section id="companies">
				<div class="col-md-12">
					<!--  <div class="col-sm-5">
							   <h4>Cliente Titolare della forniture</h4>
									<b>POD Cliente:</b><?php echo $pod; ?><br/>
									<b>Cod Cliente:</b><?php echo $indirizzo[0]->id; ?><br/>
									<b>Name del Cliente :</b><?php echo $indirizzo[0]->p_nome; ?><br/>
									<b>Cogname del Cliente:</b><?php echo $indirizzo[0]->p_cognome; ?><br/>
									<b>Luogo de nascita:</b><?php echo $indirizzo[0]->p_nacita_comune; ?><br/>
									<b>Data de nascita:</b><?php echo $indirizzo[0]->p_nacita_data; ?><br/>
									<b>Codice fiscale:</b><?php echo $indirizzo[0]->po_codice_cliente; ?><br/>
									<b>Indirizzo di residenza:</b><?php echo $indirizzo[0]->indirizzo; ?><br/>
									<b>Telefone:</b><?php echo $indirizzo[0]->tel; ?><br/>
									<b>Cellulare:</b><?php echo $indirizzo[0]->cell; ?><br/>
									<b>Email:</b><?php echo $indirizzo[0]->email; ?><br/>
									<b>COMUNE CATASTO:</b><?php echo $indirizzo[0]->comune; ?><br/>	
									<br/>
									<b>Sito di installazione:</b><?php echo $indirizzo[0]->email; ?><br/>
									<b>Via Indirizzo di Comune:</b><?php echo $indirizzo[0]->email; ?><br/>
									<h5>Dati fornitura elettrica :</h5><br/>
									<b>Tension di fornitura:</b><?php echo $forniture[0]->tensione_livello; ?><br/>
									<b>Potenza Disponsible:</b><?php echo $forniture[0]->pot_disponibile; ?><br/>
									<b>Potenza impagnata:</b><?php echo $forniture[0]->pot_impegnata; ?><br/>
									
								</div>
								<div class="col-sm-2">
								
								</div> 
								<div class="col-sm-5">
									<b>Identicativi sito di installazione:</b><?php echo $indirizzo[0]->email; ?><br/>
									<b>Indirizzo di installazione:</b><?php echo $indirizzo[0]->email; ?><br/>
									<b>Latitudine Long :</b><?php echo $impianti[0]->imp_lat."/".$impianti[0]->imp_long; ?><br/>
									<b>Particella:</b><?php echo $immobile[0]->particella; ?><br/>
									<b>SubAlterno:</b><?php echo $immobile[0]->subalterno; ?><br/>
									<b>Fogio catastale:</b><?php echo $immobile[0]->foglio; ?><br/>
									<h5>Dati progettazione:</h5><?php echo $indirizzo[0]->email; ?><br/>
									<b>progettazione Defnitiva:</b><?php echo $indirizzo[0]->prog_potenza; ?><br/>
									<br/>
									<b>Configurazione:</b><br/>
									<span class="col-sm-10 pull-right">
											<b>Moduli:</b><?php echo $indirizzo[0]->email; ?><br/>
											<b>Num moduli:</b><?php echo $indirizzo[0]->email; ?><br/>
											<b>Inverter:</b><?php echo $indirizzo[0]->email; ?><br/>
											<b>Num Inverter:</b><?php echo $indirizzo[0]->email; ?><br/>
									</span>
									<div class="clearfix"></div>
									<br/>
									<b>Tipo montaggio:</b><?php echo $impianti[0]->tipo_montaggio; ?><br/>
									<b>Tipo ancoraggio:</b><?php echo $impianti[0]->tipo_ancoraggio; ?><br/>
									<b>Tipo sottostruttura:</b><?php echo $impianti[0]->tipo_sottostruttura; ?><br/>	
								</div>-->

					<div class="col-sm-12">
						<div class="col-sm-5">
							<h4>Dati Immobile</h4>
							<b>INDIRIZZO IMMMOBILE:</b><?php echo $address_immobile[0]->presso.",".$address_immobile[0]->toponimo.",".$address_immobile[0]->indirizzo.",".$address_immobile[0]->civico.",".$address_immobile[0]->comune.",".$address_immobile[0]->provincia.",".$address_immobile[0]->cap.",".$address_immobile[0]->nazione; ?><br />
							<b>TIPO IMMOBILE:</b><?php if(isset($immobile[0]->tipo_immobile)) echo $immobile[0]->tipo_immobile; ?><br />
							<b>LONGITUDINE IMMOBILE:</b><?php if(isset($immobile[0]->visura_long)) echo $immobile[0]->visura_long; ?><br />
							<b>LATITUDINE IMMOBILE:</b><?php if(isset($immobile[0]->visura_lat)) echo $immobile[0]->visura_lat; ?><br />
							<b>USO:</b><?php if(isset($immobile[0]->uso)) echo $immobile[0]->uso; ?><br />
							<b>VINCOLI:</b><?php if(isset($immobile[0]->vincoli)) echo $immobile[0]->vincoli; ?><br />
							<b>UBICAZIONE:</b><?php if(isset($immobile[0]->ubicazione)) echo $immobile[0]->ubicazione; ?><br />
							<b>TIPO DI TETTO:</b><?php if(isset($immobile[0]->tipo_tetto)) echo $immobile[0]->tipo_tetto; ?><br />
							<b>ORIENTAMENTO:</b><?php if(isset($immobile[0]->orientamento)) echo $immobile[0]->orientamento; ?><br />
							<b>FOGLIO:</b><?php if(isset($immobile[0]->foglio)) echo $immobile[0]->foglio; ?><br />
							<b>PARTICELLA:</b><?php if(isset($immobile[0]->particella)) echo $immobile[0]->particella; ?><br />
							<b>SUBALTERNO:</b><?php if(isset($immobile[0]->subalterno)) echo $immobile[0]->subalterno; ?><br />
							<b>COMUNE CATASTO:</b><?php if(isset($immobile[0]->tipo_indirizzo) && ($immobile[0]->tipo_indirizzo == "IMMOBILE")){ echo $immobile[0]->comune;} ?><br />
							<b>CODICE COMUNE:</b><?php if(isset($immobile[0]->tipo_indirizzo) && ($immobile[0]->tipo_indirizzo == "IMMOBILE"))echo $immobile[0]->comune_id; ?><br />
							<b>VINCOLO NON BLOCCANTE:</b><?php if(isset($immobile[0]->visura_vincolo_bloccante)) echo $immobile[0]->visura_vincolo_bloccante; ?><br />
							<b>NOTE VISURA:</b><?php if(isset($immobile[0]->visura_note)) echo $immobile[0]->visura_note; ?><br />

							<hr />

							<h4>Dati Fornitura Elettrica</h4>

							<b>Tension di fornitura:</b><?php if(isset($forniture[0]->tensione_livello)) echo $forniture[0]->tensione_livello; ?><br />
							<b>Potenza Disponsible:</b><?php if(isset($forniture[0]->pot_disponibile)) echo $forniture[0]->pot_disponibile; ?><br />
							<b>Potenza impagnata:</b><?php if(isset($forniture[0]->pot_impegnata)) echo $forniture[0]->pot_impegnata; ?><br />

							<hr />
							<h4>AUTORIZZAZIONE</h4>
							<b>TIPO RICHIESTA AUTORIZZAZIONE:</b><?php if(isset($impianti[0]->aut_rich_tipo)) echo $impianti[0]->aut_rich_tipo; ?><br />

							<hr />
							<h4>RICHIESTA ALLACCIO</h4>
							<b>DATA INVIO RICHIESTA DI ALLACCIO:</b><?php if(isset($impianti[0]->all_rich_d_invio)) echo $impianti[0]->all_rich_d_invio; ?><br />
							<b>CORRISPETTIVO RICHIESTA DI ALLACCIO:</b><?php if(isset($impianti[0]->all_rich_importo_prev)) echo $impianti[0]->all_rich_importo_prev; ?><br />
							<b>DATA PREVENTIVO ALLACCIO:</b><?php if(isset($impianti[0]->all_prev_data)) echo $impianti[0]->all_prev_data; ?><br />
							<b>TIPO LAVORO PREVENTIVO:</b><?php if(isset($impianti[0]->all_prev_tipo_lavori)) echo $impianti[0]->all_prev_tipo_lavori; ?><br />
							<b>CORRISPETTIVO PREVENTIVO:</b><?php if(isset($impianti[0]->all_prev_importo)) echo $impianti[0]->all_prev_importo; ?><br />
							<b>ITER AUTORIZZATIVO:</b><?php if(isset($impianti[0]->all_prev_iter_aut_sn)) echo $impianti[0]->all_prev_iter_aut_sn; ?><br />
							<b>CORRISPETTIVO ITER AUTORIZZATIVO:</b><?php if(isset($impianti[0]->all_prev_importo_iter_aut)) echo $impianti[0]->all_prev_importo_iter_aut; ?><br />

							<hr />
							<h4>INSTALLAZIONE</h4>
							<b>DATA PREVISIONE INSTALLAZIONE:</b><?php if(isset($impianti[0]->pre_install_d_installazione)) echo $impianti[0]->pre_install_d_installazione; ?><br />
							<b>NOTE CONTATTO PRE-INSTALLAZIONE:</b><?php if(isset($impianti[0]->pre_install_note)) echo $impianti[0]->pre_install_note; ?><br />
							<b>DATA FINE INSTALLAZIONE:</b><?php if(isset($impianti[0]->install_data_fine)) echo $impianti[0]->install_data_fine; ?><br />
							<b>NOTE INSTALLAZIONE:</b><?php if(isset($impianti[0]->install_note)) echo $impianti[0]->install_note; ?><br />

						</div>
						<div class="col-sm-2">
							<div class="clearfix"></div>
						</div>
						<div class="col-sm-5">
							<h4>Sopralluogo</h4>
							<b>DATA INIZIO SOPRALLUOGO:</b><?php if(isset($immobile[0]->soprall_d_start)) echo $immobile[0]->soprall_d_start; ?><br />
							<b>DATA FINE SOPRALLUOGO:</b><?php if(isset($immobile[0]->soprall_d_end)) echo $immobile[0]->soprall_d_end; ?><br />
							<b>NOTE SOPRALLUOGO:</b><?php if(isset($immobile[0]->sopralluogo_note)) echo $immobile[0]->sopralluogo_note; ?><br />
							<b>RICHIESTE CLIENTE:</b><?php if(isset($immobile[0]->sopralluogo_rich_cliente)) echo $immobile[0]->sopralluogo_rich_cliente; ?><br />
							<b>LATITUDINE IMPIANTO:</b><?php if(isset($impianti[0]->imp_lat)) echo $impianti[0]->imp_lat; ?><br />
							<b>LONGITUDINE IMPIANTO:</b><?php if(isset($impianti[0]->imp_long)) echo $impianti[0]->imp_long; ?><br />
							<b>POTENZA INSTALLABILE:</b><?php if(isset($impianti[0]->pot_installabile)) echo $impianti[0]->pot_installabile; ?><br />
							<b>TILT:</b><?php if(isset($impianti[0]->imp_tilt)) echo $impianti[0]->imp_tilt; ?><br />
							<b>AZIMUT:</b><?php if(isset($impianti[0]->imp_azimuth)) echo $impianti[0]->imp_azimuth; ?><br />
							<b>LOCALI INTERNI PER INSTALLAZIONE INVERTER:</b><?php if(isset($impianti[0]->stanza_inverter)) echo $impianti[0]->stanza_inverter; ?><br />
							<b>TIPOLOGIA MONTAGGIO:</b><?php if(isset($impianti[0]->tipo_montaggio)) echo $impianti[0]->tipo_montaggio; ?><br />
							<b>TIPOLOGIA ANCORAGGIO:</b><?php if(isset($impianti[0]->tipo_ancoraggio)) echo $impianti[0]->tipo_ancoraggio; ?><br />
							<b>TIPOLOGIA SOTTOSTRUTTURA:</b><?php if(isset($impianti[0]->tipo_sottostruttura)) echo $impianti[0]->tipo_sottostruttura; ?><br />
							<b>NOTE INSTALLAZIONE:</b><?php if(isset($impianti[0]->note_per_installazione)) echo $impianti[0]->note_per_installazione; ?><br />
							<hr />
							<h4>Progettazione</h4>
							<b>POTENZA:</b><?php if(isset($impianti[0]->prog_potenza)) echo $impianti[0]->prog_potenza; ?><br />
							<b>NOTE PER L’INSTALLAZIONE:</b><?php if(isset($impianti[0]->prog_note_install)) echo $impianti[0]->prog_note_install; ?><br />
							<b>NOTE PROGETTAZIONE:</b><?php if(isset($impianti[0]->prog_note_esito)) echo $impianti[0]->prog_note_esito; ?><br />

							<hr />
							<h4>FINE LAVORI ENEL</h4>
							<b>DATA INVIO RDE:</b><?php if(isset($impianti[0]->all_finelav_d_invio_rde)) echo $impianti[0]->all_finelav_d_invio_rde; ?><br />
							<b>DATA INVIO FINE LAVORI:</b><?php if(isset($impianti[0]->all_finelav_d_invio_finelav)) echo $impianti[0]->all_finelav_d_invio_finelav; ?><br />
							<b>CORRISPETTIVO FINE LAVORI:</b><?php if(isset($impianti[0]->all_finelav_bonifico_importo)) echo $impianti[0]->all_finelav_bonifico_importo; ?><br />

							<hr />
							<h4>ALLACCIO</h4>
							<b>DATA ALLACCIO PIANIFICATO:</b><?php if(isset($impianti[0]->all_pianif_data)) echo $impianti[0]->all_pianif_data; ?><br />
							<b>NOTE PIANIFICAZIONE ALLACCIO:</b><?php if(isset($impianti[0]->all_pianif_note)) echo $impianti[0]->all_pianif_note; ?><br />
							<b>DATA ALLACCIO RIPIANIFICATO:</b><?php if(isset($impianti[0]->all_pianif2_data)) echo $impianti[0]->all_pianif2_data; ?><br />
							<b>NOTE RIPIANIFICAZIONE ALLACCIO:</b><?php if(isset($impianti[0]->all_pianif2_note)) echo $impianti[0]->all_pianif2_note; ?><br />
							<b>DATA ALLACCIO:</b><?php if(isset($impianti[0]->allaccio_data)) echo $impianti[0]->allaccio_data; ?><br />
							<b>NOTE ALLACCIO:</b><?php if(isset($impianti[0]->all_note)) echo $impianti[0]->all_note; ?><br />
						</div>


					</div>
					<div class="clearfix"></div>
					<hr />
					<div class="row-fluid">
						<h4>
							<strong>Production Details</strong>
						</h4>
					</div>
					<div id="client_details_production"
						style="width: 1108px; height: 350px;"></div>
				</div>
				<div class="clearfix"></div>
				<hr />
				<div class="col-md-12">
					<ul class="nav nav-tabs" role="tablist">
						<li class="active"><a href="#installazione" role="tab"
							data-toggle="tab">INSTALLAZIONE</a></li>
						<li><a href="#installazione_review" role="tab" data-toggle="tab">INSTALLAZIONE
								REVIEW</a></li>
						<li><a href="#progettazione_definitiva" role="tab"
							data-toggle="tab">PROGETTAZIONE DEFINITIVA</a></li>
						<li><a href="#progettazione_esecutive" role="tab"
							data-toggle="tab">PROGETTAZIONE ESECUTIVE</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="installazione">
							<div class="clearfix"></div>
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>Quantity</th>
										<th>Prodotto</th>
										<th>Modello</th>
										<th>Marca</th>
										<th>SN</th>
									</tr>
								</thead>
								<tbody>
													<?php
													foreach ( $installazione [0] as $item ) {
														echo "<tr>";
														echo "<td>" . $item ["qty"] . "</td>";
														echo "<td>" . $item ["prodotto"] . "</td>";
														echo "<td>" . $item ["modello"] . "</td>";
														echo "<td>" . $item ["marca"] . "</td>";
														echo "<td>" . $item ["sn"] . "</td>";
														echo "</tr>";
													}
													?>
												</tbody>

							</table>
						</div>




						<div class="tab-pane " id="installazione_review">
							<div class="clearfix"></div>
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>Quantity</th>
										<th>Prodotto</th>
										<th>Modello</th>
										<th>Marca</th>
										<th>SN</th>
									</tr>
								</thead>
								<tbody>
											<?php
											foreach ( $installazione [1] as $item ) {
												echo "<tr>";
												echo "<td>" . $item ["qty"] . "</td>";
												echo "<td>" . $item ["prodotto"] . "</td>";
												echo "<td>" . $item ["modello"] . "</td>";
												echo "<td>" . $item ["marca"] . "</td>";
												echo "<td>" . $item ["sn"] . "</td>";
												echo "</tr>";
											}
											?>
										</tbody>
							</table>
						</div>


						<div class="tab-pane " id="progettazione_definitiva">
							<div class="clearfix"></div>
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>Quantity</th>
										<th>Prodotto</th>
										<th>Modello</th>
										<th>Marca</th>
										<th>SN</th>
									</tr>
								</thead>
								<tbody>
											<?php
											foreach ( $progettazione [0] as $item ) {
												echo "<tr>";
												echo "<td>" . $item ["qty"] . "</td>";
												echo "<td>" . $item ["prodotto"] . "</td>";
												echo "<td>" . $item ["modello"] . "</td>";
												echo "<td>" . $item ["marca"] . "</td>";
												echo "<td>" . $item ["sn"] . "</td>";
												echo "</tr>";
											}
											?>
										</tbody>
							</table>
						</div>


						<div class="tab-pane " id="progettazione_esecutive">
							<div class="clearfix"></div>
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>Quantity</th>
										<th>Prodotto</th>
										<th>Modello</th>
										<th>Marca</th>
										<th>SN</th>
									</tr>
								</thead>
								<tbody>
											<?php
											foreach ( $progettazione [1] as $item ) {
												echo "<tr>";
												echo "<td>" . $item ["qty"] . "</td>";
												echo "<td>" . $item ["prodotto"] . "</td>";
												echo "<td>" . $item ["modello"] . "</td>";
												echo "<td>" . $item ["marca"] . "</td>";
												echo "<td>" . $item ["sn"] . "</td>";
												echo "</tr>";
											}
											?>
										</tbody>
							</table>
						</div>
					</div>

				</div>
		
		</div>

		<hr />
		</section>
	</div>
</div>
</div>

<input type="hidden" id="data_hidden_productions_count"
	name="data_hidden_productions_count" class=""
	value="<?php echo count($alarm_production_data);?>">
</div>
<?php
if (count ( $alarm_production_data ) > 0) {
	
	foreach ( $alarm_production_data as $key => $porduction ) {
		echo '<input type="hidden" name ="pod_hidden_production_' . $key . '" id="pod_hidden_production_' . $key . '" value="' . $porduction ['pod'] . '">';
		unset ( $porduction ['pod'] );
		echo '<div id="data_hidden_production_' . $key . '" class="hide">' . json_encode ( $porduction ) . '</div>';
	}
}
?>				