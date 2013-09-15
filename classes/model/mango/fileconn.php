<?php
class Model_Mango_Fileconn extends Mango {

	protected $_fields = array(
		'file_id' => array(
			'type'		=> 'string',
			'required'  => true,
			'not_empty' => true,
		),
		'user_id' => array(
			'type'		=> 'string',
			'required'  => true,
			'not_empty' => true,
		)
	);
}