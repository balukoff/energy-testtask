<?php

class NotFound extends Controller
{
	
	function index()
	{
		$this->view->render('404.tpl', '404.tpl');
	}

}
?>