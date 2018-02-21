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

'use strict';

function Ctrl($scope) {
  $scope.things = [
    'A', 'B', 'C'  
  ];
}

var app = angular.module('WmanagerApp', ['ui.bootstrap','validation','ui.calendar','ngBootbox','checklist-model','ngFileUpload','angularMoment','angular.filter'])
.config(['$validationProvider', function ($validationProvider) {
    angular.extend($validationProvider, {
    validCallback: function (element){ 		
        $(element).parents('.form-group:first').removeClass('has-error');        
        $(element).next("span:first").empty();
    },
    invalidCallback: function (element) {
        $(element).parents('.form-group:first').addClass('has-error');
    }
    });

    $validationProvider.setErrorHTML(function (msg) {
       return  "<label class=\"control-label has-error\">" + msg + "</label>";
    });

    var initInjector = angular.injector(['ng']);
    var $http = initInjector.get('$http');
    var $q = initInjector.get('$q');

    var expression = {
                    required: function(value) {
                      return !!value;
                    },
                    url: /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)/,
                    email: /(^$|^.*@.*\..*$)/,
                    number: /^\d+$/,
                    cf: /^[A-Za-z]{6}[0-9]{2}[A-Za-z]{1}[0-9]{2}[A-Za-z]{1}[0-9]{3}[A-Za-z]{1}$/,
                    iva: /^[0-9]{11}$/,
                    cap: /^[0-9]{5}$/,
                    prov: /^[A-Za-z]{2}$/,
                    pod: /^IT[0-9]{3}E[0-9A-Za-z]{8,9}$/,
                    pod_unique: function(value, scope) {
                      if(value.length <14) return true;
                      if(scope.$parent.$parent.$parent.actid || scope.$parent.$parent.$parent.$parent.actid) return true;
                      var status = false; 
                         var result = $http({
                            method: "post",
                            url: "/common/module/check_pod",
                            data: {pod: value, tipo_contratto: scope.$parent.$parent.$parent.$parent.form_data.tipo_contratto}
                          }).then(function(data){                        
                              status = data.data.result;
                              return data.data.result;
                          });
                          return $q.all([result]).then(function () {
                                return !status;
                          });
                    },
                    pod_unique_sereno: function(value, scope) {
                        if(value.length <14) return true;
                        if(scope.$parent.$parent.$parent.actid || scope.$parent.$parent.$parent.$parent.actid) return true;
                        var status = false;
                        var result = $http({
                            method: "post",
                            url: "/common/module/check_pod/SERENO",
                            data: {pod: value}
                        }).then(function(data){
                            status = data.data.result;
                            return data.data.result;
                        });
                        return $q.all([result]).then(function () {
                            return !status;
                        });
                    },
                     pod_existing: function(value, scope) {
                      if(value.length <14) return true;
                      //console.log(scope.$parent.$parent.$parent.actid);
                      if(scope.$parent.$parent.$parent.actid) return true;
                      var status = false;
                         var result = $http({
                            method: "post",
                            url: "/common/module/check_pod",
                            data: {pod: value}
                          }).then(function(data){
                              status = data.data.result;
                              return data.data.result;
                          });
                          return $q.all([result]).then(function () {
                                return status===1;
                          });
                    },
                    contratto: /^[0-9]{8}([B-D]|(CDD)){1}$/,
                    contratto_sereno: /^[0-9]{8}([S]|(CDS)){1}$/,
                    contrattotuo: /^[0-9]{8}([T]|(CDT)){1}$/,
                    contratto_np04: /^[0-9]{8}[N]{1}$/,
                    iban: /^IT\d{2}[A-Z]\d{10}[0-9A-Z]{12}$/,
                    tensione: /^[0-9]{3}$/,
                    percentage: function(value, scope) {
                      if(value >= 0 && value <= 100) return true;
                    },
                    blacklist_comuni: function(value, scope, element, attrs, param) {
                      var status = false;
                     
                         var result = $http({
                            method: "post",
                            url: "/common/module/get_blacklisted_comuni",
                            data: {comune: value}
                          }).then(function(data){                        
                              status = data.data.result;
                              return data.data.result;
                          });
                          return $q.all([result]).then(function () {
                              return !status;
                          });
                     
                    },
                    blacklist_pod: function(value, scope, element, attrs, param) {
                      if(value.length >= 14){
                         var status = false;
                         var result = $http({
                            method: "post",
                            url: "/common/module/get_blacklisted_pod",
                            data: {pod: value}
                          }).then(function(data){                        
                              status = data.data.result;
                              return data.data.result;
                          });
                       
                          return $q.all([result]).then(function () {
                              return !status;
                          });
                      }
                    },
                    minlength: function(value, scope, element, attrs, param) {
                        return value.length >= param;
                    },
                    maxlength: function(value, scope, element, attrs, param) {
                        return value.length <= param;
                    },
                    latitudine: function(value, scope, element, attrs, param) {
                        if(value != null && value.length > 0){
                          value = value.replace(",", ".");
                          if(value >= 35.49 && value <= 47.1){
                            return true;
                          } else {
                            return false;
                          }
                        }
                        return true;
                    },
                    longitudine: function(value, scope, element, attrs, param) {
                        if(value != null && value.length > 0){ 
                          value = value.replace(",", ".");
                          if(value >= 6.6255 && value <= 18.522){
                            return true;
                          } else {
                            return false;
                          }
                        }
                        return true;
                    },
                    azimuth: function(value, scope, element, attrs, param) {
                        if(value != null && value.length > 0){ 
                          value = value.replace(",", ".");
                          if(value >= -90 && value <= 90){
                            return true;
                          } else {
                            return false;
                          }
                        }
                        return true;
                    },
                    min: function(value, scope, element, attrs, param) {
                      if(value != null && value.length > 0){ 
                        return value.length >= param;
                      }
                    },
                    max: function(value, scope, element, attrs, param) {
                      if(value != null && value.length > 0){ 
                        return value.length <= param;
                      }
                    }
                };

    var defaultMsg = {
                    required: {
                        error: 'Required field',
                        success: ''
                    },
                    url: {
                        error: 'This should be Url',
                        success: ''
                    },
                    email: {
                        error: 'Email non valida',
                        success: ''
                    },
                    cf: {
                        error: 'Codice fiscale non valido',
                        success: ''
                    },
                    iva: {
                        error: 'Partita IVA non valida',
                        success: ''
                    },
                    cap: {
                        error: 'CAP non valido',
                        success: ''
                    },
                    prov: {
                        error: 'Inserisci due lettere',
                        success: ''
                    },
                    pod: {
                        error: 'Inserisci un numero POD valido',
                        success: ''
                    },
                    pod_unique: {
                        error: 'Il numero POD inserito risulta già presente',
                        success: ''
                    },
                    pod_unique_sereno: {
                        error: 'Il numero POD inserito risulta già presente',
                        success: ''
                    },
                    pod_existing: {
                        error: 'Numero POD non trovato',
                        success: ''
                    },
                    contratto: {
                        error: 'Inserisci un numero contratto valido',
                        success: ''
                    },
                    contratto_sereno: {
                        error: 'Inserisci un numero contratto valido',
                        success: ''
                    },
                    contrattotuo: {
                        error: 'Inserisci un numero contratto valido',
                        success: ''
                    },
                    contratto_np04: {
                        error: 'Inserisci un numero contratto valido',
                        success: ''
                    },
                    number: {
                        error: 'Inserisci un numero',
                        success: ''
                    },
                    minlength: {
                        error: 'This should be longer',
                        success: 'Long enough!'
                    },
                    maxlength: {
                        error: 'This should be shorter',
                        success: 'Short enough!'
                    },
                    blacklist_comuni: {
                        error: 'Comune blacklisted',
                        success: ''
                    },
                    blacklist_pod: {
                        error: 'POD blacklisted',
                        success: ''
                    },
                    iban: {
                      error: 'Inserisci un codice IBAN corretto',
                      success: ''
                    },
                    tensione: {
                      error: 'Il valore indicato non è corretto',
                      success: ''
                    },
                    percentage: {
                      error: 'Immettere un valore da 0 a 100',
                      success: ''
                    },
                    latitudine: {
                      error: 'Immettere un valore compreso tra 35,49 e 47,1',
                      success: ''
                    },
                    longitudine: {
                      error: 'Immettere un valore compreso tra 6,6255 e 18,522',
                      success: ''
                    },
                    azimuth: {
                      error: 'Immettere un valore compreso tra -90 e +90',
                      success: ''
                    }
                };

    $validationProvider.setExpression(expression).setDefaultMsg(defaultMsg);
}])
 
.controller("Contract", function( $scope, $injector, $http, $timeout, transformRequestAsFormPost, $q, $ngBootbox ) {

  $scope.tabs = [];
  $scope.disable_creation = true;
  $scope.forms = {};
  $scope.busy = false;
  $scope.form_data = {};
  $scope.form_data.account = {};
  $scope.form_data.client_address = {};
  $scope.form_data = {};

  $scope.verify = function() {      
        var request = $http({
          method: "post",
          url: "/common/module_inorder/check_client_exists",
          transformRequest: undefined,
          headers: {
           'Content-Type': 'application/json'
          },
          data: JSON.stringify($scope.form_data)
        });

        // Store the data-dump of the FORM scope.
        request.success(
          function( data ) {
            $scope.status_verifica = data.status;            
            if(data.status == 'YES'){            	
            	$scope.form_data.account_existing = true;
            	$scope.form_data.account = 'OLD';
            } else if((data.status == 'NO')){           	            	
            	$scope.form_data.account_existing = false;
            	$scope.form_data.account = 'NEW';
            }
            $scope.disable_verifica = true;
            $scope.disable_creation = false;
          }
        );
    };

    $scope.create_contract = function() {
      $scope.busy = true;
      var request = $http({
          method: "post",
          url: "/common/module_inorder/create_new_contract",
          headers: {
           'Content-Type': 'application/json'
          },
          data: JSON.stringify(angular.copy($scope.form_data))
        });

      request.success(
          function( data ) {        	  
            if(data.status){            	
            	 $scope.busy = false;
            	 $scope.new_contract = true;
            	 window.location = "/common/module_inorder/account_details/"+data.data.account_id+"/"+data.data.be_id+"/"+data.data.asset_id;            
            } else {
            	$scope.error_message = data.message;
            }
          }
      );
      
    }
    
    $scope.$watch('accountid', function () {    	
        if ($scope.accountid != '' && $scope.accountid != undefined) {
            var request = $http({
                method: "get",
                url: "/common/module_inorder/get_account_details/" + $scope.accountid + "/" + $scope.be_id + "/" + $scope.assets_id
            });

            // Store the data-dump of the FORM scope.
            request.success(
                function (data) {
                	if(data) {
                		$scope.disable_verifica = true;
                		$scope.form_data.vat = data.be.be_code;
                		$scope.form_data.be = data.be;
                		$scope.form_data.asset = data.asset;
                		$scope.form_data.invoice_address = data.address.INVOICE;
                		$scope.form_data.account_type = data.account.account_type;
                		$scope.form_data.code = data.account.code;
                		$scope.form_data.account = data.account;
                		$scope.form_data.client_address = data.address.CLIENT;
                	}
                	 
                });
            
            var request = $http({
                method: "get",
                url: "/common/module_inorder/get_product_details/"
            });

            // Store the data-dump of the FORM scope.
            request.success(
                function (data) {
                	if(data) {
                		$scope.products = data;                		
                	}
                	 
                });
        }
    });
    
    $scope.delete_module = function(api_url) {
        $scope.busy = true;
        var request = $http({
            method: "post",
            url: api_url,
            headers: {
             'Content-Type': 'application/json'
            },
            data: JSON.stringify(angular.copy($scope.form_data))
          });

        request.success(
            function( data ) {     
              $scope.busy = false;
              if(data.status){            	
              	 $scope.success_message = data.message; 
              	 window.location = "/common/module_inorder/";
              } else {
              	$scope.error_message = data.message;
              }
            }
        );
        
      }

    $scope.save = function() {
        $scope.busy = true;
        var request = $http({
            method: "post",
            url: "/common/module_inorder/update_client",
            headers: {
             'Content-Type': 'application/json'
            },
            data: JSON.stringify(angular.copy($scope.form_data))
          });

        request.success(
            function( data ) { 
              $scope.busy = false;
              if(data.status){   
            	 window.location = "/common/accounts/"
              	 $scope.success_message = data.message;            
              } else {
              	$scope.error_message = data.message;
              }
            }
        );
        
      }

})

