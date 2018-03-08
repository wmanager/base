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

if (! function_exists ( 'clean_array_data' )) {
	function clean_array_data($data) {
		foreach ( $data as $k => $v ) {
			if ($v == '') {
				unset ( $data [$k] );
			}
		}
		return $data;
	}
	function defect_exist($serial, $list) {
		$CI = & get_instance ();
		$query = $CI->db->join ( 'activities', 'activities.id = activities_detail.id_activity' )->where ( 'activities.type', 'DEFECT' )->where ( 'activities.id_list', $list )->where ( 'activities_detail.serial', $serial )->get ( 'activities_detail' );
		return $query->num_rows ();
	}
	function assets_status($id) {
		$CI = & get_instance ();
		$query = $CI->db->where ( 'activities_detail.id_activity', $id )->get ( 'activities_detail' );
		$total = $query->num_rows ();
		$query = $CI->db->where ( 'activities_detail.id_activity', $id )->where ( 'exitcode >= 0' )->get ( 'activities_detail' );
		$ok = $query->num_rows ();
		
		if ($total == $ok) {
			return true;
		} else {
			return false;
		}
	}
	function formatBytes($bytes, $precision = 2) {
		if ($bytes == '')
			return '';
		
		$kilobyte = 1024;
		$megabyte = $kilobyte * 1024;
		$gigabyte = $megabyte * 1024;
		$terabyte = $gigabyte * 1024;
		
		if (($bytes >= 0) && ($bytes < $kilobyte)) {
			return $bytes . 'B';
		} elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
			return round ( $bytes / $kilobyte, $precision ) . 'KB';
		} elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
			return round ( $bytes / $megabyte, $precision ) . 'MB';
		} elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
			return round ( $bytes / $gigabyte, $precision ) . 'GB';
		} elseif ($bytes >= $terabyte) {
			return round ( $bytes / $terabyte, $precision ) . 'TB';
		} else {
			return $bytes . 'B';
		}
	}
	function datait2ts($data) {
		if (strpos ( $data, "-" ) > - 1) {
			$separator = "-";
		} else {
			$separator = '/';
		}
		
		$dt = explode ( $separator, $data );
		$y = $dt [0];
		$m = $dt [1];
		$d = $dt [2];
		return strtotime ( $y . '-' . $m . '-' . $d );
	}
	function get_warnings($id) {
		$CI = & get_instance ();
		$query = $CI->db->where ( 'id_activity', $id )->get ( 'assets_log' );
		return $query->result ();
	}
	function crypt_params($id) {
		if ($id == '')
			return 'null';
		$CI = & get_instance ();
		$CI->load->library ( 'encrypt' );
		return $CI->encrypt->encode ( $id );
	}
}
