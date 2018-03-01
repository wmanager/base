/*$(function () {
	if($("#pie-chart-autoletture").length){
		
		var data = jQuery.parseJSON($('#data_hidden_example').html());
		
		$.plot($("#example"), data, 
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
});*/