.controller("Activity", function( $scope, $rootScope, $http, $timeout, transformRequestAsFormPost, $ngBootbox, uiCalendarConfig, $compile) {
  $scope.roles = [];
  $scope.actid = '';
  $scope.activities = [];
  $scope.activity_template = "/common/cases/activity_main";
  $scope.activity_summary = "/common/cases/activity_summary";
  $scope.activity_pending = "/common/cases/activity_pending";
  $scope.activity_related = "/common/cases/activity_related";
  $scope.activity_followup = '/common/cases/activity_followup/';
  $scope.activity_trouble = '/common/cases/activity_trouble/';
  $scope.forms = [];
  $scope.thread = '';
  $scope.variables = [];
  $scope.file = "";
  $scope.filedata = {};
  $scope.required_attachments = {};
  $scope.arr_required_attachments = [];
  $scope.arr_attachments = [];
  $scope.request = [];
  $scope.followup = [];

  $scope.errors = false;

  $scope.thread = {};
  
  $scope.cc_busy = false;
  $scope.cc_error = false;
  $scope.cc_message ='';
  $scope.cc_check = true;
  
  $scope.view_calendar = false;
  $scope.validation = [];
  $scope.validation.required_fields = 'Campo obbligatorio';

  $scope.caldata = [];

  $scope.today = new Date();

  $scope.force_required = false;

  window.onbeforeunload = function (event) {
      if($scope.request.draft == 't'){
        return "Il Thread non è stato salvato, abbandonando la pagina tutti i dati andranno persi.";      
      } else {
        return "Sei sicuro di voler abbandonare la pagina? I dati non salvati andranno persi.";      
      }
    };

    $scope.loans_companies_list = function(loan_companies, order_type){

        var res = [];
        var i=0, len=loan_companies.length;
        for (; i<len; i++) {
            if (loan_companies[i].type_order == order_type  ) {
                res.push(loan_companies[i]);
            }
        }
        if (res.length == 0) res = loan_companies;

        return res;
    };

    $scope.get_months_loan_company = function(loan_companies, loan_company_id){

        var res = null;
        var i=0, len=loan_companies.length;
        for (; i<len; i++) {
            if (loan_companies[i].id == loan_company_id  ) {
                res = loan_companies[i].months;
            }
        }
        if (res == 0) res = null;
        return res;
    };

   $scope.prc_scont = function(index,prodotto_id,products){
      //console.log(products);
      angular.forEach(products, function(product) {
        if(product.id == prodotto_id){
          $scope.forms[index].prodotto_id = product.id;
          $scope.forms[index].prc_sconto = product.prc_sconto;
          $scope.forms[index].bop = product.bop;
        }
      });
    }

    $scope.followupSetDone = function(id){
      $scope.busy = true;
        $http({        
        method: "get",
        url: '/common/activities/setdonefollowup/'+id
            }).success(function(data){ 
                $scope.busy = false;
                
                $scope.loadcomments($scope.thread,$scope.actid);
            });
    }


   $scope.chained_potenza = function(index,val1,val2){
      if($scope.forms[index].pot_disponibile==val1) {
        $scope.forms[index].pot_impegnata = val2;
        return true;
      } else {
        return false;
      }
    };

    
    $scope.validatecf = function(index,cf,p_comune,p_data,p_sesso,p_nazione,p_nome,p_cognome){
    
           $http({
            method: "post",
            url: '/common/module/checkcf/'+cf, 
            data:    { 
                    comune:p_comune, 
                    data: p_data, 
                    sesso: p_sesso, 
                    nazione: p_nazione, 
                    nome: p_nome, 
                    cognome: p_cognome
                  }
                }).success(function(data){ 
                    $scope.message = data.error;
                    $scope.errors = !data.result;
                    $scope.forms[index].p_cf_validated = data.result;
                    return data.result;
                });
         
    };


  $scope.validateCfOnSubmit = function(cf,comune,data,sesso,nazione,nome,cognome){
     $scope.errors = true;
      $http({ 
              method: 'POST', 
              url: '/common/module/checkcf/'+cf, 
              data: { 
                comune:comune, 
                data: data, 
                sesso: sesso, 
                nazione: nazione, 
                nome: nome, 
                cognome: cognome
              } 
            }).success(function(data, status, headers, config) {
                if(data.result===true){
                  $scope.errors = false;
                  $scope.message = '';
                } else {
                  $scope.errors = true;
                  $scope.message = data.error;
                }
            });
    }

  $scope.busy = true;
  $scope.orderByFunction = function(status){
    return parseInt(status.ordering);
  };

  $scope.addItemProgettazione = function(item,index){
    if(!$scope.forms[index].items_progettazione) $scope.forms[index].items_progettazione = [];
    $scope.forms[index].items_progettazione.push(item);
    //$scope.calculate_power(index);
  }
  $scope.addItemProgettazioneSerial = function(item,index){
    if(!$scope.forms[index].items_progettazione) $scope.forms[index].items_progettazione = [];
    var serials = item.serial.split("\n");
    var not_found = [];
     for (var i = 0; i < serials.length; i++) {
              var current = angular.copy(item);
              current.serial = serials[i];
              console.log(not_found);
              if(not_found.indexOf(current.serial)==-1){
                $scope.forms[index].items_progettazione.push(current);
              }
            }
           // $scope.calculate_power(index);
    var duplicated = [];
    if(serials.filter){
        duplicated = serials.filter(function(itm, i){
            return serials.lastIndexOf(itm)== i && serials.indexOf(itm)!= i;
        });
    }
    if(duplicated.length > 0){
              $ngBootbox.alert('I seguenti seriali risultano inseriti più volte:<br>'+duplicated.join('<br>'))
                .then(function() {
            
              });
            } 
    $http.post("/common/activities/check_serials", $scope.forms[index].items_progettazione).success(function(data, status) {
            if(data.serials.length > 0){
              $scope.force_required = true;
              not_found = data.serials;
              $ngBootbox.alert('Attenzione i seguenti seriali risultano incongruenti:<br>'+data.serials.join('<br>')+'<br><br>E\' necessario caricare la foto dei codici incriminati nella sezione allegati.')
                .then(function() {
                    
              });
            } else {
              //console.log(false);
            }

    });

  }
  $scope.removeItemProgettazione = function(act,index){
    $scope.forms[act].items_progettazione.splice(index,1);
    //$scope.calculate_power(act);
  }

  $scope.check_latitudine = (function() {
  //  var regexp = /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/;
    return {
        test: function(value) {
            if(value != null && value.length > 0){
                  value = value.replace(",", ".");
                  if(value >= 35.49 && value <= 47.1){
                      return true;
                  } else {
                      return false;
                  }
            } else {
              return true;
            }
        }
    };
  })();
  
  $scope.check_longitudine = (function() {
    //var regexp = /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/;
    return {
        test: function(value) {
            if(value != null && value.length > 0){
                  value = value.replace(",", ".");
                  if(value >= 6.6255 && value <= 18.522){
                      return true;
                  } else {
                      return false;
                  }
            } else {
              return true;
            }
        }
    };
  })();

  $scope.check_cf = (function(comune,data,sesso,nazione,nome,cognome) {
  //  var regexp = /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/;
    return {
        test: function(value) {
            if(value != null && value.length == 16){
              var request = $http({
                      method: "post",
                      data: {comune:comune, data:data, sesso:sesso, nazione:nazione, nome:nome, cognome:cognome},
                      url: "/common/module/checkcf/"+value,
                      //transformRequest: undefined,
                      headers: {
                       'Content-Type': 'application/json'
                      }
                }); 
               request.success(
                function( data ) {
                 
                  return data.result;
                });
            }

        }        
    };
  })();
  
  $scope.check_time =(function(){
	  var regexp =/^(?:\d|[01]\d|2[0-3]):[0-5]\d$/;
	  return {
	        test: function(value) {
	            if(value != null && value.length > 0){
	                 return regexp.test(value);
	            } else {
	              return true;
	            }
	        }
	    };
  })();
  
  $scope.check_amount =(function(){
	  var regexp = /^\d*(\.\d{1,6})?$/;
	  return {
	        test: function(value) {
	            if(value != null && value.length > 0){
	                 return regexp.test(value);
	            } else {
	              return true;
	            }
	        }
	    };
  })();

  $scope.check_iban =(function(){
    var regexp = /^IT\d{2}[A-Z]\d{10}[0-9A-Z]{12}$/;
    return {
          test: function(value) {
              if(value != null && value.length > 0){
                   return regexp.test(value);
              } else {
                return true;
              }
          }
      };
  })();

  $scope.verificaiban = function(iban,index){
   
   $http.get('/common/module/get_filiale/'+iban).
                    success(function(data, status, headers, config) {
                      if(data.DENOMINAZIONE===undefined){
                        $ngBootbox.alert('Filiale non trovata')
                        .then(function() {
                           $scope.forms[index].verified_iban = false;
                           $scope.forms[index].sdd_banca = '';
                           $scope.forms[index].sdd_filiale = '';
                        });
                      } else {
                        $ngBootbox.confirm(data.DENOMINAZIONE+'<br>'+data.INDIRIZZO+'<br>'+data.COMUNE)
                        .then(function() {
                            $scope.forms[index].verified_iban = true;
                            $scope.forms[index].sdd_banca = data.DENOMINAZIONE;
                            $scope.forms[index].sdd_filiale = data.INDIRIZZO+' '+data.COMUNE;
                        }, function() {
                            $scope.forms[index].verified_iban = false;
                            $scope.forms[index].sdd_banca = '';
                            $scope.forms[index].sdd_filiale = '';
                        });
                      }
                }); 
  };

$scope.busy = true;
  $rootScope.$watch('process', function () {
    $scope.processdata = $rootScope.process;  
    console.log($scope.processdata);
    $scope.busy = false;
  });
    
   
    /* Change View */
    $scope.changeView = function(view,calendar) {
      uiCalendarConfig.calendars[calendar].fullCalendar('changeView',view);
    };
    /* Change View */
    $scope.renderCalender = function(calendar) {
      if(uiCalendarConfig.calendars[calendar]){
        uiCalendarConfig.calendars[calendar].fullCalendar('render');
      }
    };
    /* Render Tooltip */
    $scope.eventRender = function( event, element, view ) { 

      $timeout(function(){
        $(element).attr('tooltip-html-unsafe', event.discription);
        $(element).attr('tooltip-placement','bottom');
        $compile(element)($scope);
      });
    };

    /* config object */
    $scope.uiConfig = {
      calendar:{
        height: 450,
        editable: false,
        header:{
          left: 'prev',
            center: 'title',
            right: 'next'
        }, 
        eventRender: $scope.eventRender
      }
    };
    
    $scope.uiConfig.calendar.dayNames = ["Domenica", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato"];
    $scope.uiConfig.calendar.dayNamesShort = ["Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab"];
    $scope.uiConfig.calendar.monthNames = ["Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno","Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre" ];
    $scope.uiConfig.calendar.monthNamesShort = ['Gen','Feb','Mar','Apr','Mag','Giu','Lug','Ago','Set','Ott','Nov','Dic'];


  $scope.forms.calendar = {
        opened: {},
        dateFormat: 'dd-MM-yyyy',
        dateOptions: {
            formatYear: 'yy',
            startingDay: 1
        },
        open: function($event, which) {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.forms.calendar.opened[which] = true;
        } 
    };


$scope.viewCalendar = function(){
  $scope.view_calendar = !$scope.view_calendar;
  $timeout(function() {
   $('#calendario').fullCalendar('render');
   $('#calendario').fullCalendar('refetchEvents');
 },100);
}

  $scope.$watch('actid', function () {
    $http.get('/common/activities/get_activity/'+$scope.actid).
                    success(function(data, status, headers, config) {
                        // this callback will be called asynchronously
                        // when the response is available
                        $scope.activities[0] = data;

                        
                        if(data.payload){
                          $scope.forms[0] = JSON.parse(data.payload);
                        } else {
                          $scope.forms[0] = {};
                        }

                        /*if(data.order){
                            $scope.forms[0].order = data.order;
                          } else {
                            $scope.forms[0].order = [];
                          }*/
                        $scope.thread = data.id_thread;
                        $scope.variables[0] = {'status':data.status};
                        
                        $scope.loadfiles(data.id_thread,$scope.actid,data.form_id);
                        $scope.loadrequiredfiles(data.form_id);
                        $scope.loadfiletypes(data.form_id);
                        $scope.loadcomments($scope.thread,$scope.actid);

                        $http.get('/common/cases/get_thread/'+$scope.thread).
                            success(function(data, status, headers, config) {
                              $scope.request = data;
                              $scope.tipo_contract = data.be_tipocontracto;
                              $scope.trouble_id = data.trouble_id;
                              $http.get('/common/cases/get_by_customer/'+$scope.request.customer+'/'+$scope.request.id).
                                  success(function(data, status, headers, config) {
                                      // this callback will be called asynchronously
                                      // when the response is available
                                      $scope.related_threads = [];
                                      $scope.related_threads = data;
                              });
                              if($scope.trouble_id){
                                  $scope.locked = false;       
                                  var request = $http({
                                    method: "get",
                                    url: "/common/troubles/detail/"+$scope.trouble_id
                                  });

                                  // Store the data-dump of the FORM scope.
                                  request.success(
                                    function( data ) {
                                      $scope.selected = {type : data.type_id, customer:data.customer_id, contract:data.be_id, duty_company_crm:data.crm_duty_company, duty_company_resolution:data.res_duty_company, duty_user_crm:data.crm_duty_user, duty_user_resolution:data.res_duty_user,res_roles:data.res_role, deadline:data.deadline, description:data.description, status:data.status, result:data.result, subtype:data.subtype, be_contratti:data.contratti};                                
                                      $scope.selected.status  = $scope.selected.status; 
                                      $scope.trouble_status = $scope.selected.status;  	
                                 	 if($scope.selected.contract != ''){
                            	    	 var request = $http({
                            	             method: "post",
                            	             url: '/json/customers/get_contratti/'+$scope.selected.contract,
                            	           });
                            	
                            	           // Store the data-dump of the FORM scope.
                            	           request.success(
                            	             function( data ) {
                            	                 $scope.be_contratti = data;
                            	             }
                            	           );  
                                	 }
                                      $http.get('/json/troubles/automatic_trouble_types/').
                           	               success(function(data, status, headers, config) {
                           	                 // this callback will be called asynchronously
                           	                 // when the response is available
                           	                 $scope.form_types = data;
                           	               }).
                           	               error(function(data, status, headers, config) {
                           	                 // called asynchronously if an error occurs
                           	                 // or server returns response with an error status.
                           	             });
                           	           
                                              $http.get('/json/troubles/status/').
                                                success(function(data, status, headers, config) {
                                                  // this callback will be called asynchronously
                                                  // when the response is available
                                                  $scope.form_status = data;
                                                }).
                                                error(function(data, status, headers, config) {
                                                  // called asynchronously if an error occurs
                                                  // or server returns response with an error status.
                                              });
                                              

                                              $http.get('/json/companies/by_role/').
                                                success(function(data, status, headers, config) {
                                                  // this callback will be called asynchronously
                                                  // when the response is available
                                                  $scope.all_companies = data;
                                                }).
                                                error(function(data, status, headers, config) {
                                                  // called asynchronously if an error occurs
                                                  // or server returns response with an error status.
                                              });
                                      
                                     	 
                                          if($scope.selected.type > 0){
                                 	          var request = $http({
                                 	              method: "get",
                                 	              url: "/json/troubles/check_process_type/"+$scope.selected.type
                                 	            });
                                 	
                                 	            request.success(
                                 	              function( data ) {
                                 	            	  if(data > 0)
                                 	            		  $scope.selected.show_role_company = false;
                                 	            	  else
                                 	            		  $scope.selected.show_role_company = true;
                                 	              }
                                 	            );
                                 	            
                                 	            $http.get('/json/troubles/get_troubles_subtypes/'+$scope.selected.type).
                                 		            success(function(data, status, headers, config) {
                                 		              $scope.form_subtypes = data;
                                 		            }).
                                 		            error(function(data, status, headers, config) {
                                 	           });
                                             } 
                                      
                                   	      var request = $http({
                                   	          method: "get",
                                   	          url: "/json/companies/active_users/"+$scope.selected.duty_company_crm+'/'+'CRM'
                                   	        });

                                   	        // Store the data-dump of the FORM scope.
                                   	        request.success(
                                   	          function( data ) {
                                   	            $scope.crm_users = data;
                                   	          }
                                   	        );        	      
                                    }
                                  );
                               }
                        }); 


                        
                }); 

$scope.loadcomments = function(thread,actid){
   $scope.busy = true;
   $http.get('/json/memos/followup/'+thread+'/'+actid).
        success(function(data, status, headers, config) {
            // this callback will be called asynchronously
            // when the response is available
            $scope.comments = data;
            $scope.busy = false;
    }); 
}

$scope.insertFollowup = function(){
  $scope.busy = true;
  $scope.followup_process = true;
  $http({        
	method: "post",
	url: '/common/activities/savefollowup/', 
	transformRequest: transformRequestAsFormPost,
	data: {actid:$scope.actid,text:$scope.followup.description,scheduled:$scope.followup.scheduler,day:$scope.followup.day,time:$scope.followup.time}
	    }).success(function(data){ 
	        $scope.busy = false;
	        $scope.followup.description = '';
	$scope.followup.scheduler = false;
	$scope.followup.day = '';
	$scope.followup.time = '';
	 $scope.followup_process = '';
	    $scope.loadcomments($scope.thread,$scope.actid);
	});
}


$scope.setPending = function () {
          $scope.busy = true;
       
              if($scope.thread) {
                if($scope.request.pending){
                  $ngBootbox.confirm("Sei sicuro di voler impostare lo status del Thread in PENDING?")
                              .then(function() {
                                  $http({        
                                    method: "post",
                                    url: '/common/cases/pending/'+$scope.thread, 
                                    data: {set:$scope.request.pending,reason:$scope.request.pending_reason,related:$scope.request.pending_related}
                                        }).success(function(data){ 
                                            $scope.busy = false;
                                            location.reload();
                                        });
                                     
                              }, function() {
                                   $scope.busy = false;
                              });
                } else {
                  $ngBootbox.confirm("Sei sicuro di voler ripristinare il Thread?")
                              .then(function() {
                                  $http({        
                                    method: "post",
                                    url: '/common/cases/pending/'+$scope.thread, 
                                    data: {set:$scope.request.pending,reason:$scope.request.pending_reason,related:$scope.request.pending_related}
                                        }).success(function(data){ 
                                            $scope.busy = false;
                                            $scope.mode = 'edit';
                                            location.reload();
                                        });
                                     
                              }, function() {
                                   $scope.busy = false;
                              });
                }
              }  
      };
    
    
      
    
  });
  $scope.loadRequiredAttachments = function(form_id){
	  $http.get('/common/module/get_required_attach/'+form_id).
	      success(function(data, status, headers, config) {
	          // this callback will be called asynchronously
	          // when the response is available
	          $scope.arr_required_attachments[form_id] = data.result;
	          $scope.busy = false;
	  }); 
  }
    
  $scope.loadAttachments = function(thread,form_id){
	    $scope.busy = true;
	    $http.get('/common/activities/get_attachment/'+thread+'/'+form_id).
	                    success(function(data, status, headers, config) {
	                    	$scope.attachments = {};
	                        if(typeof data.attachments != 'undefined' && data.attachments.length > 0){
	                          $scope.attachments = data.attachments;
	                        }
	                        $scope.collections = {};
	                        if(typeof data.collection != 'undefined' && data.collection.length > 0){
	                          $scope.collections = data.collection;
	                        }
	                        $scope.busy = false;
	                }); 
	   
	  };

    $scope.loadfiletypes = function(form_id){
      $scope.busy = true;
      $http.get('/common/activities/attach_types/'+form_id).
                      success(function(data, status, headers, config) {
                          if(data.length > 0){
                            $scope.filetypes = data;
                          } else {
                            $scope.filetypes = {};
                          }
                          $scope.busy = false;
                  }); 
     
    };
	  
  $scope.loadfiles = function(thread,activity,form_id){
    $scope.busy = true;
    $http.get('/common/attachments/list_files/'+thread+'/'+activity).
        success(function(data, status, headers, config) {
            // this callback will be called asynchronously
            // when the response is available
        	
        	if(typeof form_id == 'undefined'){
        		$http.get('/common/activities/get_single_activity/'+activity).
                success(function(value, status, headers, config) {
                	if(typeof value.form_id != 'undefined'){
                		form_id = value.form_id;
                		if(data.result) {
                		if(data.result.length > 0){
                            $scope.listfiles = data.result;
                            $scope.arr_attachments[form_id] = data.result;
                          } else {
                            $scope.listfiles = {};
                            $scope.arr_attachments[form_id] = undefined;
                          }
                          $scope.busy = false;
                		}
                	}
                }); 
        	}else{
        		if(data.result) {
        		if(data.result.length > 0){
                    $scope.listfiles = data.result;
                    $scope.arr_attachments[form_id] = data.result;
                  } else {
                    $scope.listfiles = {};
                    $scope.arr_attachments[form_id] = undefined;
                  }
                  $scope.busy = false;
        		}
        	}
            
    }); 
   
  };

  $scope.loadrequiredfiles = function(form_id){

     $http.get('/common/module/get_required_attach/'+form_id).
            success(function(data, status, headers, config) {
                // this callback will be called asynchronously
                // when the response is available
                $scope.required_attachments = data.result;
                $scope.busy = false;
        }); 
  };

  $scope.loadrequiredfilesThread = function(form_id){

     $http.get('/common/cases/get_required_attach/'+form_id).
            success(function(data, status, headers, config) {
                // this callback will be called asynchronously
                // when the response is available
                $scope.required_attachments_thread = data.result;
                $scope.busy = false;
        }); 
  };


  //listen for the file selected event
  $scope.$on("fileSelected", function (event, args) {
      $scope.$apply(function () {            
          //add the file object to the scope's files collection
          $scope.files.push(args.file);
      });
  });

$scope.setFiles = function(element) {
      $scope.$apply(function($scope) {
        console.log('files:', element.files);
        // Turn the FileList object into an Array
          $scope.files = []
          for (var i = 0; i < element.files.length; i++) {
            $scope.files.push(element.files[i])
          }
        $scope.progressVisible = false
        });
    };

    $scope.upload = function(thread,activity) {
        var uploadUrl = '/common/attachments/upload/'+thread+'/'+activity;
        var fd = new FormData()
        for (var i in $scope.files) {
            fd.append("userfile", $scope.files[i])
        }
        fd.append("attach_type",$scope.filedata.attach_type)
        if($scope.filedata.description){
          fd.append("description",$scope.filedata.description)
        } else {
          fd.append("description","")
        }
        var xhr = new XMLHttpRequest()
        xhr.upload.addEventListener("progress", $scope.uploadProgress, false)
        xhr.addEventListener("load", $scope.uploadComplete, false)
        xhr.addEventListener("error", $scope.uploadFailed, false)
        xhr.addEventListener("abort", $scope.uploadCanceled, false)
        xhr.open("POST", uploadUrl)
        $scope.progressVisible = true
        xhr.send(fd)
    }

    $scope.deleteFile = function(id,thread_id,activity_id){
      $http.get('/common/attachments/delete_file/'+id+'/'+thread_id+'/'+activity_id).
                    success(function(data, status, headers, config) {
                        $scope.loadfiles(thread_id,activity_id);
                }); 
    };

    $scope.uploadProgress = function(evt) {
        $scope.$apply(function(){
            $scope.filedata.busy = true
            if (evt.lengthComputable) {
                $scope.progress = Math.round(evt.loaded * 100 / evt.total)
            } else {
                $scope.progress = 'unable to compute'
            }
        })
    }

    $scope.uploadComplete = function(evt) {
        /* This event is raised when the server send back a response */
        //alert(evt.target.responseText)
        var response = JSON.parse(evt.target.responseText);
        
        if(!response.result){
          $scope.filedata.errors = true;
          $scope.filedata.error = response.error;
          $scope.filedata.busy = false;
        } else {
          $scope.filedata.errors = false;
          $scope.filedata.busy = false;
          $scope.loadfiles(response.id_thread,response.id_act);
        }
        
         $scope.$digest();
    }

    $scope.uploadFailed = function(evt) {
        var response = JSON.parse(evt.target.responseText);
        if(!response.result){
          $scope.filedata.error = response.error;
          $scope.filedata.errors = true;
        } else {
          $scope.filedata.errors = false;
        }

        $scope.filedata.busy = false;
        $scope.$digest();
    }

    $scope.uploadCanceled = function(evt) {
        $scope.$apply(function(){
           $scope.progressVisible = false
        })
        $scope.filedata.errors = "The upload has been canceled by the user or the browser dropped the connection.";
        $scope.filedata.busy = false;
        $scope.$digest();
    }

  $scope.saveForm = function(index,url,id,json) {
      window.onbeforeunload = null;      
      $scope.disableSubmit = true;
      if($scope.request.master_status == 'PENDING'){
         $ngBootbox.alert('Thread in PENDING status can not be changed!');
         return false;
      }

        $http.defaults.useXDomain = true;
        delete $http.defaults.headers.common['X-Requested-With'];
        //console.log($scope.forms[index]);


                    $http({
                      method: "get",
                      url: "/common/cases/draft/"+$scope.thread
                    });

        //if($scope.filedata.errors) return false;
	  if(typeof json == 'undefined'){
		  json= false;
	  }
	  
        angular.forEach($scope.activities[index].statuses, function(value, key) {
          if(value.key == $scope.variables[index].status){
            if(value.final == 't'){
              if($scope.errors===true) return false;
            	var attached = [];
                var i = 0;
                angular.forEach($scope.arr_attachments[$scope.activities[index].form_id], function(value, key) {
                      attached.push(value.attach_type);
                });
                angular.forEach($scope.arr_required_attachments[$scope.activities[index].form_id], function(value, key) {
                      if(value.required=='t' && $scope.force_required===false){
                        if(attached.indexOf(value.id_attachment) == -1){
                          i++;
                        }
                      } else if($scope.force_required===true){
                        if(attached.indexOf(value.id_attachment) == -1){
                          i++;
                        }
                      }
                });
                
                if(i > 0){
                	console.log('missing required files');
                  if($scope.force_required){
                    $scope.filedata.error = "It is necessary to insert the photo as a proof of the serial number.";
                  } else {
                    $scope.filedata.error = "Before proceeding, all mandatory attachments must be uploaded.";
                  }
                    $scope.filedata.errors = true;
                }else{
                	 $ngBootbox.confirm('Are you sure you want to close the business?')
                     .then(function() {
                    	 if(json== true){
                    		 var request = $http({
                    	                         method: "post",
                    	                         url: "/common/cases/save_activity_process/"+id,
                    	                         data: angular.toJson($scope.forms[index]),
                                               transformRequest: false
                    	                       });
                    	}else{
                    		var request = $http({
                    	                         method: "post",
                    	                         url: "/common/cases/save_activity_process/"+id,
                    	                         transformRequest: transformRequestAsFormPost,
                    	                         data: $scope.forms[index]
                    	                       });
                    	}
                        /*var request = $http({
                         method: "post",
                         url: "/common/cases/save_activity_process/"+id,
                         transformRequest: transformRequestAsFormPost,
                         data: $scope.forms[index]
                       });*/
                       request.success(
                         function( data ) {
                           delete $scope.forms[index].errors;
                           $scope.forms[index].status = $scope.variables[index].status;
                           $scope.forms[index].activity = id;
                           $scope.forms[index].thread = $scope.thread;
                           if(data.result===true){
                        	   if(json== true){
                        		   var form_request = $http({
                                       method: "post",
                                       url: url,
                                       data: angular.toJson($scope.forms[index])
                                     });
                        		   
                        	   }else{
                        		   var form_request = $http({
                                       method: "post",
                                       url: url,
                                       transformRequest: transformRequestAsFormPost,
                                       data: $scope.forms[index]
                                     });
                        	   }
                               
                               form_request.success(function(data, status, headers, config) {
                                   // this callback will be called asynchronously
                                   // when the response is available
                                   
                                   if(data.result === true){
                                     window.location = "/common/activities";
                                   } else {
                                     $scope.forms[index].errors = data.error;
                                   }
                               }).
                               error(function(data, status, headers, config) {
                                   // called asynchronously if an error occurs
                                   // or server returns response with an error status.
                               });
                           }
                         }
                       );
                     }, function() {
                         return false;
                     });
                }
               
            } else {
            	if(json== true){
	            	var request = $http({
	                    method: "post",
	                    url: "/common/cases/save_activity_process/"+id,
	                    data: angular.toJson($scope.forms[index])
	                  });
		       	}else{
			       	var request = $http({
	                    method: "post",
	                    url: "/common/cases/save_activity_process/"+id,
	                    transformRequest: transformRequestAsFormPost,
	                    data: $scope.forms[index]
	                  });
		       	}
              /*var request = $http({
                            method: "post",
                            url: "/common/cases/save_activity_process/"+id,
                            transformRequest: transformRequestAsFormPost,
                            data: $scope.forms[index]
                          });*/
                          request.success(
                            function( data ) {
                              delete $scope.forms[index].errors;
                              $scope.forms[index].status = $scope.variables[index].status;
                              $scope.forms[index].activity = id;
                              $scope.forms[index].thread = $scope.thread;
                              if(data.result===true){
                            	  if(json== true){
                            		  var form_request= $http({
                                          method: "POST",
                                          url: url,
                                          data: angular.toJson($scope.forms[index])
                                        });
                            	  }else{
                            		  var form_request= $http({
                                          method: "POST",
                                          url: url,
                                          transformRequest: transformRequestAsFormPost,
                                          data: $scope.forms[index]
                                        });
                            	  }
                                 
                                  
                                  form_request.success(function(data, status, headers, config) {
                                      // this callback will be called asynchronously
                                      // when the response is available
                                      
                                      if(data.result === true){
                                       window.location = "/common/activities";
                                      } else {
                                        $scope.forms[index].errors = data.error;
                                      }
                                  }).
                                  error(function(data, status, headers, config) {
                                      // called asynchronously if an error occurs
                                      // or server returns response with an error status.
                                  });
                              }
                            }
                          );
            }
          }
        });
        

    };
$scope.loadmemo = function(company){
    $http.get('/json/memos/get/'+company).success(function(data) {
                 $scope.busy_calendar = false;
                 var memos = data.result;
                 var rawArray = [];
                 for(var i=0; i< memos.length; i++)
                 {
                   var startDateArray = null;
                   var startTimeArray = null;
                   var endDateArray = null;
                   var endTimeArray = null;
                   var allDay = null;
                   var className = 'b-l b-2x b-info';
                   var startDate = null;
                   var endDate = null;
                   if(memos[i].start_day != null && memos[i].start_day != '') {
                     startDateArray = memos[i].start_day.split("/");
                     startTimeArray = memos[i].start_time.split(":");
                    
                     startDate = new Date(startDateArray[2], startDateArray[1]-1, startDateArray[0], startTimeArray[0], startTimeArray[1]);
                   }
                   if(memos[i].end_day != null && memos[i].end_day != '') {
                     endDateArray = memos[i].end_day.split("/");
                     endTimeArray = memos[i].end_time.split(":");
                  
                     endDate = new Date(endDateArray[2], endDateArray[1]-1, endDateArray[0], endTimeArray[0], endTimeArray[1]);
                   }
                   if(memos[i].all_day != null && memos[i].all_day != '') {
                     if(memos[i].all_day == 'f') {
                       allDay = false;
                     } else if(memos[i].all_day == 't') {
                       allDay = true;
                     }
                   }
                   rawArray.push({id: memos[i].id, thread_id: memos[i].thread_id, activity_id: memos[i].activity_id, title: memos[i].title, discription: memos[i].description, start:startDate, end:endDate, allDay:allDay, className:className});
                 }
          
                
               $scope.caldata[0] = rawArray;

                console.log($scope.caldata);
              });
}

$scope.loadmemo_allaccio = function(company){
    $http.get('/json/memos/get/'+company+'/soprall_company_id').success(function(data) {
                 $scope.busy_calendar = false;
                 var memos = data.result;
                 var rawArray = [];
                 for(var i=0; i< memos.length; i++)
                 {
                   var startDateArray = null;
                   var startTimeArray = null;
                   var endDateArray = null;
                   var endTimeArray = null;
                   var allDay = null;
                   var className = 'b-l b-2x b-info';
                   var startDate = null;
                   var endDate = null;
                   if(memos[i].start_day != null && memos[i].start_day != '') {
                     startDateArray = memos[i].start_day.split("/");
                     if(memos[i].start_time != null && memos[i].start_time != '') {
                        startTimeArray = memos[i].start_time.split(":");
                      } else {
                        startTimeArray = [];
                      }
                     
                    
                     startDate = new Date(startDateArray[2], startDateArray[1]-1, startDateArray[0], startTimeArray[0], startTimeArray[1]);
                   }
                   if(memos[i].end_day != null && memos[i].end_day != '') {
                     endDateArray = memos[i].end_day.split("/");
                      if(memos[i].end_time != null && memos[i].end_time != '') {
                        endTimeArray = memos[i].end_time.split(":");
                      } else {
                        endTimeArray = [];
                      }
                  
                     endDate = new Date(endDateArray[2], endDateArray[1]-1, endDateArray[0], endTimeArray[0], endTimeArray[1]);
                   }
                   if(memos[i].all_day != null && memos[i].all_day != '') {
                     if(memos[i].all_day == 'f') {
                       allDay = false;
                     } else if(memos[i].all_day == 't') {
                       allDay = true;
                     }
                   }
                   rawArray.push({id: memos[i].id, thread_id: memos[i].thread_id, activity_id: memos[i].activity_id, title: memos[i].title, discription: memos[i].description, start:startDate, end:endDate, allDay:allDay, className:className});
                 }
          
                
               $scope.caldata[0] = rawArray;

                //console.log($scope.caldata);
              });
}

    $scope.creditCheck = function(index,thread_id){
    $scope.variables[index].status = 'VERIFY';
    $scope.cc_busy = true;
    var request = $http({
          method: "post",
          url: "/common/cribis_check/check/"+thread_id,
          transformRequest: transformRequestAsFormPost,
        });

        // Store the Credit check response.
        request.success(
          function( data ) {
            
            $scope.cc_busy = false;
            
            
            if(data.success){

              $scope.cc_error = false;
              var date = new Date(data.response.TransactionResponse.Details.ResponseTimestamp);
                
                var a = new Date(date.getTime());
                var year = a.getFullYear();
                var month = a.getMonth()+1;
                var date = a.getDate();
                var hour = a.getHours();
                var min = a.getMinutes();
                if(min <10){
                	min = '0'+min;
                }
                if($scope.forms[index] == null){
                $scope.forms[index] = new Array();
                }
                
                $scope.forms[index].cc_created_date = date+'/'+month+'/'+year;
                $scope.forms[index].cc_created_time = hour+':'+min;
                $scope.forms[index].cc_pdf_name = data.pdf_name;
                $scope.forms[index].cc_protesti = 'OK';
                $scope.forms[index].cc_pregiudizievoli = 'OK';
                $scope.forms[index].cc_procedure_concorsuali = 'OK';
                if(data.protesti){
                  $scope.forms[index].cc_protesti = 'KO';
                }
                if(data.pregiudizievoli){
                  $scope.forms[index].cc_pregiudizievoli = 'KO';
                }
                if(data.procedure_concorsuali){
                  $scope.forms[index].cc_procedure_concorsuali = 'KO';
                }

            }else{
              $scope.cc_error = true;
              $scope.cc_message = data.message;
            }
          }
        );
        
        request.error(
          function( data ) {
            $scope.cc_busy = false;
        });
  }
  
  $scope.downloadpdf = function (pdf_name){
    var request = $http({
          method: "post",
          url: "/common/cribis_check/download/",
          data: {file:pdf_name},
          transformRequest: transformRequestAsFormPost,
        });
    
  // Store the Credit check response.
      request.success(
        function( data ) {
          
        }
      );
  }
  
  $scope.get_status_filter = function(statuses,status){
	  var check = false;
	  angular.forEach(statuses, function(item,key) {
		  if(item.key == status && item.final == 't'){
			  check = true;
		  }
	  });
	  
	  return check;
  }
  
  

})


.controller("Trouble", function( $scope, $rootScope, $http, $timeout, $ngBootbox,transformRequestAsFormPost, $q ) {
    $scope.customer = '';
    $scope.form_types = [];
    $scope.selected = {type : '', customer:'', contract:'', duty_company_crm:'', duty_company_resolution:'', status:'', be_contratti: ''};
    $scope.loadtemplate = false;
    $scope.filedata = {};
    $scope.activity_related = '/common/troubles/related/';
    $scope.activity_summary = "/common/cases/activity_summary";
    $scope.activity_followup = '/common/cases/activity_followup/';
    $scope.showdashboard = false;
    $scope.dashboard = '';
    $scope.locked = true;
    $scope.is_thread_view = false;
    $scope.followup = [];
    var selected_option = '';
    $scope.followupSetDone = function(id){
      $scope.busy = true;
        $http({        
            method: "get",
            url: '/common/activities/setdonefollowup/'+id
                }).success(function(data){ 
                    $scope.busy = false;
                    
                    $scope.loadcomments($scope.trouble_id);
                });
    }

     $scope.$watch('trouble_id', function () {

     if($scope.trouble_id != ''){
        $scope.locked = false;
        $scope.loadcomments($scope.trouble_id);
        var request = $http({
          method: "get",
          url: "/common/troubles/detail/"+$scope.trouble_id
        });

        // Store the data-dump of the FORM scope.
        request.success(
          function( data ) {
            $scope.selected = {type : data.type_id, customer:data.customer_id, contract:data.be_id, duty_company_crm:data.crm_duty_company, duty_company_resolution:data.res_duty_company, duty_user_crm:data.crm_duty_user, duty_user_resolution:data.res_duty_user,res_roles:data.res_role, deadline:data.deadline, description:data.description, status:data.status, result:data.result, subtype:data.subtype, be_contratti:data.contratti};
            $scope.trouble_status = $scope.selected.status;
            $scope.selected.status  = $scope.selected.status;
            $scope.loadfiles($scope.trouble_id);
            $scope.campagna_id = data.campagna_id;

            $scope.dashboard = '/common/troubles/dashboard/'+$scope.selected.customer+'/'+$scope.trouble_id;


            $http.get('/common/cases/get_request_by_trouble_id/'+$scope.selected.customer+'/null/'+$scope.trouble_id).
                    success(function(data, status, headers, config) {
                        // this callback will be called asynchronously
                        // when the response is available
                        $scope.related_threads = [];
                        $scope.related_threads = data;
                });
             $http.get('/common/troubles/get_customer/'+data.customer_id).
                      success(function(data, status, headers, config) {
                        // this callback will be called asynchronously
                        // when the response is available
                        $scope.selected.customer = data;
                        
                       // $scope.setCustomer(data.customer_id);

                       var request = $http({
                          method: "post",
                          url: "/json/customers/contracts",
                          transformRequest: transformRequestAsFormPost,
                          data: {
                            user:data.id
                          }
                        });

                        // Store the data-dump of the FORM scope.
                        request.success(
                          function( data ) {
                            $scope.contracts = data;
                          }
                        );
                        
                       /* if(data.be_id!=null){
                          $http.get('/common/troubles/get_contract/'+data.be_id).
                                success(function(data, status, headers, config) {
                                  // this callback will be called asynchronously
                                  // when the response is available
                                  $scope.contract = data[0];
                                  $scope.selected.contract = data.be_id;
                          });
                        }
                        */
                  });
          }
        );


     }
      
    },true);
     
     $scope.$watch('selected.contract', function () {
    	 
    	 if($scope.selected.contract != ''){
	    	 var request = $http({
	             method: "post",
	             url: '/json/customers/get_contratti/'+$scope.selected.contract,
	           });
	
	           // Store the data-dump of the FORM scope.
	           request.success(
	             function( data ) {
	                 $scope.be_contratti = data;
	             }
	           );  
    	 }
    	 
     });
     
     // Check the trouble type have any automatic porcess
     $scope.$watch('selected.type', function () {
    	 
         if($scope.selected.type > 0){
	          var request = $http({
	              method: "get",
	              url: "/json/troubles/check_process_type/"+$scope.selected.type
	            });
	
	            request.success(
	              function( data ) {
	            	  if(data > 0)
	            		  $scope.selected.show_role_company = false;
	            	  else
	            		  $scope.selected.show_role_company = true;
	              }
	            );
	            
	            $http.get('/json/troubles/get_troubles_subtypes/'+$scope.selected.type).
		            success(function(data, status, headers, config) {
		              $scope.form_subtypes = data;
		            }).
		            error(function(data, status, headers, config) {
	           });
            } 
        },true);
  
 $scope.loadcomments = function(trouble){
   $scope.busy = true;
   $http.get('/json/memos/followup/null/null/'+trouble).
        success(function(data, status, headers, config) {
            // this callback will be called asynchronously
            // when the response is available
            $scope.comments = data;
            $scope.busy = false;
    }); 
}

$scope.insertFollowup = function(){
  var pattern = /^(([01][0-9]|2[0-3])h)|(([01][0-9]|2[0-3]):[0-5][0-9])$/;
  $scope.followup_process = true;
  $scope.dataerror = "";
  if(!pattern.test($('#followup-time').val()) && $scope.followup.scheduler==true) {
	  $scope.dataerror = "Invalid time";
	  return false;
  }

  $scope.busy = true;
  $http({        
	method: "post",
	url: '/common/troubles/savefollowup/', 
	transformRequest: transformRequestAsFormPost,
	data: {trouble:$scope.trouble_id,text:$scope.followup.description,scheduled:$scope.followup.scheduler,day:$scope.followup.day,time:$scope.followup.time}
	    }).success(function(data){ 
	        $scope.busy = false;
	        $scope.followup.description = '';
	$scope.followup.scheduler = false;
	$scope.followup.day = '';
	$scope.followup.time = '';
	 $scope.followup_process = '';
	    $scope.loadcomments($scope.trouble_id);
	}).error(function(e){
		$scope.serverError  = "Internal server error";
	});
}
    $scope.getCustomers = function(val) {
      return $http.get('/json/customers', {
        params: {
          q: val
        }
      }).then(function(response){
        return response.data.map(function(item){
          return item;
        });
      });
    };

    $scope.setCustomer = function(item) {
        $scope.selected.customer = item;
        var request = $http({
          method: "post",
          url: "/json/customers/contracts",
          transformRequest: transformRequestAsFormPost,
          data: {
            user:item.id
          }
        });

        // Store the data-dump of the FORM scope.
        request.success(
          function( data ) {
            $scope.contracts = data;
          }
        );
    };

    $scope.$watch('selected.res_roles', function () {
        
        if($scope.selected.res_roles=='') return;
       // console.log($scope.selected.duty_company_resolution);

        //$scope.selected.duty_company_resolution = '';   
       // $scope.selected.duty_user_resolution = '';   
        
        var request = $http({
            method: "get",
            url: "/json/companies/by_role/"+$scope.selected.res_roles
          });

          // Store the data-dump of the FORM scope.
          request.success(
            function( data ) {
            	//$scope.selected.duty_company_resolution = $scope.selected.duty_company_crm;   
               // $scope.selected.duty_user_resolution = '';   
              $scope.resolution_companies = data;
            }
          );
        
      },true);


    $scope.$watch('selected.duty_company_resolution', function () {
      //if($scope.trouble_id != '') return;
      if($scope.selected.duty_company_resolution=='') return;
      console.log($scope.selected.duty_company_resolution);

      
      var request = $http({
          method: "get",
          url: "/json/companies/active_users/"+$scope.selected.duty_company_resolution+'/'+$scope.selected.res_roles
        });

        // Store the data-dump of the FORM scope.
        request.success(
          function( data ) {
            $scope.resolution_users = data;
          }
        );
      
    },true);


    $scope.saveTrouble = function(){
      $(window).off('beforeunload');
      if(!$scope.trouble_id){
       $http({
            method: "post",
            url: '/common/troubles/create_trouble/', 
            data:    $scope.selected
                }).success(function(data){ 
                           
                    location.href = location.origin + '/common/troubles/edit/'+data.result;               
                });
      } else {

        $http({
            method: "post",
            url: '/common/troubles/edit_trouble/'+$scope.trouble_id, 
            data:    $scope.selected
                }).success(function(data){ 
                    
                    //$scope.trouble = data.result;
                    location.reload();
                });

      }
    }


    //listen for the file selected event
  $scope.$on("fileSelected", function (event, args) {
      $scope.$apply(function () {            
          //add the file object to the scope's files collection
          $scope.files.push(args.file);
      });
  });

$scope.setFiles = function(element) {
      $scope.$apply(function($scope) {

        // Turn the FileList object into an Array
          $scope.files = []
          for (var i = 0; i < element.files.length; i++) {
            $scope.files.push(element.files[i])
          }
        $scope.progressVisible = false
        });
    };

  $scope.upload = function() {
        var uploadUrl = '/common/attachments/upload/null/null/'+$scope.trouble_id;
        //console.log($scope.filedata);
        var fd = new FormData();
        for (var i in $scope.files) {
            fd.append("userfile", $scope.files[i])
        }
        //fd.append("attach_type",$scope.filedata.attach_type)
        if($scope.filedata.description){
          fd.append("description",$scope.filedata.description)
        } else {
          fd.append("description","")
        }

        var xhr = new XMLHttpRequest()
        xhr.upload.addEventListener("progress", $scope.uploadProgress, false)
        xhr.addEventListener("load", $scope.uploadComplete, false)
        xhr.addEventListener("error", $scope.uploadFailed, false)
        xhr.addEventListener("abort", $scope.uploadCanceled, false)
        xhr.open("POST", uploadUrl)
        $scope.progressVisible = true
        xhr.send(fd)
    }

    $scope.deleteFile = function(id,trouble_id){
      $http.get('/common/attachments/delete_file/'+id).
                    success(function(data, status, headers, config) {
                        $scope.loadfiles(trouble_id);
                }); 
    };

    $scope.uploadProgress = function(evt) {
        $scope.$apply(function(){
            $scope.filedata.busy = true
            if (evt.lengthComputable) {
                $scope.progress = Math.round(evt.loaded * 100 / evt.total)
            } else {
                $scope.progress = 'unable to compute'
            }
        })
    }

    $scope.uploadComplete = function(evt) {
        /* This event is raised when the server send back a response */
        //alert(evt.target.responseText)
        var response = JSON.parse(evt.target.responseText);
        
        if(!response.result){
          $scope.filedata.errors = true;
          $scope.filedata.error = response.error;
          $scope.filedata.busy = false;
        } else {
          $scope.filedata.errors = false;
          $scope.filedata.busy = false;
          $scope.loadfiles(response.id_trouble);
        }
        
         $scope.$digest();
    }
    
	

    $scope.uploadFailed = function(evt) {
        var response = JSON.parse(evt.target.responseText);
        if(!response.result){
          $scope.filedata.error = response.error;
          $scope.filedata.errors = true;
        } else {
          $scope.filedata.errors = false;
        }

        $scope.filedata.busy = false;
        $scope.$digest();
    }

    $scope.uploadCanceled = function(evt) {
        $scope.$apply(function(){
           $scope.progressVisible = false
        })
        $scope.filedata.errors = "The upload has been canceled by the user or the browser dropped the connection.";
        $scope.filedata.busy = false;
        $scope.$digest();
    }

    $scope.loadfiles = function(trouble){
    $scope.busy = true;
    $http.get('/common/attachments/list_files/null/null/'+trouble).
                    success(function(data, status, headers, config) {
                        // this callback will be called asynchronously
                        // when the response is available
                              if(data.result.length > 0){
                                $scope.listfiles = data.result;
                                $scope.arr_attachments = data.result;
                              } else {
                                $scope.listfiles = {};
                                $scope.arr_attachments = undefined;
                              }
                              $scope.busy = false;
                }); 
   
  };

    $scope.$watch('selected.contract', function () {
    	if($scope.trouble_id == ''){
            $http.get('/json/troubles/manual_types/').
              success(function(data, status, headers, config) {
                // this callback will be called asynchronously
                // when the response is available
                $scope.form_types = data;
              }).
              error(function(data, status, headers, config) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
            });
    } else{
    	$http.get('/json/troubles/automatic_trouble_types/').
        success(function(data, status, headers, config) {
          // this callback will be called asynchronously
          // when the response is available
          $scope.form_types = data;
        }).
        error(function(data, status, headers, config) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
      });
    }
            $http.get('/json/troubles/status/').
              success(function(data, status, headers, config) {
                // this callback will be called asynchronously
                // when the response is available
                $scope.form_status = data;
              }).
              error(function(data, status, headers, config) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
            });

            $http.get('/json/companies/setup_roles/').
            success(function(data, status, headers, config) {
              // this callback will be called asynchronously
              // when the response is available
              $scope.setup_roles = data;
              //$scope.selected.type = $scope.loaded.type_id;
            }).
            error(function(data, status, headers, config) {
              // called asynchronously if an error occurs
              // or server returns response with an error status.
          });
            


            $http.get('/json/companies/by_role/').
              success(function(data, status, headers, config) {
                // this callback will be called asynchronously
                // when the response is available
                $scope.all_companies = data;
              }).
              error(function(data, status, headers, config) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
            });
    },true);
})
.controller("Thread", function( $scope, $rootScope, $http, $timeout, $ngBootbox,transformRequestAsFormPost, $q ) {
    $scope.roles = [];
    $scope.user = '';
    $scope.customer = '';
    $scope.form_types = [];
    $scope.selected = {template : '', customer:'', contract:''};
    $scope.loadtemplate = false;
    $scope.process = '';
    $scope.mode = 'add';
    $scope.thread = '';
    $scope.loadactivities = false;
    $scope.activities = [];
    $scope.rel_activities = [];
    $scope.activity_template = "/common/cases/activity_main";
    $scope.activity_summary = "/common/cases/activity_summary";
    $scope.activity_pending = "/common/cases/activity_pending";
    $scope.activity_related = "/common/cases/activity_related";
    $scope.activity_followup = '/common/cases/activity_followup/';
    $scope.process_list = '/common/cases/process_list/';
    $scope.activity_integrations = '/common/cases/activity_integrations/';
    $scope.pending = false;
    $scope.forms = [];
    $scope.variables = [];
    $scope.date = new Date();
    $scope.request = [];
    $scope.request.mode = '';
    $scope.bo_form_data = [];
    $scope.filedata = {};
    $scope.arr_required_attachments = [];
    $scope.errors = false;
    $scope.followup = [];


    $scope.followupSetDone = function(id){
      $scope.busy = true;
        $http({        
        method: "get",
        url: '/common/activities/setdonefollowup/'+id
            }).success(function(data){ 
                $scope.busy = false;
                
                $scope.loadcomments($scope.thread.id);
            });
    }


    $scope.$watch('request.draft', function () {
 
    if($scope.request.draft == 't'){
     window.onbeforeunload = function (event) {
          return "Sei sicuro di voler abbandonare la pagina? I dati non salvati andranno persi.";      
      };
    } else {
      window.onbeforeunload = null;
    }
    
  },true);

    $scope.loadcomments = function(thread){
   $scope.busy = true;
   $http.get('/json/memos/followup/'+thread).
        success(function(data, status, headers, config) {
            // this callback will be called asynchronously
            // when the response is available
            $scope.comments = data;
            $scope.busy = false;
    }); 
}

$scope.insertFollowup = function(){
  $scope.busy = true;
  $scope.followup_process = true;
  $http({        
        method: "post",
        url: '/common/cases/savefollowup/', 
        transformRequest: transformRequestAsFormPost,
        data: {thread:$scope.thread.id,text:$scope.followup.description,scheduled:$scope.followup.scheduler,day:$scope.followup.day,time:$scope.followup.time}
            }).success(function(data){ 
                $scope.busy = false;
                $scope.followup.description = '';
                $scope.followup.scheduler = false;
                $scope.followup.day = '';
                $scope.followup.time = '';
                $scope.followup_process = '';
                $scope.loadcomments($scope.thread.id);
            });
}

    $scope.setPending = function () {
      $scope.busy = true;
   // console.log($scope.thread);
          if($scope.thread.id) {
            if($scope.request.pending){
              $ngBootbox.confirm("Are you sure you want to set the status of the Thread in PENDING?")
                          .then(function() {
                              $http({        
                                method: "post",
                                url: '/common/cases/pending/'+$scope.thread.id, 
                                data: {set:$scope.request.pending,reason:$scope.request.pending_reason,related:$scope.request.pending_related}
                                    }).success(function(data){ 
                                        $scope.busy = false;
                                        location.reload();
                                    });
                                 
                          }, function() {
                               $scope.busy = false;
                          });
            } else {
              $ngBootbox.confirm("Are you sure you want to restore the Thread?")
                          .then(function() {
                              $http({        
                                method: "post",
                                url: '/common/cases/pending/'+$scope.thread.id, 
                                data: {set:$scope.request.pending,reason:$scope.request.pending_reason,related:$scope.request.pending_related}
                                    }).success(function(data){ 
                                        $scope.busy = false;
                                        $scope.mode = 'edit';
                                        location.reload();
                                    });
                                 
                          }, function() {
                               $scope.busy = false;
                          });
            }
          }  
  };
    


    $scope.prc_scont = function(index,prodotto_id,products){
      //console.log(products);
      angular.forEach(products, function(product) {
        if(product.id == prodotto_id){
          $scope.forms[index].prodotto_id = product.id;
          $scope.forms[index].prc_sconto = product.prc_sconto;
          $scope.forms[index].bop = product.bop;
        }
      });
    }
    

    $scope.chained_potenza = function(index,val1,val2){
      if($scope.forms[index].pot_disponibile==val1) {
        $scope.forms[index].pot_impegnata = val2;
        return true;
      } else {
        return false;
      }
    };

    $scope.validatecf = function(index,cf,p_comune,p_data,p_sesso,p_nazione,p_nome,p_cognome){
    
           $http({
            method: "post",
            url: '/common/module/checkcf/'+cf, 
            data:    { 
                    comune:p_comune, 
                    data: p_data, 
                    sesso: p_sesso, 
                    nazione: p_nazione, 
                    nome: p_nome, 
                    cognome: p_cognome
                  }
                }).success(function(data){ 
                    $scope.message = data.error;
                    $scope.errors = !data.result;
                    $scope.forms[index].p_cf_validated = data.result;
                    return data.result;
                });
         
    };

$scope.busy = true;

    $scope.loadRequiredAttachments = function(form_id){
    $http.get('/common/cases/get_required_attach/'+form_id).
        success(function(data, status, headers, config) {
            // this callback will be called asynchronously
            // when the response is available
            $scope.arr_required_attachments[form_id] = data.result;
            $scope.busy = false;
    }); 
  }

    
  $scope.loadAttachments = function(thread,form_id){
      $scope.busy = true;
      $http.get('/common/cases/get_attachment/'+thread+'/'+form_id).
                      success(function(data, status, headers, config) {
                        $scope.attachments = {};
                          if(typeof data.attachments != 'undefined' && data.attachments.length > 0){
                            $scope.attachments = data.attachments;
                          }
                          $scope.collections = {};
                          if(typeof data.collection != 'undefined' && data.collection.length > 0){
                            $scope.collections = data.collection;
                          }
                          $scope.busy = false;
                  }); 
     
    };

    $scope.loadfiletypes = function(form_id){
      $scope.busy = true;
      $http.get('/common/cases/attach_types/'+form_id).
                      success(function(data, status, headers, config) {
                          if(data.length > 0){
                            $scope.filetypes = data;
                          } else {
                            $scope.filetypes = {};
                          }
                          $scope.busy = false;
                  }); 
     
    };
    
  $scope.loadfiles = function(thread){
    $scope.busy = true;
    $http.get('/common/attachments/list_files/'+thread).
                    success(function(data, status, headers, config) {
                        // this callback will be called asynchronously
                        // when the response is available
                    		if(data.result) {
                              if(data.result.length > 0){
                                $scope.listfiles = data.result;
                                $scope.arr_attachments = data.result;
                              } else {
                                $scope.listfiles = {};
                                $scope.arr_attachments = undefined;
                              }
                    		}
                              $scope.busy = false;
                }); 
   
  };

  $scope.loadrequiredfiles = function(form_id){

     $http.get('/common/cases/get_required_attach/'+form_id).
                    success(function(data, status, headers, config) {
                        // this callback will be called asynchronously
                        // when the response is available
                        $scope.required_attachments = [];
                        $scope.required_attachments[form_id] = data.result;
                        $scope.busy = false;
                }); 
  };


  //listen for the file selected event
  $scope.$on("fileSelected", function (event, args) {
      $scope.$apply(function () {            
          //add the file object to the scope's files collection
          $scope.files.push(args.file);
      });
  });

$scope.setFiles = function(element) {
      $scope.$apply(function($scope) {

        // Turn the FileList object into an Array
          $scope.files = []
          for (var i = 0; i < element.files.length; i++) {
            $scope.files.push(element.files[i])
          }
        $scope.progressVisible = false
        });
    };

  $scope.upload = function() {
        var uploadUrl = '/common/attachments/upload/'+$scope.thread.id;
  
        var fd = new FormData();
        for (var i in $scope.files) {
            fd.append("userfile", $scope.files[i])
        }
        fd.append("attach_type",$scope.filedata.attach_type)
        if($scope.filedata.description){
          fd.append("description",$scope.filedata.description)
        } else {
          fd.append("description","")
        }

        var xhr = new XMLHttpRequest()
        xhr.upload.addEventListener("progress", $scope.uploadProgress, false)
        xhr.addEventListener("load", $scope.uploadComplete, false)
        xhr.addEventListener("error", $scope.uploadFailed, false)
        xhr.addEventListener("abort", $scope.uploadCanceled, false)
        xhr.open("POST", uploadUrl)
        $scope.progressVisible = true
        xhr.send(fd)
    }

    $scope.deleteFile = function(id,thread_id){
      $http.get('/common/attachments/delete_file/'+id+'/'+thread_id).
                    success(function(data, status, headers, config) {
                        $scope.loadfiles(thread_id);
                }); 
    };

    $scope.uploadProgress = function(evt) {
        $scope.$apply(function(){
            $scope.filedata.busy = true
            if (evt.lengthComputable) {
                $scope.progress = Math.round(evt.loaded * 100 / evt.total)
            } else {
                $scope.progress = 'unable to compute'
            }
        })
    }

    $scope.uploadComplete = function(evt) {
        /* This event is raised when the server send back a response */
        //alert(evt.target.responseText)
        var response = JSON.parse(evt.target.responseText);
        
        if(!response.result){
          $scope.filedata.errors = true;
          $scope.filedata.error = response.error;
          $scope.filedata.busy = false;
        } else {
          $scope.filedata.errors = false;
          $scope.filedata.busy = false;
          $scope.loadfiles(response.id_thread);
        }
        
         $scope.$digest();
    }

    $scope.uploadFailed = function(evt) {
        var response = JSON.parse(evt.target.responseText);
        if(!response.result){
          $scope.filedata.error = response.error;
          $scope.filedata.errors = true;
        } else {
          $scope.filedata.errors = false;
        }

        $scope.filedata.busy = false;
        $scope.$digest();
    }

    $scope.uploadCanceled = function(evt) {
        $scope.$apply(function(){
           $scope.progressVisible = false
        })
        $scope.filedata.errors = "The upload has been canceled by the user or the browser dropped the connection.";
        $scope.filedata.busy = false;
        $scope.$digest();
    }

    $scope.forms.calendar = {
        opened: {},
        dateFormat: 'dd-MM-yyyy',
        dateOptions: {
            formatYear: 'yy',
            startingDay: 1
        },
        open: function($event, which) {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.forms.calendar.opened[which] = true;
        } 
    };

   
   $scope.$watch('mode', function () {


        if($scope.mode == 'edit'){
            $scope.request.mode = 'edit';
            $http.get('/common/cases/get_thread/'+$scope.thread).
              success(function(data, status, headers, config) {
                // this callback will be called asynchronously
                // when the response is available
            	
                $scope.thread = data;
                $scope.request = data;
                $scope.getSla();

                $scope.loadfiletypes(data.form_id);
                $scope.loadrequiredfiles(data.form_id);
                $scope.loadfiles(data.id);

                $http.get('/common/cases/get_customer/'+$scope.thread.customer).
                      success(function(data, status, headers, config) {
                        // this callback will be called asynchronously
                        // when the response is available
                        $scope.selected.customer = data;
                        
                        if($scope.thread.be!=null){
                          $http.get('/common/cases/get_contract/'+$scope.thread.be).
                                success(function(data, status, headers, config) {
                                  // this callback will be called asynchronously
                                  // when the response is available 

                                  $scope.selected.contract = data;
                          });
                        }
                  });
                $http.get('/common/cases/get_process/'+$scope.thread.process+'/'+$scope.thread.type).
                    success(function(data, status, headers, config) {
                        // this callback will be called asynchronously
                        // when the response is available
                        $scope.selected.template = data;
                        $scope.loadtemplate = true;
                        $rootScope.processdata = data;
                        $scope.processdata = data;
                });
                
                $http.get('/common/cases/get_activities/'+$scope.thread.id).
                	success(function(data, status, headers, config) {
                    // this callback will be called asynchronously
                    // when the response is available
                    $scope.activities = data;
                    
                    $scope.activities.sort(function (actA, actB) {
                        if (actA.id < actB.id) {
                            return -1;
                        }
                        if (actA.id > actB.id) {
                            return 1;
                        }
                        return 0;
                    });

                    var i = 0;
                    angular.forEach(data, function(value, key) {
                       //console.log(JSON.parse(value.payload));
                        try {
                          $scope.forms[i] = JSON.parse(value.payload);
                        } catch (e) {
                          $scope.forms[i] = value.payload;
                        }
                       
                        //$scope.bo_form_data[i] = JSON.parse(value.payload);
                        $scope.variables[i] = {'status':value.status};
                      i++;
                    });
           
                    $scope.loadactivities = true;
                }); 
                
                
                $http.get('/common/cases/get_rel_activities/'+$scope.thread.id).
                    success(function(data, status, headers, config) {
                        // this callback will be called asynchronously
                        // when the response is available
                        $scope.rel_activities = data;
                        var i = 0;
                        angular.forEach(data, function(value, key) {
                           //console.log(JSON.parse(value.payload));
                            try {
                              $scope.forms[i] = JSON.parse(value.payload);
                            } catch (e) {
                              $scope.forms[i] = value.payload;
                            }
                           
                            //$scope.bo_form_data[i] = JSON.parse(value.payload);
                            $scope.variables[i] = {'status':value.status};
                          i++;
                        });
                        console.log($scope.variables);
                        $scope.loadactivities = true;
                }); 
                // Thread Integrations
                $http.get('/common/cases/get_integrations/'+$scope.thread.id).
                success(function(data, status, headers, config) {
                    // this callback will be called asynchronously
                    // when the response is available
                    $scope.integrations = data;
                    var i = 0;
                    angular.forEach(data, function(value, key) {
                       //console.log(JSON.parse(value.payload));
                        try {
                          $scope.forms[i] = JSON.parse(value.payload);
                        } catch (e) {
                          $scope.forms[i] = value.payload;
                        }
                       
                        //$scope.bo_form_data[i] = JSON.parse(value.payload);
                       // $scope.variables[i] = {'status':value.status};
                      i++;
                    });
                   // console.log('*****************');
                  //  console.log($scope.integrations);
                    $scope.loadactivities = true;
                }); 
	             // Thread process list
	            $http.get('/common/cases/get_process_list/'+$scope.thread.id).
	                success(function(data, status, headers, config) {
	                    // this callback will be called asynchronously
	                    // when the response is available
	                	$scope.process_activity_list = [];
	                    $scope.process_activity_list = data;
	                    $scope.loadactivities = true;
	            }); 
                $http.get('/common/cases/get_by_customer/'+$scope.thread.customer+'/'+$scope.thread.id).
                    success(function(data, status, headers, config) {
                        // this callback will be called asynchronously
                        // when the response is available
                        $scope.related_threads = [];
                        $scope.related_threads = data;
                });

                    $scope.loadcomments($scope.thread.id);
            });
          
            
        }

    },true);

    $scope.setCustomer = function(item) {;
        $scope.selected.customer = item;
        var request = $http({
          method: "post",
          url: "/json/customers/contracts",
          transformRequest: transformRequestAsFormPost,
          data: {
            user:item.id
          }
        });

        // Store the data-dump of the FORM scope.
        request.success(
          function( data ) {
            $scope.contracts = data;
          }
        );
    };

     $scope.updateThread = function(controller) {
        var request = $http({
          method: "post",
          url: controller+$scope.thread.id,
          transformRequest: transformRequestAsFormPost,
          data: $scope.request
        });

        // Store the data-dump of the FORM scope.
        request.success(
          function( data ) {
            if(data.result===true){
              location.href = location.origin + '/common/cases/edit/'+$scope.thread.id;
            }
          }
        );
    };

    $scope.resetCustomer = function() {
        $scope.customer = '';
        $scope.selected.customer = '';
        $scope.selected.customer_model = ''; 
    };

    $scope.saveForm = function(index,url,id,json) {
      window.onbeforeunload = null;

      if($scope.request.master_status == 'PENDING'){
         $ngBootbox.alert('Thread in status PENDING non è possibile apportare modifiche!');
         return false;
      }

       $http({
                      method: "get",
                      url: "/common/cases/draft/"+$scope.thread.id
                    });

      angular.forEach($scope.activities[index].statuses, function(value, key) {
          if(value.key == $scope.variables[index].status){
            if(value.final == 't'){
              if($scope.errors===true) return false;
            }
          }
      });

    //if($scope.errors === true) return false;


        if(typeof json == 'undefined'){
          json= false;
        }
      if(json== true){
          var request = $http({
                   method: "post",
                   url: "/common/cases/save_activity_process/"+id,
                   data: angular.toJson($scope.forms[index]),
                   transformRequest: false
                 });
      }else{
    	  var request = $http({
               method: "post",
               url: "/common/cases/save_activity_process/"+id,
               transformRequest: transformRequestAsFormPost,
               data: $scope.forms[index]
             });
      }
    
    	
       request.success(
          function( data ) {
            if($scope.forms[index].errors) delete $scope.forms[index].errors;
            $scope.forms[index].status = $scope.variables[index].status;
            $scope.forms[index].activity = id;
            $scope.forms[index].thread = $scope.thread.id;
            if(data.result===true){
              if(json== true){
                               var form_request = $http({
                                       method: "post",
                                       url: url,
                                       data: angular.toJson($scope.forms[index])
                                     });
                               
                             }else{
                               var form_request = $http({
                                       method: "post",
                                       url: url,
                                       transformRequest: transformRequestAsFormPost,
                                       data: $scope.forms[index]
                                     });
                             }

                form_request.success(function(data, status, headers, config) {
                    // this callback will be called asynchronously
                    // when the response is available
                    if(data.result === true){
                      location.reload();
                    } else {
                      $scope.forms[index].errors = data.error;
                    }
                }).
                error(function(data, status, headers, config) {
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });
            }
          }
        );
    };

    
    $scope.resetForm = function(index) {
       if($scope.request.draft == 't'){
        window.onbeforeunload = null;
        $scope.request.draft = 'f';
        $http({
            method: "get",
            url: '/common/cases/delete_draft/'+$scope.thread.id
            }).success(function(data){
                  window.history.back();
            });
       }
        $scope.forms[index] = {};
    };

    $scope.createThread = function() {
        $scope.loadtemplate = true;
        var request = $http({
          method: "post",
          url: "/common/cases/create_thread",
          transformRequest: transformRequestAsFormPost,
          data: {
            customer: $scope.selected.customer.id,
            be: $scope.selected.contract,
            mp: $scope.process,
            type: $scope.selected.template.key,
            sla: $scope.selected.template.sla,
            weight: $scope.selected.template.weight
          }
        });

        // Store the data-dump of the FORM scope.
        request.success(
          function( data ) {

            $scope.thread = data;
            $scope.loadtemplate = true;
            $scope.remaining = data.remaining;
            $scope.deadline = data.deadline;
            $scope.sla = data.sla; 
            $scope.request.thread = data.id;

            $scope.loadfiletypes(data.form_id);
            $scope.loadrequiredfiles(data.form_id);

   
          }
        );
    }

    $scope.getSla = function() {
        $scope.loadtemplate = true;
        var request = $http({
          method: "GET",
          url: "/common/cases/get_sla/"+$scope.thread.id
        });

        // Store the data-dump of the FORM scope.
        request.success(
          function( data ) {
            $scope.remaining = data.remaining;
            $scope.deadline = data.deadline;
            $scope.sla = data.sla; 
          }
        );
    }


    $scope.$watch('process', function () {
            $http.get('/json/forms/types/'+$scope.process).
              success(function(data, status, headers, config) {
                // this callback will be called asynchronously
                // when the response is available
                $scope.form_types = data;
              }).
              error(function(data, status, headers, config) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
            });
    },true);

    $scope.getCustomers = function(val) {
    return $http.get('/json/customers', {
      params: {
        q: val
      }
    }).then(function(response){
      return response.data.map(function(item){
        return item;
      });
    });
  };

   $scope.get_status_filter = function(statuses,status){
    var check = false;
    angular.forEach(statuses, function(item,key) {
      if(item.key == status && item.final == 't'){
        check = true;
      }
    });
    
    return check;
  }
  

})


