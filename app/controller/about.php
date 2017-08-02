<?
class About extends Controller
{
	public function index(){	
		$data['header'] = $this->view->load('common/header');
		$data['footer'] = $this->view->load('common/footer');
		$this->view->render('about.tpl', 'about.tpl', $data);
	}
}
?>
