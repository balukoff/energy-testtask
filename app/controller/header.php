<?
class Header extends Controller
{
	public function index(){	
		$this->view->render('header.tpl', 'header.tpl', $data);
	}
}
?>