.controller('Calendar', function($scope,$rootScope,$http,$timeout,$filter,uiCalendarConfig) {
	
	var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    
	$scope.caldata = [];
	$scope.busy_calendar = true;
	
	$http.get('/common/calendar/get_memos').success(function(data) {
	  	 $scope.busy_calendar = false;
	     var memos = data.result;
	     var rawArray = [];
	     for(var i=0; i< memos.length; i++)
	     {
	    	 var startDateArray = null;
	    	 var startTimeArray = null;
	    	 var endDateArray = null;
	    	 var endTimeArray = null;
	    	 var allDay = null;
	    	 var className = 'b-l b-2x b-info';
	    	 var startDate = null;
	    	 var endDate = null;
	    	 if(memos[i].start_day != null && memos[i].start_day != '') {
	    		 startDateArray = memos[i].start_day.split("-");
	    		 startTimeArray = memos[i].start_time.split(":");
	    		 var month = parseInt(startDateArray[1], 10);
	    		 var month_index = month-1;
	    		 startDate = new Date(startDateArray[0], month_index, startDateArray[2], startTimeArray[0], startTimeArray[1]);
	    	 }
	    	 if(memos[i].end_day != null && memos[i].end_day != '') {
		    	 endDateArray = memos[i].end_day.split("-");
		    	 endTimeArray = memos[i].end_time.split(":");
		    	 var end_month = parseInt(endDateArray[1], 10);
	    		 var end_month_index = end_month-1;
		    	 endDate = new Date(endDateArray[0], end_month_index, endDateArray[2], endTimeArray[0], endTimeArray[1]);
	    	 }
	    	 if(memos[i].all_day != null && memos[i].all_day != '') {
	    		 if(memos[i].all_day == 'f') {
	    			 allDay = false;
	    		 } else if(memos[i].all_day == 't') {
	    			 allDay = true;
	    		 }
	    	 }
	    	 rawArray.push({id: memos[i].id, thread_id: memos[i].thread_id, activity_id: memos[i].activity_id, title: memos[i].title, discription: memos[i].description, start:startDate, end:endDate, allDay:allDay, className:className});
	     }
	     $scope.caldata[0] = rawArray;
	
	});
	                  
	/* config object */
    $scope.uiConfig = {
      calendar:{
        height: 450,
        editable: false,
        header:{
        	left: 'prev',
            center: 'title',
            right: 'next'
        },
        defaultView: 'agendaWeek',
      },
      viewRender: $scope.renderView,
      eventRender: $scope.eventRender
    };

    
    $scope.uiConfig.calendar.dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    $scope.uiConfig.calendar.dayNamesShort = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
    $scope.uiConfig.calendar.monthNames = ["January","February","March","April","May","June","July", "August", "September", "October", "November", "December" ];
    $scope.uiConfig.calendar.monthNamesShort = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    
    
  //with this you can handle the events that generated by each page render process
    $scope.renderView = function(view){    
        var date = new Date(view.calendar.getDate());
        $scope.currentDate = date.toDateString();
       
        /*$scope.$apply(function(){
          $scope.alertMessage = ('Page render with date '+ $scope.currentDate);
        });*/
    };


	/* Change View */
	$scope.changeView = function(view,calendar) {
		uiCalendarConfig.calendars[calendar].fullCalendar('changeView',view);
	};
	/* Change View */
	$scope.renderCalender = function(calendar) {
	    if(uiCalendarConfig.calendars[calendar]){
	    	uiCalendarConfig.calendars[calendar].fullCalendar('render');
	    }
	};
	/* Render Tooltip */
	    $scope.eventRender = function( event, element, view ) { 
    
      $timeout(function(){
        $(element).attr('tooltip', event.discription);
        $compile(element)($scope);
      });
    };
	//$scope.changeView('agendaDay', 'myMemoCalendar');
})



