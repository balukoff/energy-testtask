<?
class Main extends Controller
{
	public function index(){	
		$data['header'] = $this->view->load('common/header');
		$data['footer'] = $this->view->load('common/footer');
		$data['host'] = DIR_APP;
		$this->view->render('main.tpl', 'main.tpl', $data);
	}
}
?>