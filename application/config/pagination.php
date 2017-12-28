<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
	/* This Application Must Be Used With BootStrap 3 * */
$config ['uri_segment'] = 5;
$config ['per_page'] = 10;

$config ['full_tag_open'] = "<ul class='pagination'>";
$config ['full_tag_close'] = "</ul>";
$config ['num_tag_open'] = '<li>';
$config ['num_tag_close'] = '</li>';
$config ['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
$config ['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
$config ['next_tag_open'] = "<li>";
$config ['next_tagl_close'] = "</li>";
$config ['prev_tag_open'] = "<li>";
$config ['prev_tagl_close'] = "</li>";
$config ['first_tag_open'] = "<li>";
$config ['first_tagl_close'] = "</li>";
$config ['last_tag_open'] = "<li>";
$config ['last_tagl_close'] = "</li>";
$config ['last_link'] = 'Last';
$config ['first_link'] = 'First';
$config ['num_links'] = 5;