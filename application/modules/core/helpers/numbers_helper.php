<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
     
    if ( ! function_exists('clean_number'))
    {
      function clean_number($number)
      {
        if($number=='') $number = 0;

        if(strpos($number, ',') !== false)
        {
          $cleaned = str_replace('.','',$number);
        } else {
          $cleaned = $number;
        }
        $cleaned = str_replace(',','.',$cleaned);
        return $cleaned;
      }
    }
    
  if ( ! function_exists('format_num'))
    {
      function format_num($num, $precision = 0) {
        if($num == '') $num = 0;
         if ($num >= 1000 && $num < 1000000) {
          $n_format = number_format((int)$num/1000,$precision).'K';
          } else if ($num >= 1000000 && $num < 1000000000) {
          $n_format = number_format((int)$num/1000000,$precision).'M';
         } else if ($num >= 1000000000) {
         $n_format=number_format((int)$num/1000000000,$precision).'B';
         } else {
         $n_format = (int)$num;
          }
        return $n_format;
      } 
}
   