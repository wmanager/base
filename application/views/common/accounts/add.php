<script type="text/ng-template" id="customerTpl.html">
   <a tabindex="-1">
   	<span>{{match.model.p_nome}} {{match.model.p_cognome}}</span>
   	<span>{{match.model.o_ragione_sociale}}</span><br>
   	<small>{{match.model.p_nascita_comune}}</small>
   </a>
</script>

<div class="col-md-12">
	<div class="widget stacked ">

		<div class="widget-header trouble-header">
			<i class="icon-pencil"></i>
	              <?php
															if ($this->uri->segment ( 5 )) {
																echo '<h3 style="color:white;">Edit Contatto #' . sprintf ( "%05d", $this->uri->segment ( 5 ) ) . '</h3>';
															} else {
																echo '<h3 style="color:white;">Nuovo Contatto</h3>';
															}
															?>
					
	              
	            </div>
		<!-- /.widget-header -->
		<div class="widget-content">
			<section id="trouble" ng-controller="FastThread"
				ng-init="acc_id='<?=$this->uri->segment(4)?>';">

				<div class="row-fluid">
					<ng-form name="fastThreadForm" ng-hide="busy">
					<div class="col-md-12">



						<div class="form-group col-md-6">
							<select class="form-control"
								ng-options="item.key as item.label for item in form_types"
								ng-model="selected.type" ng-disabled="trouble_id"
								ng-change="selected.subtype = '';">
							</select>
						</div>

						<div class="form-group col-md-12">
							<label class="control-label"><b>Descrizione</b></label>
							<textarea class="form-control" ng-model="selected.description"></textarea>
						</div>
						<div class="clearfix"></div>



						<hr>
						<h5>Dati contatto del subentrante</h5>
						<fieldset>
							<div class="form-group col-md-12">
								<div class="col-md-6">
									<label>Nome *</label> <input type="text" class="form-control"
										name="p_nome" ng-model="form_data.p_nome" ng-required="true">
								</div>
								<div class="col-md-6">
									<label>Cognome *</label> <input type="text"
										class="form-control" name="p_cognome"
										ng-model="form_data.p_cognome" ng-required="true">
								</div>

								<div class="col-md-2">
									<label>Via/Piazza *</label> <input type="text"
										class="form-control" name="contatto_toponimo"
										ng-model="form_data.contatto_toponimo" ng-required="true">
								</div>
								<div class="col-md-4">
									<label>Indirizzo *</label> <input type="text"
										class="form-control" name="contatto_indirizzo"
										ng-model="form_data.contatto_indirizzo" ng-required="true">
								</div>
								<div class="col-md-2">
									<label>Numero *</label> <input type="text" class="form-control"
										name="contatto_civico" ng-model="form_data.contatto_civico"
										ng-required="true">
								</div>
								<div class="col-md-2">
									<label>Estensione</label> <input type="text"
										class="form-control" name="contatto_estensione"
										ng-model="form_data.contatto_estensione">
								</div>
								<div class="col-md-2">
									<label>Scala</label> <input type="text" class="form-control"
										name="contatto_scala" ng-model="form_data.contatto_scala">
								</div>
								<div class="col-md-2">
									<label>Piano</label> <input type="text" class="form-control"
										name="contatto_piano" ng-model="form_data.contatto_piano">
								</div>
								<div class="col-md-2">
									<label>Interno</label> <input type="text" class="form-control"
										name="contatto_interno" ng-model="form_data.contatto_interno">
								</div>

								<div class="col-md-6">
									<label>Nazione *</label> <select class="form-control"
										ng-model="form_data.contatto_nazione" ng-required="true">
										<option ng-repeat="n in countries" ng-value="n.name">{{n.name}}</option>
									</select>
								</div>


								<div class="col-md-2">
									<label>Provincia *</label> <input
										ng-if="form_data.contatto_nazione!='Italia'" type="text"
										class="form-control" ng-model="form_data.contatto_provincia"
										ng-required="true"> <select
										ng-if="form_data.contatto_nazione=='Italia'"
										class="form-control" ng-model="form_data.contatto_provincia"
										ng-required="true">
										<option ng-repeat="p in cities.province track by $index"
											ng-value="p.value">{{p.value}}</option>
									</select>
								</div>

								<div class="col-md-4">
									<label>Città *</label> <input
										ng-if="form_data.contatto_nazione!='Italia'" type="text"
										class="form-control" name="contatto_comune"
										ng-model="form_data.contatto_comune" ng-required="true"> <select
										ng-if="form_data.contatto_nazione=='Italia'"
										class="form-control" ng-model="form_data.contatto_comune"
										ng-required="true">
										<option
											ng-repeat="c in cities.province[form_data.contatto_provincia].comuni"
											ng-value="c.value">{{c.value}}</option>
									</select>
								</div>


								<div class="col-md-2">
									<label>CAP *</label> <input
										ng-if="form_data.contatto_nazione!='Italia'" type="text"
										class="form-control" ng-model="form_data.contatto_cap"
										ng-required="true"> <select
										ng-if="form_data.contatto_nazione=='Italia'"
										class="form-control" ng-model="form_data.contatto_cap"
										ng-required="true">
										<option
											ng-repeat="c in cities.province[form_data.contatto_provincia].comuni[form_data.contatto_comune].cap"
											ng-value="c.value">{{c.value}}</option>
									</select>
								</div>

								<div class="col-md-4">
									<label>Telefono *</label> <input type="text"
										class="form-control" name="contatto_tel"
										ng-model="form_data.contatto_tel" ng-required="true">
								</div>
								<div class="col-md-5">
									<label>Cellulare *</label> <input type="text"
										class="form-control" name="contatto_cell"
										ng-model="form_data.contatto_cell" ng-required="true">
								</div>
								<div class="col-md-3">
									<label>Fax</label> <input type="text" class="form-control"
										name="contatto_fax" ng-model="form_data.contatto_fax">
								</div>
								<div class="col-md-3">
									<label>Email</label> <input type="text" class="form-control"
										name="contatto_email" ng-model="form_data.contatto_email">
								</div>
							</div>

						</fieldset>


					</div>

					<button type="button" class="btn btn-success"
						ng-click="save('edit')" ng-disabled="fastThreadForm.$invalid">SAVE</button>
					<button type="button" class="btn pull-right"
						ng-click="save('edit')">Cancel</button>
					</ng-form>
				</div>
			</section>
		</div>
	</div>
</div>
<?php
if ($this->uri->segment ( 5 )) {
	?>
<script>
$(window).on('beforeunload', function(){	
	if($('#showpop').val() == 'DRAFT') {
	    return "You have unsaved changes!";
	}
});

</script>
<?php } ?>