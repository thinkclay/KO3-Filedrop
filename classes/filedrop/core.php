<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Filedrop
 * 
 * @package	Filedrop
 * @author	Bryan Galli
 */
class Filedrop_Core
{	
	/**
	 * Multi-dimentional data array that gets sent to the view
	 *
	 * @var array
	 */
	public $data;
	
	/**
	 * Multi-dimentional error array that gets sent to the view
	 *
	 * @var array
	 */
	public $errors;
	
	/**
	 * The user id used throughout this class
	 *
	 * @var string
	 */
	protected $_uid;
	
	/**
	 * The config dir used throughout this class
	 *
	 * @var string
	 */
	protected $_path;
	
	/**
	 * The view which is being called
	 *
	 * @var string
	 */
	protected $_view;
	
	/**
	 * The user's base path
	 *
	 * @var string
	 */
	private $_user_base_path;
	
	
	private function _build_array($directory, $recursive)
	{
		$array_items = array();
		
		if ($handle = opendir($directory)) 
		{
			while ($file = readdir($handle)) 
			{
				if ( ! preg_match('/^\./', $file)) 
				{
					if (is_dir($directory.'/'.$file) AND $recursive) 
					{
						$array_items['folders'][$file] = $this->_build_array($directory.'/'.$file, $recursive);
					} 
					else 
					{
						$array_items['files'][] = preg_replace("/\/\//si", "/", $file);
					}
				}
			}
			closedir($handle);
		}
		
		return $array_items;
	}
	
	/** 
	 * Constructor - set the kid property for object scope
	 */
	public function __construct($uid)
	{
		$this->_config = Kohana::$config->load('filedrop');
		$this->_path = realpath(Kohana::$config->load('filedrop.path'));
		$this->_uid = $uid;
		$this->_check_for_base_dir();
		$this->_user_base_path = $this->_config['base_dir'].(string)$this->_uid.'/filedrop/';
	}
	
	/**
	 * Factory - Returns a new Filedrop Object
	 * 
	 * @static
	 * @param	mixed	$uid	user id
	 * @return	obj		Filedrop
	 */
	public static function factory($uid = null)
    {
    	if ($uid)
        	return new Filedrop($uid);
		else 
			return new Filedrop;
    }
	
	/**
	 * Create a new folder in the user object as well as a physical folder
	 *
	 * @param	str		$dir	absolute server path
	 * @param	str		$name	file name
	 * @return	bool
	 */
	public function create_folder($name = null, $path = null)
	{
		if ($name != null)
		{
			$formatted_name = $this->_format_name($name);
			if ($path == null OR $path == '')
			{
				$path = $this->_config['base_dir'].(string)$this->_uid.'/filedrop/';
			}
			else
			{
				$path = $this->_config['base_dir'].(string)$this->_uid.'/filedrop/'.$path;
			}
			if ( ! $this->_check_path($path))
			{
				if ( ! $this->_create_path($path))
				{
					return $this;
				}
			}
			if ($this->_make_folder($name, $path))
			{
				$this->data = array('folder_created' => $name, 'full_path' => $path.$name);
				return $this;
			}
			else 
			{
				return $this;
			}
		}
		else 
		{
			$this->errors['no_folder_name'] = 'You did not provide a folder name to create.';
			return $this;
		}
	}

	/**
	 * Get Folders that the current user has access to
	 *
	 * @param	str		$folder_id	folder id 
	 * @param	bool	$as_array
	 * @return	obj		Filedrop
	 */
	public function get_folders($folder_id = false)
	{		
		if ($folder_id)
		{
			$this->data = Mango::factory('mango_user', array('_id' => $this->_uid))
				->load(1, null, null, array('mango_filedrop.'.$folder_id => 1), array())
				->as_array(true);
				
			$this->get_files($folder_id);
		}
		// otherwise, we're probably looking for a list of all folders
		else 
		{
			$the_folders = Mango::factory('mango_user', array('_id' => $this->_uid))->load();
			$this->data = $the_folders->mango_filedrop;	
		}
		return $this;
	}
	
	/**
	 * Get Files that the current user has access to from a directory
	 *
	 * @param	str		$folder_id	folder id 
	 * @param	bool	$as_array
	 * @return	obj		Filedrop
	 */
	public function get_files($folder_id)
	{	
		$folder = Mango::factory('mango_user', array('_id' => $this->_uid))
			->load(1, null, null, array('mango_filedrop.'.$folder_id => 1), array())
			->as_array(true);
		
		$dir = $folder['mango_filedrop'][$folder_id]['directory'].'/';
		$file = $folder['mango_filedrop'][$folder_id]['hash'];
		$path = $dir.$file;
		
		if (file_exists($path) AND is_dir($path))
		{
			// get array of dir, strip files starting with '.', and reset the keys for easy json parsing
			$this->data = $this->_build_array($path, true);
		}
		else 
		{
			error_log('file did not exist');
			// Put logic to create strays
		}
				
		return $this;
	}
	
	/**
	 * Delete Folder 
	 *
	 * @return void
	 * @param string of the folder ID that you want to delete
	 */
	public function delete_folder($folder_id)
	{
		$user = Mango::factory('mango_user', array('_id' => $this->_uid))->load(); 
		unset($user->mango_filedrop[$folder_id]);
		$user->update();
		
		$this->data = array('status' => 'success');
		
		return $this;
	}
	
