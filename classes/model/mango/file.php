<?php defined('SYSPATH') or die('No direct script access.');

class Model_Mango_File extends Mango 
{
	protected $_fields = array(
		'directory'	=> array(
			'type'		=> 'string',
			'required' 	=> true,
			'not_empty'	=> true,
		),
		'hash'		=> array(
			'type'		=> 'string',
			'required'	=> true,
			'not_empty' => true,
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
	);

    public function check_timestamp($val) 
    {
        if ( ! empty($val))
        {
            if (is_numeric($val))
            {
                if (strlen($val) > 6)
                    return true;    
                else
                	return false;
            } 
            else 
            {
				return false; 
			}        
        } 
        else 
        {
        	return false; 
		}
    }
}