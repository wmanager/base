$(function () {
    var sin = [], cos = [];
    for (var i = 0; i < 10; i += 0.5) {
        sin.push([i, Math.sin(i)]);
        cos.push([i, Math.cos(i)]);
    }
	if($('#line-chart').length > 0) {
	    var plot = $.plot($("#line-chart"),
	           [ { data: sin, label: "sin(x)"}, { data: cos, label: "cos(x)" } ], {
	               series: {
	                   lines: { show: true },
	                   points: { show: true }
	               },
	               
	               grid: { hoverable: true, clickable: true },
	               yaxis: { min: -1.1, max: 1.1 },
				   xaxis: { min: 0, max: 9 },
	    	colors: ["#F90", "#222", "#666", "#BBB"]
	             });
	}
});

$(function () {
	
	var productions_data_count = $('#data_hidden_productions_count').val();
	productions0 = productions1 = productions2 = productions3 = productions4 	= '';
	var i = 0;
	if(productions_data_count > 0){
		for(j=0;j < productions_data_count;j++){
			
			if(j == 0){
				productions0 = jQuery.parseJSON($('#data_hidden_production_'+j).html());
				pod_production0 = $('#pod_hidden_production_'+j).val();
			}
			if(j == 1){
				productions1 = jQuery.parseJSON($('#data_hidden_production_'+j).html());
				pod_production1 = $('#pod_hidden_production_'+j).val();
			}
			if(j == 2){
				productions2 = jQuery.parseJSON($('#data_hidden_production_'+j).html());
				pod_production2 = $('#pod_hidden_production_'+j).val();
			}
			if(j == 3){
				productions3 = jQuery.parseJSON($('#data_hidden_production_'+j).html());
				pod_production3 = $('#pod_hidden_production_'+j).val();
			}
			if(j == 4){
				productions4 = jQuery.parseJSON($('#data_hidden_production_'+j).html());
				pod_production4 = $('#pod_hidden_production_'+j).val();
			}
			
		}
	}
	if(productions4 !=''){
	    var plot = $.plot($("#client_details_production"),
	    		[{ data: productions0, label: pod_production0 },{ data: productions1, label: pod_production1 },{ data: productions2, label: pod_production2 },{ data: productions3, label: pod_production3 },{ data: productions4, label: pod_production4 } ], {
	               series: {
	                   lines: { show: true },
	                   points: { show: true }
	               },
	               
	               grid: { hoverable: true, clickable: true },
	            //   yaxis: { min: 0, max: 10 },
				 //  xaxis: { min: 0, max: 9 },
	               xaxis: { mode: "time", timeformat: "%d/%m/%y", minTickSize: [1, "day"]},
	               colors: ["#F90", "#222", "#666", "#BBB", "#CCC"]
	             });
	} else if(productions3 !=''){
	    var plot = $.plot($("#client_details_production"),
	    		[{ data: productions0, label: pod_production0 },{ data: productions1, label: pod_production1 },{ data: productions2, label: pod_production2 },{ data: productions3, label: pod_production3 } ], {
	               series: {
	                   lines: { show: true },
	                   points: { show: true }
	               },
	               
	               grid: { hoverable: true, clickable: true },
	            //   yaxis: { min: 0, max: 10 },
				 //  xaxis: { min: 0, max: 9 },
	               xaxis: { mode: "time", timeformat: "%d/%m/%y", minTickSize: [1, "day"]},
	               colors: ["#F90", "#222", "#666", "#BBB"]
	             });
	} else if(productions2 !=''){
	    var plot = $.plot($("#client_details_production"),
	    		[{ data: productions0, label: pod_production0 },{ data: productions1, label: pod_production1 },{ data: productions2, label: pod_production2 } ], {
	               series: {
	                   lines: { show: true },
	                   points: { show: true }
	               },
	               
	               grid: { hoverable: true, clickable: true },
	            //   yaxis: { min: 0, max: 10 },
				 //  xaxis: { min: 0, max: 9 },
	               xaxis: { mode: "time", timeformat: "%d/%m/%y", minTickSize: [1, "day"]},
	               colors: ["#F90", "#222", "#666", "#BBB"]
	             });
	} else if(productions1 !=''){
		    var plot = $.plot($("#client_details_production"),
		    		[{ data: productions0, label: pod_production0 },{ data: productions1, label: pod_production1 } ], {
		               series: {
		                   lines: { show: true },
		                   points: { show: true }
		               },
		               
		               grid: { hoverable: true, clickable: true },
		            //   yaxis: { min: 0, max: 10 },
					 //  xaxis: { min: 0, max: 9 },
		               xaxis: { mode: "time", timeformat: "%d/%m/%y", tickSize: [1, "day"]},
		               colors: ["#F90", "#222", "#666", "#BBB"]
		             });
		} else if(productions0 !=''){
		    var plot = $.plot($("#client_details_production"),
		        		[{ data: productions0, label: pod_production0 }, ], {
		                   series: {
		                       lines: { show: true },
		                       points: { show: true }
		                   },
		                   
		                   grid: { margin: 10, 
		     	              labelMargin: 5, 
		    	              labelWidth: 20, hoverable: true, clickable: true },
		                   tooltip: true,
		                //   yaxis: { min: 0, max: 10 },
		    			 //  xaxis: { min: 0, max: 9 },
		                   xaxis: { mode: "time", timeformat: "%d/%m/%y", tickSize: [1, "day"],labelHeight: 30 },
		                   colors: ["#F90", "#222", "#666", "#BBB"]
		                 });
		    }
});