/* memos calendar list end */

app.filter('dateToISO', function() {
  return function(badTime) {
	  
    var goodTime = badTime.replace(/(.+) (.+)/, "$1 T $2");
    
    return goodTime;
  };
});

app.filter('dateToFormat', function() {
	  return function(badTime) {
		  
	    var goodTime = badTime.replace(/(.+) (.+)/, "$1");
	    
	    return goodTime;
	  };
	});
        app.factory(
            "transformRequestAsFormPost",
            function() {

                // I prepare the request data for the form post.
                function transformRequest( data, getHeaders ) {

                    var headers = getHeaders();

                    headers[ "Content-Type" ] = "application/x-www-form-urlencoded; charset=utf-8";

                    return( serializeData( data ) );

                }
                // Return the factory value.
                return( transformRequest );
                // ---
                // PRVIATE METHODS.
                // ---
                // I serialize the given Object into a key-value pair string. This
                // method expects an object and will default to the toString() method.
                // --
                // NOTE: This is an atered version of the jQuery.param() method which
                // will serialize a data collection for Form posting.
                // --
                // https://github.com/jquery/jquery/blob/master/src/serialize.js#L45
                function serializeData( data ) {

                    // If this is not an object, defer to native stringification.
                    if ( ! angular.isObject( data ) ) {

                        return( ( data == null ) ? "" : data.toString() );

                    }

                    var buffer = [];

                    // Serialize each key in the object.
                    for ( var name in data ) {

                        if ( ! data.hasOwnProperty( name ) ) {
                            continue;
                        }

                        var value = data[ name ];

                        buffer.push(
                            encodeURIComponent( name ) +
                            "=" +
                            encodeURIComponent( ( value == null ) ? "" : value )
                        );

                    }

                    // Serialize the buffer and clean it up for transportation.
                    var source = buffer
                        .join( "&" )
                        .replace( /%20/g, "+" )
                    ;

                    return( source );

                }
            }
        );

