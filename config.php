<?php

$windbloom = (object) array();

$wb = (object) array();

/* CONFIGURACION DEL SISTEMA */

$windbloom->sys = array(
					  
	#"url" => 'http://localhost/Valhalla/Valhalla/',
    "url" => 'http://localhost/Valhalla/',
        
    "path" => getcwd().'/',
        
	"upload" => 'uploads/',
        
	"kernel" => 'kernel/',
            
	"lang" => 'lang/',
        
	"lang" => 'es',
	
	"crypt" => 'Vi28aA'
	
);


define("DBHOST","localhost");

define("DBUSER","dev");

define("DBPASS","qwe8521z");

define("DBNAME","valhalla");
