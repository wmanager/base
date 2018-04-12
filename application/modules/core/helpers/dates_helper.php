<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('datait2ts'))
{
    function datait2ts($data,$sep="/") {
	  $dt = explode($sep,$data);
	  $y = $dt[0];
	  $m = $dt[1];
	  $d = $dt[2];
	  return strtotime($y.'-'.$m.'-'.$d);
	}
	  
	
}

if ( ! function_exists('timecheck'))
{
	function timecheck($time) {
		$flag = preg_match('#^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#', $time);
		return $flag;
	}
	 

}

if ( ! function_exists('datecheck'))
{
	function datecheck($date) {
		$flag = preg_match('/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/', $date);
		return $flag;
	}


}