	/**
	 * Upload File
	 */
	public function upload_file()
	{
		$files = Validation::factory($_FILES);
		$files->rule('files', array('Upload', 'not_empty'));
		$files->rule('files', array('Upload', 'valid'));
		
		if ($files->check())
		{
			error_log(var_export($_POST, true));
			error_log(var_export($_FILES, true));
			
			// If you want to ignore the uploaded files, set $demo_mode to true;
			$dir = urldecode($_POST['directory']).'/'.$_POST['hash'].'/';
			
			error_log($dir);
			
			if ($_FILES['files']['error'] == 0 ){
			
				$pic = $_FILES['files'];
			
				// Move the uploaded file from the temporary
				// directory to the uploads folder:
				if (move_uploaded_file($pic['tmp_name'], $dir.$pic['name'])){
					error_log('File was uploaded successfuly!');
					//the database must be broken into two parts
					//A. a linking "table" with two fields 'user_id' 'file_id'
					//B. file data with fields 'owner', 'created', 'path', 
					//this will be used to create the linking system
				}
				else 
				{
					error_log('could not move file');
				}
			}
			else 
			{
				error_log('Something went wrong with your upload!');			
			}

		}

		$this->data = $_FILES;
		
		return $this;
	}
	
	/**
	 * Render Views for quick HMVC style templating
	 *
	 * @param	str		a string of the name of the view that you would like to render
	 * @return	obj		view object
	 */
	public function render($view)
	{
		$this->_view = $view;
		return View::factory('filedrop/'.$this->_view)->bind('data', $this->data);
	}
	
	public function upload($data)
	{
		return print_r($data);
	}
	
	public function share_file()
	{
		//used to create a connection between your files and other users
	}
	
	public function share_folder()
	{
		//used to create a connection between your folders and other users
	}
	
	public function download()
	{
		//used to download a file or folder
	}
	
	public function search()
	{
		
	}
	
	private function _check_for_base_dir()
	{
		if ( ! is_dir($this->_config['base_dir']))
		{
			$this->errors['no_base_dir'] = 'The base dir (set in the config file) does not exist.';
		}
		if ( ! is_writeable($this->_config['base_dir']))
		{
			$this->errors['base_dir_no_write'] = 'The base dir (set in the config file) is not writeable.';
		}
	}
	
	private function _format_name($name = '')
	{
		$name = strtolower(trim($name));
		foreach($this->_config['replace_chars'] as $needle)
		{
			$name = str_replace($needle, '_', $name);
		}
		return $name;
	}

	private function _check_path($path)
	{
		if ( ! is_dir($path) OR ! is_writeable($path))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	private function _create_path($path)
	{
		$path_to_create = $this->_user_base_path;
		$path_array = explode('/', $path);
		foreach ($path_array as $next_folder)
		{
			if ($this->_check_path($path_to_create.$this->_format_name($next_folder)))
			{
				$path_to_create = $path_to_create.$this->_format_name($next_folder).'/';
			}
			else 
			{
				if ( ! $this->_make_folder($next_folder, $path_to_create))
				{
					return false;
				}
			}
		}
		return true;
	}
	
	private function _make_folder($name, $path, $mode = null)
	{
		if ($mode == null)
		{
			$mode = $this->_config['default_mode'];
		}
		if (mkdir($path.$this->_format_name($name), $mode))
		{
			if ($this->_make_folder_in_db($path, $this->_format_name($name), $mode))
			{
				return true;
			}
			else 
			{
				rmdir($path.$this->_format_name($name));
				return false;
			}
		}
		else 
		{
			$this->errors['cant_make'] = 'Could not make the folder '.$path.$this->_format_name($name).'.';
			return false;
		}
	}
	
	private function _make_folder_in_db($path, $name, $mode)
	{
		if ($this->_config->database_type == 'mongo')//check for the mongo database type
		{
			$folder = array('parent_path' => $path, 'full_path' => $path.$this->_format_name($name), 'name' => $this->_format_name($name), 'time' => time(), 'owner' => (string)$this->_uid, 'mode' => $mode);
			if ($this->_folder_model_check($folder))
			{
				$folder_object = Mango::factory('mango_folder');
				$folder_object->values($folder)->create();
				$this->data = $folder_object->as_array();
				return true;
			}
			else 
			{
				return false;
			}
		}
		elseif ($this->_config->database_type == 'mysql')//if database_type in the config file is set to mysql
		{
			//put in the necessary mysql stuff here
		}
		else//if database_type in the config file is not set to anything that we have set it up for
		{
			$this->errors = array('unknown_database' => 'The database you have set in your filedrop config file is not supported.');//set errors
			return false;//return false
		}
	}

	private function _folder_model_check($folder_data)
	{
		if ($this->_config->database_type == 'mongo')//check for the mongo database type
		{
			$folder_model = Mango::factory('mango_folder');//load up an empty model object
			$folder_model->values($folder_data);//set the values in that model to the data we want to check
			try
			{
				$folder_model->check();//run the check against the model
				$good_folder = $folder_model->as_array();//set good data to the model converted to an array
				return $good_folder;//return the array
			}
			catch(Mango_Validation_Exception $e)//if it didn't match the model
			{
				$this->errors['bad_folder_info'] = 'The info you provided for the folder was unuseable.';//get the errors and set them
				return false;//return false
			}
		}
		elseif ($this->_config->database_type == 'mysql')//if database_type in the config file is set to mysql
		{
			//put in the necessary mysql stuff here
		}
		else//if database_type in the config file is not set to anything that we have set it up for
		{
			$this->errors = array('unknown_database' => 'The database you have set in your contacts config file is not supported.');//set errors
			return false;//return false
		}
	}
}