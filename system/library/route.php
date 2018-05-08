<? 
// singletone 
class Route
{
	protected static $_instance;
	
    private function __construct(){}
    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;   
    }
        return self::$_instance;
    }
    
	private function __clone(){}
    private function __wakeup(){}     
	
	static function start()
	{
		// контроллер и действие по умолчанию
		$controller_name = 'Main';
		$action_name = 'index';
		
		$routes = explode('/', $_SERVER['REQUEST_URI']);
		//print_r($routes);
		// получаем им€ контроллера
		if ( !empty($routes[2]) )
		{	
			$controller_name = $routes[2];
		}
		
		// получаем им€ экшена
		if ( !empty($routes[3]) )
		{
			$action_name = $routes[3];
		}
		$model_name = $controller_name;
		$controller_name = $controller_name;
		//$action_name = $action_name;

		$model_file = strtolower($model_name).'.php';
		$model_path = DIR_APP."model/".$model_file;
		if(file_exists($model_path))
		{
			include DIR_APP."model/".$model_file;
		}

		$controller_file = strtolower($controller_name).'.php';
		$controller_path = DIR_APP."controller/".$controller_file;
		//print DIR_APP."controller/".$controller_file;
		if(file_exists($controller_path))
		{
			include DIR_APP."controller/".$controller_file;
		}
		else{
			Route::ErrorPage404();
		}
		
		// создаем контроллер
		$controller = new $controller_name;
		$action = $action_name;
		
		if(method_exists($controller, $action)){
			// вызываем действие контроллера
			$controller->$action();
		}
		else{
			Route::ErrorPage404();
		}
	
	}
	function ErrorPage404()
	{
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/energy-testtask/';
        header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:'.$host.'notfound');
    }
}
?>