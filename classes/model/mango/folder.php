<?php defined('SYSPATH') or die('No direct script access.');

class Model_Mango_Folder extends Mango 
{
	protected $_fields = array(
		'parent_path'	=> array(
			'type'		=> 'string',
			'required' 	=> true,
			'not_empty'	=> true,
		),
		'full_path'	=> array(
			'type'		=> 'string',
			'required' 	=> true,
			'not_empty'	=> true,
		),
		'name'		=> array(
			'type'		=> 'string',
			'required'	=> true,
			'not_empty' => true,
		),
		'time'		=> array(
			'type'		=> 'string',
			'required'	=> true,
			'not_empty' => true,
		),
		'owner'		=> array(
			'type'		=> 'string',
			'required'	=> true,
			'not_empty' => true,
		),
		'mode'		=> array(
			'type'		=> 'string',
			'required'	=> true,
			'not_empty' => true,
		),
	);
}