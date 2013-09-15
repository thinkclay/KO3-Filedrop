<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Filedrop
 * 
 * @package	Private
 * @author	Clay McIlrath
 * 
 * @todo	Create an action for purging, at least for tests
 */
class Controller_Filedrop extends Controller_Private
{
	/**
	 * Landing Page - drop in the default view
	 */
	public function action_index()
	{
		//Filedrop::factory(parent::$user->_id)->create_folder('test 1');
		$this->template->main->content = Filedrop::factory(parent::$user->_id)->get_folders()->render('wrapper');
	}
	
	public function action_directory()
	{
		$this->auto_render = false;
		
		if (Request::$current->param('id') == 'create' AND isset($_POST['name']))
		{
			Filedrop::factory(parent::$user->_id)->create_folder($_POST['name']);
			echo json_encode($_POST);
		}
		elseif (Request::$current->param('id') == 'read' AND isset($_POST['folder_id']))
		{
			$files = Filedrop::factory(parent::$user->_id)->get_files($_POST['folder_id']);
			echo json_encode($files->data);
		}
		elseif (Request::$current->param('id') == 'delete' AND isset($_POST['folder_id']))
		{
			$files = Filedrop::factory(parent::$user->_id)->delete_folder($_POST['folder_id']);
			echo json_encode($files->data);
		}
	}
	
	public function action_upload()
	{
		$this->auto_render = false;
		
		$files = Filedrop::factory(parent::$user->_id)->upload_file($_FILES);
		echo json_encode($files->data);
	}
}