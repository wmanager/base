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






