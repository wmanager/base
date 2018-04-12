$(function(){
	$.ajax({
		url: "/common/home/get_status",
		type: 'GET',
		success: function(data) {			
			var data = JSON.parse(data);			
			var troublesDataArray = [];
			var activityDataArray = [];
			var threadsDataArray = [];
			
			var troubles_data = data.troubles;
			var activity_data = data.activities;
			var threads_data = data.threads;
			for(var key in troubles_data) {				
				if(troubles_data[key]['status'] == 'WIP') {
					troublesDataArray.push({
						name : troubles_data[key]['status'],
						y : parseInt(troubles_data[key]['count']),
						sliced: true,
			            selected: true
					});
				} else {
					troublesDataArray.push({
						name : troubles_data[key]['status'],
						y : parseInt(troubles_data[key]['count'])
					});
				}
			}
			
			for(var key in activity_data) {				
				if(activity_data[key]['status'] == 'NEW') {
					activityDataArray.push({
						name : activity_data[key]['status'],
						y : parseInt(activity_data[key]['count']),
						sliced: true,
			            selected: true
					});
				} else {
					activityDataArray.push({
						name : activity_data[key]['status'],
						y : parseInt(activity_data[key]['count'])
					});
				}
			}
			
			for(var key in threads_data) {				
				if(threads_data[key]['status'] == 'NEW') {
					threadsDataArray.push({
						name : threads_data[key]['status'],
						y : parseInt(threads_data[key]['count']),
						sliced: true,
			            selected: true
					});
				} else {
					threadsDataArray.push({
						name : threads_data[key]['status'],
						y : parseInt(threads_data[key]['count'])
					});
				}
			}			
			if(troublesDataArray.length > 0) {				
			Highcharts.chart('trouble_container', {
			    chart: {
			        plotBackgroundColor: null,
			        plotBorderWidth: null,
			        plotShadow: false,
			        type: 'pie'
			    },
			    title: {
			        text: 'Trouble Status'
			    },
			    tooltip: {
			        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			    },
			    plotOptions: {
			        pie: {
			            allowPointSelect: true,
			            cursor: 'pointer',
			            dataLabels: {
			                enabled: false
			            },
			            showInLegend: true
			        }
			    },
			    series: [{
			        name: 'Status',
			        colorByPoint: true,
			        data: troublesDataArray
			    }]
			});
			} else {
				$("#trouble_container").parent('div').hide();
			}
		}
	});
});