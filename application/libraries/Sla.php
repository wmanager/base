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
class Sla {
	var $CI;
	var $working_time;
	public function __construct() {
		$this->CI = & get_instance ();
		$this->CI->config->load ( 'sla' );
		$this->working_time = $this->CI->config->item ( 'user_working_time' );
	}
	public function calculate($sla) {
		$date = $this->roundedtime ();
		
		$current_hour = date ( 'H:00:00', strtotime ( $date ) );
		$current_date = date ( 'Y-m-d', strtotime ( $date ) );
		$current_hour = $current_date . ' ' . $current_hour;
		
		// GET HOURS TO NEXT STARTING WORKING DAY
		$d = date ( 'd', strtotime ( $date ) ) + 1;
		$day2 = date ( 'Y-m', strtotime ( $date ) ) . '-' . $d . ' ' . $this->working_time ['morning_section_start'];
		
		// IF IS HOLIDAY ADD 24H
		while ( $this->isHoliday ( $day2 ) ) {
			$day2 = date ( 'Y-m-d H:i:s', strtotime ( '+1 day', strtotime ( $day2 ) ) );
		}
		
		// IF IS WEEKEND ADD 24H
		while ( $this->isWeekend ( $day2 ) ) {
			$day2 = date ( 'Y-m-d H:i:s', strtotime ( '+1 day', strtotime ( $day2 ) ) );
		}
		
		$start_date = $this->switchtime ( $date, $current_hour, $day2 );
		
		for($i = 1; $i <= $sla; $i ++) {
			
			$timestamp = strtotime ( $start_date );
			$time_added = date ( 'Y-m-d H:00:00', strtotime ( "+1 hours", $timestamp ) );
			// $start_date = $time_added;
			$time_added = $this->switchtime ( $date, $time_added, $day2, $i );
			// $time_added = date('Y-m-d H:00:00',strtotime('+'.$i.' hours',strtotime($start_date)));
		}
		
		if (isset ( $time_added )) {
			return $time_added;
		} else {
			return $start_date;
		}
	}
	private function switchtime($date, $current_hour, $day2, $increment = NULL) {
		$start_date = $this->adjustime ( $date, $current_hour, $day2 );
		
		if ($increment != NULL) {
			$start_date = date ( 'Y-m-d H:i:s', strtotime ( '+' . $increment . ' hours', strtotime ( $start_date ) ) );
			
			if (strtotime ( $start_date ) >= strtotime ( date ( 'Y-m-d ' . $this->working_time ['morning_section_end'], strtotime ( $date ) ) ) && strtotime ( $start_date ) <= strtotime ( date ( 'Y-m-d ' . $this->working_time ['afternoon_section_start'], strtotime ( $date ) ) )) {
				
				$start_hour = date ( 'H', strtotime ( $start_date ) );
				$end_hour = date ( 'H', strtotime ( date ( 'Y-m-d ' . $this->working_time ['morning_section_end'] ) ) );
				$subtract = ($start_hour - $end_hour);
				$start_date = date ( 'Y-m-d H:i', strtotime ( '+' . $subtract . ' hours', strtotime ( date ( 'Y-m-d ' . $this->working_time ['afternoon_section_start'] ) ) ) );
			}
			
			if (strtotime ( $start_date ) > strtotime ( date ( 'Y-m-d ' . $this->working_time ['afternoon_section_end'], strtotime ( $date ) ) )) {
				if ($increment > 8) {
					$days = $increment / 8;
					$day2 = date ( 'Y-m-d H:i', strtotime ( "+$days days", strtotime ( $day2 ) ) );
				}
				for($i = 1; $i <= $increment; $i ++) {
					
					$start_date = date ( 'Y-m-d H:i', strtotime ( '+' . $i . ' hours', strtotime ( $day2 ) ) );
					if (strtotime ( $start_date ) > strtotime ( date ( 'Y-m-d ' . $this->working_time ['afternoon_section_end'], strtotime ( $day2 ) ) )) {
						
						$start_date = $this->adjustime ( $date, $start_date, $day2 );
					}
				}
			}
		}
		return $start_date;
	}
	private function adjustime($date, $current_hour, $day2) {
		
		// IF CURRENT HOUR IS BEFORE OPENING TIME START FROM OPENING TIME
		if (strtotime ( $current_hour ) <= strtotime ( date ( 'Y-m-d ' . $this->working_time ['morning_section_start'], strtotime ( $date ) ) )) {
			$start_date = $current_date . ' ' . $this->working_time ['morning_section_start'];
		}
		
		// IF CURRENT HOUR IS BETWEEN MORNING TIME START FROM CURRENT TIME
		if (strtotime ( $current_hour ) >= strtotime ( date ( 'Y-m-d ' . $this->working_time ['morning_section_start'], strtotime ( $date ) ) ) && strtotime ( $current_hour ) <= strtotime ( date ( 'Y-m-d ' . $this->working_time ['morning_section_end'], strtotime ( $date ) ) )) {
			$start_date = $current_hour;
		}
		
		// IF CURRENT HOUR IS BETWEEN MORNING TIME END AND AFTERNOON TIME BEGIN, START FROM AFTERNOON TIME BEGIN
		if (strtotime ( $current_hour ) >= strtotime ( date ( 'Y-m-d ' . $this->working_time ['morning_section_end'], strtotime ( $date ) ) ) && strtotime ( $current_hour ) <= strtotime ( date ( 'Y-m-d ' . $this->working_time ['afternoon_section_start'], strtotime ( $date ) ) )) {
			$start_date = $current_date . ' ' . $this->working_time ['afternoon_section_start'];
		}
		
		// IF CURRENT HOUR IS BETWEEN AFTERNOON TIME START AND AFTERNOON TIME END, START FROM CURRENT TIME
		if (strtotime ( $current_hour ) >= strtotime ( date ( 'Y-m-d ' . $this->working_time ['afternoon_section_start'], strtotime ( $date ) ) ) && strtotime ( $current_hour ) <= strtotime ( date ( 'Y-m-d ' . $this->working_time ['afternoon_section_end'], strtotime ( $date ) ) )) {
			$start_date = $current_hour;
		}
		
		// IF CURRENT HOUR IS AFTER CLOSURE TIME START FROM NEXT DAY OPENING
		if (strtotime ( $current_hour ) > strtotime ( date ( 'Y-m-d ' . $this->working_time ['afternoon_section_end'], strtotime ( $date ) ) )) {
			$start_date = $day2;
		}
		
		return $start_date;
	}
	private function roundedtime() {
		$date = date ( 'Y-m-d H:i:s' );
		$minutes = date ( 'i' );
		
		$datetime = new DateTime ( $date );
		
		$remaining = 60 - $minutes;
		
		if ($minutes >= 30)
			$datetime->modify ( "+ $remaining minutes" );
		if ($minutes < 30)
			$datetime->modify ( "- $minutes minutes" );
		
		return $datetime->format ( 'Y-m-d H:i:s' );
	}
	private function isHoliday($data = false) {
		$data = date ( 'Y-m-d', strtotime ( $data ) );
		// creo un array con le festivita
		$array_festivita = $this->working_time ['holidays'];
		// se non ho la data come argomento restituisco l'array
		if (! $data) {
			return $array_festivita;
		}
		// creo un array con la data ricevuta
		$exp = explode ( '-', $data );
		// verifico la data
		if (! checkdate ( $exp [1], $exp [2], $exp [0] )) {
			// data non valida esco
			return "Data non valida!";
		}
		// time della data
		$timestamp = mktime ( 0, 0, 0, $exp [1], $exp [2], $exp [0] );
		// verifico se il giorno della settimana è Domenica
		// con date('w') (0->Dom 6->Sab)
		if (date ( 'w', $timestamp ) == 0) {
			// Se = a 0 è festivo ! esco
			return true;
		}
		// altrimenti creo una variabile per la ricerca nell array
		$mesegiorno = $exp [1] . "-" . $exp [2];
		// se true è festivo
		if (array_key_exists ( $mesegiorno, $array_festivita ))
			return $array_festivita [$mesegiorno];
			// non è festivo esco
		return false;
	}
	function isWeekend($date) {
		return (date ( 'N', strtotime ( $date ) ) >= 6);
	}
	function time_remaining($date) {
		$start_date = new DateTime ();
		$end_date = new DateTime ( $date );
		$interval = $start_date->diff ( $end_date );
		$arr = array (
				'days' => $interval->format ( '%d' ),
				'hours' => $interval->format ( '%h' ),
				'minutes' => $interval->format ( '%i' ) 
		);
		return $arr;
	}
}