app.filter('inArray', function() {
    return function(array, value) {
        return array.indexOf(value) !== -1;
    };
});

/*app.config(function ($httpProvider) {
    $httpProvider.defaults.transformRequest = function(data){
        if (data === undefined) {
            return data;
        }
        return $.param(data);
    }
});*/


app.directive('myDate',function(dateFilter,$parse){
  return{
    restrict:'EAC',
    require:'?ngModel',
    link:function(scope,element,attrs,ngModel,ctrl){
      ngModel.$parsers.push(function(viewValue){
       return dateFilter(viewValue,'yyyy-MM-dd');
       //return '01-01-2015';
      });
    }
  }
});

app.filter('unsafe', function($sce) {
    return function(value) {
        if (!value) { return ''; }
        return $sce.trustAsHtml(value);
    };
});


app.directive('typeaheadcustom', function() {
      var directive = {
        restrict: 'A',
        link: function(scope, element, attrs, ctrl) {
          var myData = {};
          var match = false;
          myData.valueCache = [];
            var bloodHound = new Bloodhound({
              datumTokenizer: Bloodhound.tokenizers.obj.whitespace('comune'),
              queryTokenizer: Bloodhound.tokenizers.whitespace,
              //prefetch: $(this).data('source'),
              limit: 12,
              remote: {
                url: $(element).data('source')+'?q=%QUERY',
                wildcard: '%QUERY',
                cache: false,
                limit: 30,
                ajax: {
                          complete: function(response){
                              response.responseJSON.forEach(function (item) {
                                  myData.valueCache.push(item.comune);
                              });
                          }
                      }
              }
          });
           bloodHound.initialize();
              $(element).typeahead(null,{
                  name: $(element).name,
                  display: 'comune',
                source: bloodHound.ttAdapter()      
              }).bind('typeahead:closed', function () {
              if (myData.valueCache.indexOf($(element).val()) === -1) {
                      match = false;
                    } else {
                      match = true;
                    }
                    if(match===false){
                      $(element).val('');
                    }
          });
        }
      };
      return directive;
    });

