$(function () {
	if($("#pie-chart-autoletture").length){
		
		var data = jQuery.parseJSON($('#data_hidden_autoletture').html());
		
		$.plot($("#pie-chart-autoletture"), data, 
		{
				series: {
					pie: { 
						show: true,
						innerRadius: 0.4,
						radius: 1,
						label: {
			                show: true,
			                radius: 2/3,
			                formatter: function(label, series) {
			                    return '<div style="font-size:11px ;text-align:center; padding:2px;">'+Math.round(series.percent)+'%</div>';
			                },
			                threshold: 0.1
			            }
					}
				},
				legend: {
					show: true
				},
				grid: {
					hoverable: true,
					clickable: false,
	
				},
		});
	}
	
	
	if($("#pie-chart-grossista").length){
		 
		var data = jQuery.parseJSON($('#data_hidden_grossista').html());
		
		$.plot($("#pie-chart-grossista"), data, 
		{
				series: {
					pie: { 
						show: true,
						innerRadius: 0.4,
						radius: 1,
						label: {
			                show: true,
			                radius: 2/3,
			                formatter: function(label, series) {
			                    return '<div style="font-size:11px ;text-align:center; padding:2px;">'+Math.round(series.percent)+'%</div>';
			                },
			                threshold: 0.1
			            }
					}
				},
				legend: {
					show: true
				},
				grid: {
					hoverable: true,
					clickable: false
				},
		});
	}
	
	if($("#pie-chart-grossista-subentro").length){
		var data = jQuery.parseJSON($('#data_hidden_grossista_subentro').html());
		
		$.plot($("#pie-chart-grossista-subentro"), data, 
		{
				series: {
					pie: { 
						show: true,
						innerRadius: 0.4,
						radius: 1,
						label: {
			                show: true,
			                radius: 2/3,
			                formatter: function(label, series) {
			                    return '<div style="font-size:11px ;text-align:center; padding:2px;">'+Math.round(series.percent)+'%</div>';
			                },
			                threshold: 0.1
			            }
					}
				},
				legend: {
					show: true
				},
				grid: {
					hoverable: true,
					clickable: false
				},
		});
	}
	
	if($("#pie-chart-attivazione-billing").length){

		var data = jQuery.parseJSON($('#data_hidden_attivazione_billing').html());
			console.log(data);
	$.plot($("#pie-chart-attivazione-billing"), data, 
		{
				series: {
					pie: { 
						show: true,
						innerRadius: 0.4,
						radius: 1,
						label: {
			                show: true,
			                radius: 2/3,
			                formatter: function(label, series) {
			                    return '<div style="font-size:11px ;text-align:center; padding:2px;">'+Math.round(series.percent)+'%</div>';
			                },
			                threshold: 0.1
			            }
					}
				},
				legend: {
					show: true
				},
				grid: {
					hoverable: true,
					clickable: false,
	
				},
		});
	}
	
	if($("#pie-chart-anagrafic-billing").length){

		var data = jQuery.parseJSON($('#data_hidden_anagrafic_billing').html());
			console.log(data);
		$.plot($("#pie-chart-anagrafic-billing"), data, 
		{
				series: {
					pie: { 
						show: true,
						innerRadius: 0.4,
						radius: 1,
						label: {
			                show: true,
			                radius: 2/3,
			                formatter: function(label, series) {
			                    return '<div style="font-size:11px ;text-align:center; padding:2px;">'+Math.round(series.percent)+'%</div>';
			                },
			                threshold: 0.1
			            }
					}
				},
				legend: {
					show: true
				},
				grid: {
					hoverable: true,
					clickable: false,
	
				},
		});
	}
	

	if($("#pie-chart-subentro-billing").length){

		var data = jQuery.parseJSON($('#data_hidden_subentro_billing').html());
			console.log(data);
	$.plot($("#pie-chart-subentro-billing"), data, 
		{
				series: {
					pie: { 
						show: true,
						innerRadius: 0.4,
						radius: 1,
						label: {
			                show: true,
			                radius: 2/3,
			                formatter: function(label, series) {
			                    return '<div style="font-size:11px ;text-align:center; padding:2px;">'+Math.round(series.percent)+'%</div>';
			                },
			                threshold: 0.1
			            }
					}
				},
				legend: {
					show: true
				},
				grid: {
					hoverable: true,
					clickable: false,
	
				},
		});
	}
	
	if($("#pie-chart-variazioni-billing").length){

		var data = jQuery.parseJSON($('#data_hidden_variazioni_billing').html());
			console.log(data);
	$.plot($("#pie-chart-variazioni-billing"), data, 
		{
				series: {
					pie: { 
						show: true,
						innerRadius: 0.4,
						radius: 1,
						label: {
			                show: true,
			                radius: 2/3,
			                formatter: function(label, series) {
			                    return '<div style="font-size:11px ;text-align:center; padding:2px;">'+Math.round(series.percent)+'%</div>';
			                },
			                threshold: 0.1
			            }
					}
				},
				legend: {
					show: true
				},
				grid: {
					hoverable: true,
					clickable: false,
	
				},
		});
	}

	if($("#pie-chart-grossista-potenza").length){

		var data = jQuery.parseJSON($('#data_hidden_grossista_potenza').html());
		console.log(data);
	$.plot($("#pie-chart-grossista-potenza"), data, 
		{
				series: {
					pie: { 
						show: true,
						innerRadius: 0.4,
						radius: 1,
						label: {
			                show: true,
			                radius: 2/3,
			                formatter: function(label, series) {
			                    return '<div style="font-size:11px ;text-align:center; padding:2px;">'+Math.round(series.percent)+'%</div>';
			                },
			                threshold: 0.1
			            }
					}
				},
				legend: {
					show: true
				},
				grid: {
					hoverable: true,
					clickable: false,
	
				},
		});
	}

	if($("#pie-chart-rettifiche-billing").length){

		var data = jQuery.parseJSON($('#data_hidden_rettifiche_billing').html());
			console.log(data);
	$.plot($("#pie-chart-rettifiche-billing"), data, 
		{
				series: {
					pie: { 
						show: true,
						innerRadius: 0.4,
						radius: 1,
						label: {
			                show: true,
			                radius: 2/3,
			                formatter: function(label, series) {
			                    return '<div style="font-size:11px ;text-align:center; padding:2px;">'+Math.round(series.percent)+'%</div>';
			                },
			                threshold: 0.1
			            }
					}
				},
				legend: {
					show: true
				},
				grid: {
					hoverable: true,
					clickable: false,
	
				},
		});
	}
	
	// For credit check wizard
	if($("#pie-chart-credit_check").length){

		var data = jQuery.parseJSON($('#data_hidden_credit_check').html());
			console.log(data);
	$.plot($("#pie-chart-credit_check"), data, 
		{
				series: {
					pie: { 
						show: true,
						innerRadius: 0.4,
						radius: 1,
						label: {
			                show: true,
			                radius: 2/3,
			                formatter: function(label, series) {
			                    return '<div style="font-size:11px ;text-align:center; padding:2px;">'+Math.round(series.percent)+'%</div>';
			                },
			                threshold: 0.1
			            }
					}
				},
				legend: {
					show: true
				},
				grid: {
					hoverable: true,
					clickable: false,
	
				},
		});
	}
	
	// For precheck wizard
	if($("#pie-chart-precheck").length){

		var data = jQuery.parseJSON($('#data_hidden_precheck').html());
			console.log(data);
	$.plot($("#pie-chart-precheck"), data, 
		{
				series: {
					pie: { 
						show: true,
						innerRadius: 0.4,
						radius: 1,
						label: {
			                show: true,
			                radius: 2/3,
			                formatter: function(label, series) {
			                    return '<div style="font-size:11px ;text-align:center; padding:2px;">'+Math.round(series.percent)+'%</div>';
			                },
			                threshold: 0.1
			            }
					}
				},
				legend: {
					show: true
				},
				grid: {
					hoverable: true,
					clickable: false,
	
				},
		});
	}
	
	// For precheck wizard
	if($("#pie-chart-allineamento_rid").length){

		var data = jQuery.parseJSON($('#data_hidden_allineamento_rid').html());
			console.log(data);
	$.plot($("#pie-chart-allineamento_rid"), data, 
		{
				series: {
					pie: { 
						show: true,
						innerRadius: 0.4,
						radius: 1,
						label: {
			                show: true,
			                radius: 2/3,
			                formatter: function(label, series) {
			                    return '<div style="font-size:11px ;text-align:center; padding:2px;">'+Math.round(series.percent)+'%</div>';
			                },
			                threshold: 0.1
			            }
					}
				},
				legend: {
					show: true
				},
				grid: {
					hoverable: true,
					clickable: false,
	
				},
		});
	}
	

	});