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

class Service extends CI_Controller
{
    public function __construct()
    {
    	parent::__construct();
    }

 	public function allextention() {
 		//API URL
 		$url = $this->config->item('api_url').'api/service/allextention';
 
 		//HEADER
 		$header = array(
 				'Version:'. '1.0.1'
 		);
 		//CREATING THE cURL REQUEST
 		$ch = curl_init($url);
 		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
 		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
 		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
 		curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 	
 		$result = curl_exec($ch);
 		echo $result;
 		//CLOSE cURL 
 		curl_close($ch);
    }
    
    public function download_file($key = NULL, $token = NULL) { 
    		if($_POST) {
		    	$token = ($_POST['token']) ? $_POST['token'] : NULL;
		    	$_POST = json_decode($_POST['data']);
		    	$key = $_POST->key;
		
	    	}	
	    	// API Request
	    	$url = $this->config->item('api_url').'api/service/download_file';
	    	// POST data
	    	$header = array(
	    			'X-API-KEY:'. $key,
	    			'Version:'.'1.0.1',
	    			'Authorization:'.$token
	    	);
	    	//CREATING THE cURL REQUEST
	    	$ch = curl_init($url);
	    	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	    	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	    	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	    	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	    	$result = curl_exec($ch);
	    	$response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    	header("HTTP/1.0 $response");
	    	echo $result; 
	    	//CLOSE cURL
	    	curl_close($ch);
    	}
    
}
?>