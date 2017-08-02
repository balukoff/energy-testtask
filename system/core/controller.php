<?
class Controller {
	
	public $model;
	public $view;
	
	function __construct(){
		$this->view = new View();
	}
	
	function index(){

	}
	
	public static function load($file){ 
	 if (is_file(DIR_APP.'controller/'.$file.'.php')){
	 require_once DIR_APP.'controller/'.$file.'.php';
	} else
	return false;
	

	}
	
}

?>