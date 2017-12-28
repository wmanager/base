<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

$config ['user_working_time'] = array (
		'morning_section_start' => '9:00:00',
		'morning_section_end' => '13:00:00',
		'afternoon_section_start' => '14:00:00',
		'afternoon_section_end' => '18:00:00',
		'holidays' => array (
				"01-01" => "Capodanno",
				"01-06" => "Epifania",
				"04-25" => "Festa della liberazione",
				"05-01" => "Festa dei lavoratori",
				"06-02" => "Festa della repubblica",
				"08-15" => "Ferragosto",
				"11-01" => "Festa di tutti i santi",
				"12-08" => "Festa dell'immacolata",
				"12-25" => "Natale",
				"12-26" => "Giorno di Santo Stefano" 
		) 
);
