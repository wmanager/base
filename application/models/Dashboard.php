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
class Dashboard extends CI_Model {	
	/**
	 * get_open_troubles -fetches all open troubles types and count without threads associated to it.
	 *
	 * @param
	 *        	none
	 *        	
	 * @return array
	 *
	 * @author adharsh
	 */
	public function get_open_troubles() {
		// fetch troubles associated to threads
		$this->db->flush_cache ();
		$thread_fetch = $this->db->select ( "threads.trouble_id" )->where ( "threads.trouble_id IS NOT NULL" )->get ( "threads" );
		$thread_result = $thread_fetch->result ();
		$trouble_array = array ();
		if (count ( $thread_result ) > 0) {
			
			foreach ( $thread_result as $item ) {
				$trouble_array [] = $item->trouble_id;
			}
		} 
		
		// fetch trouble types and count
		$this->db->flush_cache ();
		$trouble_type_fetch = $this->db->select ( "setup_troubles_types.id,setup_troubles_types.title,count(troubles.id)" )
					->join ( "setup_troubles_types", "setup_troubles_types.id = troubles.type_id" )
					->where ( "troubles.status = 'NEW'" )					
					->group_by ( "setup_troubles_types.title,setup_troubles_types.id" )
					->get ( "troubles" );
		return $trouble_type_fetch->result ();
	}
}
