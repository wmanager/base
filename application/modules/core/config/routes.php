<?php
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

/*
 * =======================================================================
 * CORE ENGINE ROUTES
 * =======================================================================
 */
$route ['admin/engine_debug/be_debug/(:any)'] = "core/engine_debug/be_debug/$1";
$route ['admin/engine_debug/debug/(:any)/(:any)'] = "core/engine_debug/debug/$1/$2";
$route ['admin/engine_debug/debug/(:any)/(:any)'] = "core/engine_debug/debug/$1/$2";
$route ['admin/extension'] = "core/extension/index";
$route ['admin/extension/add'] = "core/extension/add";


/*
 * =======================================================================
 * TARGETED WIZARDS ROUTES
 * =======================================================================
 */
$route ['module/targeted_wizard/index'] = "targeted_wizard/index";
$route ['module/targeted_wizard/wizard/index/(:any)'] = "targeted_wizard/wizard/index/$1";
$route ['module/targeted_wizard/wizard/export'] = "targeted_wizard/wizard/export";

/*
 * =======================================================================
 * CREDIT ROUTES
 * =======================================================================
 */
$route ['module/credit/dashboard'] = "credit/dashboard";
$route ['module/credit/dashboard/index/page/(:any)'] = "credit/dashboard";
$route ['module/credit/dashboard/index/page'] = "credit/dashboard";
$route ['module/credit/dashboard/credit_dashboard_export'] = "credit/dashboard/credit_dashboard_export";
$route ['module/credit/credit/export'] = "credit/credit/export";
$route ['module/credit/credit'] = "credit/credit/index";
$route ['module/credit/credit/index'] = "credit/credit/index";
$route ['module/credit/dashboard/related_tickets/(:any)'] = "credit/dashboard/related_tickets/$1";
$route ['module/credit/dashboard/related_docs/(:any)'] = "credit/dashboard/related_docs/$1";
$route ['module/credit/credit/report/(:any)'] = "credit/credit/report/$1";
$route ['module/credit/credit/report_credit'] = "credit/credit/report_credit";
$route ['module/credit/credit/payment_import'] = "credit/credit/payment_import";
$route ['module/credit/credit/import_administrative'] = "credit/credit/import_administrative";
$route ['module/credit/credit/import_install'] = "credit/credit/import_install";
$route ['module/credit/credit/import_rid_invoice'] = "credit/credit/import_rid_invoice";
$route ['module/credit/credit/import_crm_sole'] = "credit/credit/import_crm_sole";
$route ['module/credit/credit/import_credit_anagrafica_sole'] = "credit/credit/import_credit_anagrafica_sole";
$route ['module/credit/credit/fileslink/(:any)/(:any)'] = "credit/credit/fileslink/$1/$2";
$route ['module/credit/credit/download_credit_payments'] = "credit/credit/download_credit_payments";
$route ['module/credit/credit/report_insoluto'] = "credit/credit/report_insoluto";
$route ['module/credit/credit/report_rs1'] = "credit/credit/report_rs1";
$route ['module/credit/credit/rf1_credit_quality/(:any)'] = "credit/credit/rf1_credit_quality/$1";
$route ['module/credit/credit/rf2_vintage_analysis_disbursed/(:any)'] = "credit/credit/rf2_vintage_analysis_disbursed/$1";
$route ['module/credit/credit/rf3_report/(:any)'] = "credit/credit/rf3_report/$1";
$route ['module/credit/credit/process'] = "credit/credit/process/";
$route ['module/credit/credit/export'] = "/credit/credit/export";

/*
 * =======================================================================
 * INSTALLMENT ROUTES
 * =======================================================================
 */
$route ['module/credit/approval'] = "credit/approval";
$route ['module/credit/approval/get/page/(:any)'] = "credit/approval";
$route ['module/credit/approval/get/page'] = "credit/approval";
$route ['module/credit/pending'] = "credit/pending";
$route ['module/credit/pending/get/page/(:any)'] = "credit/pending";
$route ['module/credit/pending/get/page'] = "credit/pending";
$route ['module/credit/pending/update_status/(:num)/(:num)'] = "credit/pending/update_status/$1/$2";



/*
 * =======================================================================
 * Admin Panel Routes
 * =======================================================================
 */
$route['admin/setup_activities/(:num)'] = "wmanager/setup_activities/get/$1";
$route['admin/(:any)'] = "wmanager/$1";
$route['admin/(:any)/(:any)'] = "wmanager/$1/$2";
$route['admin/(:any)/(:any)/(:any)'] = "wmanager/$1/$2/$3";
$route['admin/(:any)/(:any)/(:any)/(:any)'] = "wmanager/$1/$2/$3/$4";
$route['admin/(:any)/(:any)/(:any)/(:any)/(:any)'] = "wmanager/$1/$2/$3/$4/$5";
$route['admin/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = "wmanager/$1/$2/$3/$4/$5/$6";
$route ['dashboard'] = "wmanager/dashboard";
$route ['dashboard/get_graph_data'] = "wmanager/dashboard/get_graph_data";
$route ['trouble_type'] = "wmanager/trouble_type";
//$route ['trouble_type'] = "wmanager/trouble_type";