app.directive('typeaheadfornitori', function() {
      var directive = {
        restrict: 'A',
        link: function(scope, element, attrs, ctrl) {
          var myData = {};
          var match = false;
          myData.valueCache = [];
            var bloodHound = new Bloodhound({
              datumTokenizer: Bloodhound.tokenizers.obj.whitespace('fornitore'),
              queryTokenizer: Bloodhound.tokenizers.whitespace,
              //prefetch: $(this).data('source'),
              limit: 12,
              remote: {
                url: $(element).data('source')+'?q=%QUERY',
                wildcard: '%QUERY',
                cache: false,
                limit: 30,
                ajax: {
                          complete: function(response){
                              response.responseJSON.forEach(function (item) {
                                  myData.valueCache.push(item.fornitore);
                              });
                          }
                      }
              }
          });
           bloodHound.initialize();
              $(element).typeahead(null,{
                  name: $(element).name,
                  display: 'fornitore',
                source: bloodHound.ttAdapter()      
              }).bind('typeahead:closed', function () {
              if (myData.valueCache.indexOf($(element).val()) === -1) {
                      match = false;
                    } else {
                      match = true;
                    }
                    if(match===false){
                      $(element).val('');
                    }
          });
        }
      };
      return directive;
    });

app.directive('newCalendar', function($compile, $timeout) {
  return function(scope, element, attrs) {
    $timeout(function() {
      
      $(element).datepicker('remove');
      $(element).datepicker({ autoclose: true, todayHighlight: true, language: 'it' });
      $(element).datepicker('update');
    });
  };
});

