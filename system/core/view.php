<?
class View
{
	//public $template_view; // здесь можно указать общий вид по умолчанию.
	
	function render($content, $template, $data = null)
	{
		
		if(is_array($data)){ extract($data);}
		ob_start();
	    include DIR_APP.'view/'.$template;
		$text = ob_get_clean();
	    echo $text;	
		//include DIR_APP.'view/'.$template;
	}
	
	public function load($template){
	 $path = DIR_APP.'view/'.$template.'.tpl';
	 if (is_file(DIR_APP.'view/'.$template.'.tpl')){
	  ob_start();
	  include $path;
	  $text = ob_get_clean();
	  return $text;	
	 }
	}
}

?>