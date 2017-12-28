<div class="clearfix"></div>

<div class="class="row-fluid"">
	<table id="integrations" name="integrations"
		class="table table-striped table-hover"
		ng-if="!selected.showdashboard">
		<thead>
			<tr>
				<th>Tipologia / Status</th>
				<th>d_decorrenza</th>
				<th>Gross_d_invio</th>
				<th>Gross_esito</th>
				<th>Billing_d_invio</th>
				<th>Return_value</th>
				<th>Billing_esito</th>
				<th>Billing_esito_note</th>
				<th>Created</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="rel in integrations">
				<td>{{rel.tipologia}} {{rel.exr_causale}} {{rel.status}}
					{{rel.exa_status}} {{rel.exr_status}}</td>
				<td>{{rel.d_decorrenza}}</td>
				<td>{{rel.gross_d_invio}}</td>
				<td>{{rel.gross_esito}}</td>
				<td>{{rel.billing_d_invio}} {{rel.exa_billing_d_invio}}</td>
				<td>{{rel.return_value}}</td>
				<td>{{rel.billing_esito}} {{rel.exa_billing_esito}}</td>
				<td style="word-break: break-all;">{{rel.billing_esito_note}}
					{{rel.exa_billing_esito_note}}</td>
				<td>{{rel.created | date:'dd-MM-yyyy hh:m'}} {{rel.exa_created |
					date:'dd-MM-yyyy hh:m'}} {{rel.exr_created | date:'dd-MM-yyyy
					hh:m'}}</td>
			</tr>
		</tbody>
	</table>
</div>