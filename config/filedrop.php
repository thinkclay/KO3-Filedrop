<?php defined('SYSPATH') or die('No direct script access.');

return array(
	
	// ideally will eventually support orm/jelly/mango/sprig, but for now just mango
	'driver'	=> 'mango', 
	
	// absolute server path to your upload folder
	'base_dir'		=> '/var/data/users/',
	
	// allowed file types () 		
	'allowed'	=> array('gif', 'jpg', 'jpeg', 'png'),
	
	// chars to replace in folder names
	'replace_chars'		=> array(' ', '/', ';', ':', '&', '<', '>', '*', '%', '$', '|'),
	
	// default mode for folder creation eg 0777
	'default_mode'		=> 0775,
	
	// type of database you're using ..for now it can only be mongo
	'database_type' 	=> 'mongo',
);