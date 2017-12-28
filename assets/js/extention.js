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

var myApp = angular.module('WmanagerApp', []);



//Not Necessary to Create Service, Same can be done in COntroller also as method like add() method
myApp.service('filteredListService', function () {
     
    
    this.searched = function (valLists,toSearch) {
        return _.filter(valLists, 
        function (i) {
            return searchUtil(i, toSearch);
        });        
    };
    
    this.paged = function (valLists,pageSize)
    {    	
        retVal = [];
        for (var i = 0; i < valLists.length; i++) {
            if (i % pageSize === 0) {
                retVal[Math.floor(i / pageSize)] = [valLists[i]];
            } else {
                retVal[Math.floor(i / pageSize)].push(valLists[i]);
            }
        }
        return retVal;
    };
 
});

var extentionCtr = myApp.controller('extentionCtrl', function ($scope, $filter, filteredListService, $http, getExtension, getInstalledExtension) {
 
    $scope.pageSize = 5;    
    getExtension.async().then(function() {
    	$scope.allItems = getExtension.data(); 
    	getInstalledExtension.async().then(function() {
    		$scope.allExtention = getInstalledExtension.data(); 
	        	angular.forEach($scope.allItems, function(item, key) {
	        		angular.forEach($scope.allExtention, function(ext, k) {
	            		if($scope.allItems[key].key == ext.key) {
	            			$scope.allItems[key].ext_id = ext.id;	
	            			if(ext.status == 'installed') {
	            				$scope.allItems[key].installed = true;	
	            			} 
	            			if(ext.status == 'downloaded') {
	            				$scope.allItems[key].downloaded = true;
	            			}
	            			
	            		}
	            	});
	        		$scope.allItems[key].downloading = false;
	        		$scope.allItems[key].file = false;
	        	});
        	
        	   	$scope.reverse = false;
        	    $scope.resetAll = function () {
        	        $scope.filteredList = $scope.allItems;    	      
        	        $scope.searchText = '';
        	        $scope.currentPage = 0;
        	    }


        	    $scope.search = function () {
        	        $scope.filteredList = 
        	       filteredListService.searched($scope.allItems, $scope.searchText);
        	        
        	        if ($scope.searchText == '') {
        	            $scope.filteredList = $scope.allItems;
        	        }
        	        $scope.pagination(); 
        	    }


        	    // Calculate Total Number of Pages based on Search Result
        	    $scope.pagination = function () {
        	        $scope.ItemsByPage = filteredListService.paged( $scope.filteredList, $scope.pageSize );         
        	    };

        	    $scope.setPage = function () {
        	        $scope.currentPage = this.n;
        	    };

        	    $scope.firstPage = function () {
        	        $scope.currentPage = 0;
        	    };

        	    $scope.lastPage = function () {
        	        $scope.currentPage = $scope.ItemsByPage.length - 1;
        	    };

        	    $scope.range = function (input, total) {
        	        var ret = [];
        	        if (!total) {
        	            total = input;
        	            input = 0;
        	        }
        	        for (var i = input; i < total; i++) {
        	            if (i != 0 && i != total - 1) {
        	                ret.push(i);
        	            }
        	        }
        	        return ret;
        	    };
        	    
        	    $scope.sort = function(){
        	        $scope.resetAll();      	           	        
        	        $scope.pagination();    
        	    };
        	    
        	    $scope.sort();  
    	});
    

    
    });
    
    $scope.triggerFileUpload = function(item) {    	
    	$('#upload_'+item.id).click();
    }

    
    $scope.download_file = function(filename, item) {    		
    		$('#spinner_'+item.id).show();
    		$('#install_'+item.id).hide();
    		$('#downloding_'+item.id).hide();
    		$('#downloaded_'+item.id).hide();
    	    var page_url = '/api/service/download_file?filename='+filename;    	
    	    var req = new XMLHttpRequest();
    	    req.open("GET", page_url, true);
    	    function transfer_complete(e) {
    	    	
    	    	console.log("The transfer is complete.");   
    	    }
    	    
        	function transfer_failed(e){console.log("An error occurred while transferring the file.");}
        	function transfer_canceled(e){console.log("The transfer has been canceled by the user.");}
        	
        	req.addEventListener("load", transfer_complete, false);
        	req.addEventListener("error", transfer_failed, false);
        	req.addEventListener("abort", transfer_canceled, false);	  	
    	    req.addEventListener("progress", function (evt) {
    	    	if (evt.lengthComputable)
    	    	  {
    	    	    var percentage = Math.round((evt.loaded/evt.total)*100);    	    	    

    	    	    console.log("percent " + percentage + '%' );
    	    	  }
    	    	  else 
    	    	  {
    	    	  	console.log("Unable to compute progress information since the total size is unknown");
    	    	  }
    	    }, false);

    	    req.responseType = "blob";
    	    req.onreadystatechange = function () {
    	        if (req.readyState === 4 && req.status === 200) {    
    	        	$('#spinner_'+item.id).hide();    	        	    	        	
        	    	$('#install_'+item.id).show();
        	    	$('#downloding_'+item.id).hide();
    	            if (typeof window.chrome !== 'undefined') {
    	                // Chrome version
    	                var link = document.createElement('a');
    	                link.href = window.URL.createObjectURL(req.response);
    	                link.download = filename;
    	                link.click();
    	            } else if (typeof window.navigator.msSaveBlob !== 'undefined') {
    	                // IE version
    	                var blob = new Blob([req.response], { type: 'application/force-download' });
    	                window.navigator.msSaveBlob(blob, filename);
    	            } else {
    	                // Firefox version
    	                var file = new File([req.response], filename, { type: 'application/force-download' });
    	                window.open(URL.createObjectURL(file));
    	            }
    	            $http({
    		    		method: "post",
    		    		url: '/core/extension/add_extention', 	    		
    		    		data: {'formData' : item}
    				}).then(function mySuccess(response) {
    					$http({
        		    		method: "post",
        		    		url: '/core/extension/get_all_extention', 	    		
        		    		data: {'formData' : item}
        				}).then(function mySuccess(response) {
        					$scope.allExtention = response.data;
        					console.log($scope.allExtention)
		    	        	angular.forEach($scope.allItems, function(item, key) {
		    	        		angular.forEach($scope.allExtention, function(ext, k) {
		    	            		if($scope.allItems[key].key == ext.key) {		    	            			
		    	            			$scope.allItems[key].ext_id = ext.id;	
		    	            		}
		    	            	});    	        	
		    	        	});
        				});
  
    				}, function myError(response) {
    				   
    				});
    	        } else {    	        	
    	        	$('#spinner_'+item.id).hide();
    	        	$('#downloding_'+item.id).show();
    	        	if(req.status === 404) {
    	        		$('#error_'+item.id).show();
   		        	 	$('#error_'+item.id).html("Error While downloading the file");	
    	        	}
    	        	
    	        }
    	    };
    	    req.send();
    	
    }
    
}) .factory('getExtension', function($http, $q) {
	  var deffered = $q.defer();
	  var data = [];  
	  var getExtension = {};

	  getExtension.async = function() {
	    $http.get('/api/service/allextention')
	    .success(function (d) {
	      data = d;
	      deffered.resolve();
	    });
	    return deffered.promise;
	  };
	  getExtension.data = function() { return data; };

	  return getExtension;
}) .factory('getInstalledExtension', function($http, $q) {
	  var deffered = $q.defer();
	  var data = [];  
	  var getInstalledExtension = {};

	  getInstalledExtension.async = function() {
	    $http.get('/core/extension/get_all_extention')
	    .success(function (d) {
	      data = d;
	      deffered.resolve();
	    });
	    return deffered.promise;
	  };
	  getInstalledExtension.data = function() { return data; };

	  return getInstalledExtension;
});

