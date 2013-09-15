<?php defined('SYSPATH') or die('No direct script access.');

return array(
	
	// Module Information
	'module'	=> array(
		'name'		=> 'Filedrop',
		'overview'	=> 'A file manager featuring HTML5 drag and drop support',
		'version'	=> '1.0.1',
		'url'		=>	array(
			'author'	=> 'http://arr.ae', 
			'updates'	=> 'http://test.com/?module=filedrop',
		),
		
		// create a point release
		// levels: update, feature, security
		'changelog'	=> array(
			'1.0.0'	=> array('security' => 'Initial Development')
		),
	),
	
	// Required Modules
	'requires'	=> array(
		'mango'	=> '1.0.0'
	),
	
	// Optional Module Support
	'optional'	=> array(
	
	),
	
	'theme'		=> array(
		'settings'	=> array(
			'media'	=> 'builder'
		),
		'styles'	=> 'resources/styles',
		'scripts'	=> 'resources/scripts'
	),
	
	'security'	=> array(
		'check'	=> TRUE
	),

	/*
	 * The ACL Resources (String IDs are fine, use of ACL_Resource_Interface objects also possible)
	 * Use: ROLE => PARENT (make sure parent is defined as resource itself before you use it as a parent)
	 */
	'resources' => array
	(
		'filedrop' 	=> null,
	),

	/*
	 * The ACL Rules (Again, string IDs are fine, use of ACL_Role/Resource_Interface objects also possible)
	 * Split in allow rules and deny rules, one sub-array per rule
	 */
	'rules' => array
	(
		'allow' => array
		(
			'filedrop' => array(
				'role'		=> array('user'),
				'resource'	=> array('filedrop'),
				'privilege'	=> array('index', 'directory', 'upload'),
			)
		),
		'deny' => array
		(
			// ADD YOUR OWN DENY RULES HERE
		)
	)
);