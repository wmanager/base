/**
 * WManager
 *
 * An open source application for business process management
 * and a process automation development framework
 *
 * This content is released under the MIT License (MIT)
 *
 * WManager
 * Copyright (c) 2017 JAMAIN SOCIAL AND SERVICES SRL (http://jamain.co)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package     WManager
 * @author      Eng. Gianluca Pelliccioli and JAMAIN SOCIAL AND SERVICES SRL development team
 * @copyright   Copyright (c) 2017 JAMAIN SOCIAL AND SERVICES SRL (http://jamain.co)
 * @license     http://opensource.org/licenses/MIT      MIT License
 * @link        http://wmanager.org
 * @since       Version 1.0.0
 * @filesource
 */

function generateChat() {
	
	$.ajax({
		url: "dashboard/get_graph_data",
		async:false,
		cache:false,
		type: 'POST',
		success: function(data) {
			var credit_data = JSON.parse(data);
			
			dataArray = [];
			for(var key in credit_data) {
				if(credit_data[key]['credit_expired'] != null) 
					dataArray.push(parseFloat(credit_data[key]['credit_expired']));
			}
			
			amountdataArray = [];
			for(var key in credit_data) {
				if(credit_data[key]['amount'] != null) 
					amountdataArray.push(parseFloat(credit_data[key]['amount']));
			}
			
			invoicedataArray = [];
			for(var key in credit_data) {
				if(credit_data[key]['amount'] != null) 
					invoicedataArray.push(parseFloat(credit_data[key]['invoice']));
			}
			
			
			Highcharts.chart('container-credit-amount', {
			    chart: {
			        type: 'column'
			    },
			    title: {
			        text: 'Credit Amount vs Aging cluster'
			    },
			    subtitle: {
			        text: ''
			    },
			    xAxis: {
			        categories: [
			            'A',
			            'B',
			            'C',
			            'D',
			            'E'
			        ],
			        crosshair: true
			    },
			    yAxis: {
			        min: 0,
			        title: {
			            text: 'Credit Amount'
			        }
			    },
			    tooltip: {
			        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
			            '<td style="padding:0"><b>{point.y:.2f} (€)</b></td></tr>',
			        footerFormat: '</table>',
			        shared: true,
			        useHTML: true
			    },
			    plotOptions: {
			        column: {
			            pointPadding: 0.2,
			            borderWidth: 0
			        }
			    },
			    series: [{
			        name: 'Amount',
			        data: dataArray,
			        color : '#04A428'

			    }],
			    exporting: {
			        enabled: false
			    }
			});
			

			Highcharts.chart('container-agent', {

			    chart: {
			        type: 'column'
			    },

			    title: {
			        text: 'Credit Amount + Invoiced vs Cluster Aging'
			    },

			    xAxis: {
			        categories: ['A', 'B', 'C', 'D', 'E']
			    },

			    yAxis: {
			        allowDecimals: false,
			        min: 0,
			        title: {
			            text: ''
			        }
			    },

			    tooltip: {
			        formatter: function () {
			            return '<b>' + this.x + '</b><br/>' +
			                this.series.name + ': ' + this.y + '<br/>' +
			                'Total: ' + this.point.stackTotal;
			        }
			    },

			    plotOptions: {
			        column: {
			            stacking: 'normal'
			        }
			    },

			    series: [{
			        name: 'Amount',
			        data: amountdataArray,			        
			        color:'#4285F4'
			    }, {
			        name: 'Fatturato',
			        data: invoicedataArray,			        
			        color:'#F87F07'
			    }],
			    exporting: {
			        enabled: false
			    }
			});
			
		}
	});
}