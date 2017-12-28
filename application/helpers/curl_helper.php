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

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

if (! function_exists ( 'curl_request' )) {
	function curl_request($uri = '', $post = NULL, $file_data = NULL, $multipart = FALSE) {
		$httpua = (isset ( $_SERVER ['HTTP_USER_AGENT'] )) ? $_SERVER ['HTTP_USER_AGENT'] : 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)';
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $uri );
		curl_setopt ( $ch, CURLOPT_HEADER, FALSE );
		curl_setopt ( $ch, CURLOPT_VERBOSE, FALSE );
		curl_setopt ( $ch, CURLOPT_FAILONERROR, TRUE );
		curl_setopt ( $ch, CURLOPT_USERAGENT, $httpua );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		if (strpos ( $uri, 'https' ) !== false) {
			curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		}
		if ($post != NULL) {
			curl_setopt ( $ch, CURLOPT_POST, true );
			// MULTIPART ONLY IF NEED UPLOAD
			if ($multipart == true) {
				if (isset ( $file_data ) && is_array ( $file_data ) && $file_data ["file"] ["name"] != '') { // replace the key "file" with appropriate input type="file" name
					$post ['upload'] = "@" . $file_data ["file"] ["tmp_name"] . ";type=" . $file_data ["file"] ["type"] . ";filename=" . $file_data ["file"] ["name"];
				}
				foreach ( $post as $key => $value ) {
					if (is_array ( $post [$key] )) {
						foreach ( $post [$key] as $k => $v ) {
							$post_data [$k] = $v;
						}
					} else {
						$post_data [$key] = $value;
					}
				}
				curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
			} else {
				curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ( $post ) );
			}
		}
		// Executing
		$result = curl_exec ( $ch ); // print_r($result);exit;
		if (! $result) {
			$error = curl_error ( $ch );
			log_message ( 'error', 'Service ' . $uri . ' ' . $error );
		} else {
			$res = json_decode ( $result );
			if (isset ( $res->status ) && $res->status == "FALSE") {
				// log_message('debug', $res->error);
			}
			log_message ( 'debug', 'Service ' . $uri . ' executed' );
		}
		// Closing the channel
		curl_close ( $ch );
		
		return $result;
	}
}