app.directive('loadCalendar', function() {
  return function(scope, element, attrs) {
    if (scope.$last){
      // iteration is complete, do whatever post-processing
      // is necessary
      element.parent().find(".input-group.date").datepicker({ autoclose: true, todayHighlight: true, language: 'it' });
    }
  };
});

app.directive('memoCalendar', ['$http', '$compile', '$timeout', 'uiCalendarConfig', memoCalendar]);
function memoCalendar($http, $compile, $timeout, uiCalendarConfig) {
    return {
        restrict: 'A',
        link: function link($scope, elem, attrs) {
        //  console.log(uiCalendarConfig);
            //if url is not empty
        	//console.log(attrs);
            if (attrs.actid) {
            	$scope.caldata =[];
            	$http.get('/json/memos/get/'+attrs.actid).success(function(data) {
	       		  	 $scope.busy_calendar = false;
	       		     var memos = data.result;
	       		     var rawArray = [];
	       		     for(var i=0; i< memos.length; i++)
	       		     {
	       		    	 var startDateArray = null;
	       		    	 var startTimeArray = null;
	       		    	 var endDateArray = null;
	       		    	 var endTimeArray = null;
	       		    	 var allDay = null;
	       		    	 var className = 'b-l b-2x b-info';
	       		    	 var startDate = null;
	       		    	 var endDate = null;
	       		    	 if(memos[i].start_day != null && memos[i].start_day != '') {
	       		    		 startDateArray = memos[i].start_day.split("/");
	       		    		 startTimeArray = memos[i].start_time.split(":");
	       		    		
	       		    		 startDate = new Date(startDateArray[2], startDateArray[1]-1, startDateArray[0], startTimeArray[0], startTimeArray[1]);
	       		    	 }
	       		    	 if(memos[i].end_day != null && memos[i].end_day != '') {
	       			    	 endDateArray = memos[i].end_day.split("/");
	       			    	 endTimeArray = memos[i].end_time.split(":");
	       			    
	       			    	 endDate = new Date(endDateArray[2], endDateArray[1]-1, endDateArray[0], endTimeArray[0], endTimeArray[1]);
	       		    	 }
	       		    	 if(memos[i].all_day != null && memos[i].all_day != '') {
	       		    		 if(memos[i].all_day == 'f') {
	       		    			 allDay = false;
	       		    		 } else if(memos[i].all_day == 't') {
	       		    			 allDay = true;
	       		    		 }
	       		    	 }
	       		    	 rawArray.push({id: memos[i].id, thread_id: memos[i].thread_id, activity_id: memos[i].activity_id, title: memos[i].title, discription: memos[i].description, start:startDate, end:endDate, allDay:allDay, className:className});
	       		     }
          
	       		    
               $scope.caldata[0] = rawArray;
                $('#calendario').fullCalendar('refetchEvents');
            	});
            }
        }
    };
}