extentionCtr.$inject = ['$scope', '$filter','filteredListService'];

function searchUtil(item, toSearch) {
    return (item.name.toLowerCase().indexOf(toSearch.toLowerCase()) > -1 || item.description.toLowerCase().indexOf(toSearch.toLowerCase()) > -1 || item.EmpId == toSearch) ? true : false;
}

var _validFileExtensions = [".zip"]; 
function triggerFileChange(oInput, item) {
	 if (oInput.type == "file") {
		 	$('#downloaded_'+item.id).hide();
		 	$('#install_'+item.id).hide();
		 	$('#install_spinner_'+item.id).show();
	        var sFileName = oInput.value;
	         if (sFileName.length > 0) {
	            var blnValid = false;
	            for (var j = 0; j < _validFileExtensions.length; j++) {
	                var sCurExtension = _validFileExtensions[j];
	                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
	                    blnValid = true;
	                    var fdata = new FormData();
	                    fdata.append("id",item.ext_id);
	                    
	                    if($("#upload_"+item.id)[0].files.length>0)
	                       fdata.append("file",$("#upload_"+item.id)[0].files[0])
	                       
	                        $.ajax({
						        type: 'POST',
						        url: '/core/extension/extension_installer/',
						        data:fdata,
						        contentType: false,
						        processData: false, 
						        success: function(data) {						        	
							         if(data.result == "success") {
							        	 $('#install_spinner_'+item.id).hide();
							        	 $('#installed_'+item.id).show();
							        	 $('#success_'+item.id).show();
							        	 $('#error_'+item.id).hide();
							        	 $('#success_'+item.id).html(data.message);
							         }  else {
							        	 $('#install_spinner_'+item.id).hide();
							        	 $('#install_'+item.id).show();
							        	 $('#success_'+item.id).hide();
							        	 $('#error_'+item.id).show();
							        	 $('#error_'+item.id).html(data.message);
							         }
						        }
						    })	                   
	                }
	            }
	             
	            if (!blnValid) {    	        	
	                oInput.value = "";
	                return false;
	            }
	        }
	    }
	    return true;
}
