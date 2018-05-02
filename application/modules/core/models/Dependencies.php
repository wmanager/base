<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

class Dependencies extends CI_Model
{
	
	public function get($for,$type){
		
		if($for == '' || $type == ''){
			return array();
		}
		
		$query = $this->db->where("template = '$for'")
							->where("type = '$type'")
							->order_by("module_order","ASC")
							->order_by("order","ASC")
							->get("dependencies");
		
		if($query->num_rows() > 0){					
			$result = $query->result_array();
			return $result;
		}else{
			return array();
		}	
	}
	
	public function get_menu($template = 'wmanager'){
		//get all childs
		$query_child = $this->db->where("is_child = 't'")->where("template = '$template'")->order_by("id","ASC")->get("setup_menu");
		$result_child = $query_child->result();
		
		//get unique all parents
		$query_parent_ids = $this->db->select("parent_id as id")->where("template = '$template'")->where("parent_id IS NOT NULL")->group_by("parent_id")->get("setup_menu");
		$result_parent_list = $query_parent_ids->result();
		
		$parent_ids = array();
		if($query_parent_ids->num_rows() > 0){
			foreach($result_parent_list as $item){
				$parent_ids[] = $item->id;
			}
		}
		
		//get all except child
		$query = $this->db->where("is_child = 'f'")->where("template = '$template'")->order_by("order","ASC")->get("setup_menu");
		$result_parent = $query->result();
		
		$menu_array = array();
		if($query->num_rows() > 0){
			$i = 0;
			foreach($result_parent as $item){
				$menu_array[$i]['label'] = $item->label;
				
				if($item->link != null){
					$menu_array[$i]['link'] = $item->link;
				}else{
					if(in_array($item->id,$parent_ids)){
						$menu_array[$i]['link'] = '#';
					}else{
						$menu_array[$i]['link'] = base_url();
					}
				}
				
				$menu_array[$i]['icon'] = $item->icon;
				if($item->class != NULL){
					$menu_array[$i]['class'] = $item->class;
				}else{
					$menu_array[$i]['class'] = '';
				}
				
				//access
				if(strpos($item->access,',') >= 0){
					
					$access = trim($item->access,",");
					$menu_array[$i]['access'] = explode(",",$access);
					
				}else{
					$menu_array[$i]['access'] = $item->access;
				}
				
				//children
				if(in_array($item->id,$parent_ids)){
					if($query_child->num_rows() > 0){
						
						$children = array();
						$j = 0;
						foreach($result_child as $child){
							if($child->parent_id == $item->id){
								$children[$j]['label'] = $child->label;
								$children[$j]['link'] = $child->link;
								
								if($child->class != NULL){
									$children[$j]['class'] = $child->class;
								}else{
									$children[$j]['class'] = '';
								}
								
								$children[$j]['label'] = $child->label;
								
								//access
								if(strpos($child->access,',') >= 0){
										
									$accesses = trim($child->access,",");
									$children[$j]['access'] = explode(",",$accesses);
										
								}else{
									$children[$j]['access'] = array(0=>$child->access);
								}
								$j++;
							}
						}
						$menu_array[$i]['children'] = $children;
					}
				}
				
				$i++;
			}
		}
		return $menu_array;
	}
}	
	