app.directive('showErrors', function() {
    return {
      restrict: 'A',
      require:  '^form',
      link: function (scope, el, attrs, formCtrl) {
        // find the text box element, which has the 'name' attribute
        var inputEl   = el[0].querySelector("[name]");
        // convert the native text box element to an angular element
        var inputNgEl = angular.element(inputEl);
        // get the name on the text box so we know the property to check
        // on the form controller
        var inputName = inputNgEl.attr('name');

        // only apply the has-error class after the user leaves the text box
        inputNgEl.bind('blur', function() {
          el.toggleClass('has-error', formCtrl[inputName].$invalid);
        })
      }
    }
  });

app.directive('greaterThan', function () {
    return {
      require: 'ngModel',
      link: function(scope, element, attrs, ctrl) {
    	  scope.$watch(attrs.ngModel, function (v) {
          if(v==null) return;
    		  var pot_disponibile = parseFloat(attrs.greaterThan.replace(',','.'),2);
              var pot_installabile = parseFloat(v.replace(',','.'),2);
    		  
    		  if(pot_installabile > pot_disponibile){
    			  scope.installabile_error = true;
    			  scope.forms[attrs.formIndex].esito_sopralluogo = 7;
    		  }else{
    			  scope.installabile_error = false;
    		  }
          });
       
    }
    };
});

app.controller('Tee', function($scope,$rootScope,$http,$timeout) {
  $scope.busy = true;
  $scope.request = {};
  $scope.activities = [];

  $http.get('/common/cases/get_tee/').
      success(function(data, status, headers, config) {
          $scope.thread = data;
          $scope.busy = false;
  });

  $http.get('/common/cases/get_tee_activities').
                success(function(data, status, headers, config) {
                    $scope.activities = data;
                    $scope.busy = false;
                  
                   
  });




  $scope.createThread = function(){

    $scope.busy = true;
    var request = $http({
            method: "post",
            url: "/common/cases/create_tee",
            data: angular.toJson($scope.request),
            transformRequest: false
        });

        // Store the data-dump of the FORM scope.
        request.success(
          function( data ) {

            $http.get('/common/cases/get_tee/').
                success(function(data, status, headers, config) {
                    $scope.thread = data;
                    $scope.busy = false;
            });

            $http.get('/common/cases/get_tee_activities/').
                success(function(data, status, headers, config) {
                    $scope.activities = [];
                    var i = 0;
                     angular.forEach(data, function(value, key) {
                           //console.log(JSON.parse(value.payload));
                           $scope.activities[i] = value;
                           
                            try {
                              $scope.activities[i].payload = JSON.parse(value.payload);
                              //$scope.activities[i].id = value.id;
                            } catch (e) {
                              $scope.activities[i].payload = value.payload;
                              //$scope.activities[i].id = value.id;
                            }
                           
                          i++;
                        });
                    $scope.busy = false;
            });

            $scope.request.crea = false;

   
          }
        );
  }

});

app.controller('Import', ['$scope', '$http', 'Upload', function ($scope, $http, Upload) {
    $scope.busy = false;
    $scope.percentage = 0;
    $scope.preview = false;
    $scope.ip = '';
    $scope.imported = false;


    $scope.continue = function() {
      $http.post("/wizards/import/in", $scope.preview).success(function(data, status) {
            $scope.imported = true;
            $scope.imported_records = data.count;
      });
    };

    // upload later on form submit or something similar
    $scope.submit = function() {
      if ($scope.form.file.$valid && $scope.file) {
        $scope.upload($scope.file);
      }
    };

    // upload on file select or drop
    $scope.upload = function (file) {
        $scope.busy = true;
        Upload.upload({
            url: '/fileimport/fileimport.php',
            data: {upload: file, filetype: 'xls',ip: $scope.ip}
        }).then(function (resp) {
  
            //console.log('Success ' + resp.config.data.file.name + 'uploaded. Response: ' + resp.data);
            $scope.preview = resp.data.data.imported_data;
            $scope.busy = false;
            $scope.errors = resp.data.error;
        }, function (resp) {
            console.log('Error status: ' + resp.status);
            console.log(resp);
            $scope.busy = false;
            

        }, function (evt) {
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            //console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
            $scope.percentage = progressPercentage;
        });
    };

    $scope.submit_inorder_teleselling = function() {
        if ($scope.form.file.$valid && $scope.file) {
            $scope.upload_inorder_teleselling($scope.file);
        }
    };

    // upload on file select or drop
    $scope.upload_inorder_teleselling = function (file) {
        $scope.busy = true;
        Upload.upload({
            url: '/fileimport/fileimport_inorder_sereno.php',
            data: {upload: file, filetype: 'xls',ip: $scope.ip}
        }).then(function (resp) {
            
            $scope.data_elab = resp.data.data;
            $scope.preview = resp.data.data.imported_data;
            $scope.busy = false;
            $scope.errors = resp.data.error;
        }, function (resp) {
            console.log('Error status: ' + resp.status);
            console.log(resp);
            $scope.busy = false;


        }, function (evt) {
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            //console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
            $scope.percentage = progressPercentage;
        });
    };
}]);

app.directive('stringToNumber', function() {
  return {
    require: 'ngModel',
    link: function(scope, element, attrs, ngModel) {
      ngModel.$parsers.push(function(value) {
        return '' + value;
      });
      ngModel.$formatters.push(function(value) {
        return parseFloat(value, 10);
      });
    }
  };
});

app.filter('unique', function() {
    return function(input, key) {
        var unique = {};
        var uniqueList = [];
        for(var i = 0; i < input.length; i++){
            if(typeof unique[input[i][key]] == "undefined"){
                unique[input[i][key]] = "";
                uniqueList.push(input[i]);
            }
        }
        return uniqueList;
    };
});

app.filter('toArray', function () {
'use strict';

return function (obj) {
if (!(obj instanceof Object)) {
return obj;
}

return Object.keys(obj).map(function (key) {
return Object.defineProperty(obj[key], '$key', {__proto__: null, value: key});
});
}
});

app.directive('convertToNumber', function() {
  return {
    require: 'ngModel',
    link: function(scope, element, attrs, ngModel) {
      ngModel.$parsers.push(function(val) {
        return parseInt(val, 10);
      });
      ngModel.$formatters.push(function(val) {
        return '' + val;
      });
    }
  };
});

//Directive to check if the token used by any other contract
app.directive('verifyToken', function ($http) {
    return {
        require: 'ngModel',
        link: function (scope, elem, attrs, ctrl) {
            function myValidation(value) {
            	var status=null;
            	if(value != ''){
	            	var result = $http({
	                    method: "post",
	                    url: "/common/module/verify_token",
	                    data: {token_lead: value}
	                  }).then(function(data){                        
	                      status = data.data.result;                      
	                      if (status==1) {
	                    	  ctrl.$setValidity('validtoken', true);
	                      } else {
	                    	  ctrl.$setValidity('validtoken', false);
	                      }
	                  });
            	}else{
            		ctrl.$setValidity('validtoken', true);
            	}
               return value;
            }
            ctrl.$parsers.push(myValidation);
        }
      }
});
