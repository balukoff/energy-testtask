<?
final class Rest extends Controller
{
    private $token;
	private $account_id;
	private $session;

	// default method of class
	public function index(){	
		$data['header'] = $this->view->load('common/header');
		$data['footer'] = $this->view->load('common/footer');
		$this->view->render('rest.tpl', 'rest.tpl', $data);
	}
	
	// private method that get params from request
	private function getID(){
	 return isset($_POST['id'])?$_POST['id']:false;
	}
	
	// method send delete-request to api and kills client by ID
	public function deleteClient(){
	 $id = $this->getID();
	 if (!$id) {echo 'error getting client ID'; return;}
	 if ($state = $this->loginToSandBox()){
			$ch = $this->session;
			curl_setopt($ch, CURLOPT_URL, SANDBOX_URL.$this->account_id.'/clients/'.$id.'?token='.$this->token);
			curl_setopt($ch, CURLOPT_HEADER, 'Accept: application/json;');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			$out = curl_exec($ch);
			header('Content-Type: application/json');
			echo json_encode($out);
	 }else
	  echo json_encode(array('message' => 'Error operation'));
	}

	// get single client function	
	public function getClient(){
	 $id = $this->getID();
	 if (!$id) {echo 'error getting client ID'; return;}
	 if ($state = $this->loginToSandBox()){
			$ch = $this->session;
			curl_setopt($ch, CURLOPT_URL, SANDBOX_URL.$this->account_id.'/clients/'.$id.'?token='.$this->token.'&id='.$id.'&accountId='.$this->account_id);
			curl_setopt($ch, CURLOPT_HEADER, 'Accept: application/json;');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 20);
			$out = curl_exec($ch);
			header('Content-Type: application/json');
			echo json_encode($out);
	 }else
	 echo json_encode(array('message' => 'Error operation'));
	}
	
	// get city list from api
	public function getCities(){
	if($session = curl_init()){
			$this->session = $session;
			curl_setopt($session, CURLOPT_URL, SANDBOX_URL.'cities?lang=ru');
			curl_setopt($session, CURLOPT_HEADER, 'Content-Type: application/json; charset=UTF-8');
			curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($session, CURLOPT_TIMEOUT,300);
			curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($session, CURLOPT_SSL_VERIFYHOST, false);
			$out = curl_exec($session);
			header('Content-Type: application/json');
			echo json_encode($out);
			return true;
	}else
	print 'error: Curl is not initialized' ;
}
	// method that create or update client on a server side by api
	public function createUpdateClient(){
	 $client = $_POST['client'];
	 parse_str($client,$data);
	 if ($state = $this->loginToSandBox()){
			$ch = $this->session;
			if ($data['operation'] == 'create')
			$url = SANDBOX_URL.$this->account_id.'/clients?token='.$this->token; else
			$url = SANDBOX_URL.$this->account_id.'/clients/'.$data['id'].'?token='.$this->token;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 'Content-Type: application/json; Accept: application/json;');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 20);
			if ($data['operation'] == 'create')
			curl_setopt($ch, CURLOPT_POST, true);
			else
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, 
			            json_encode(array("title" => $data['title'],
										  "fullTitle" => $data['fullTitle'],
									      "idCity" => (int)$data['idCity'],
										  "address" => $data['address'],
										  "phone" => $data['phone'],
										  "email"=> $data['email'],
										  "inn" => $data['inn'],
										  "kpp" => $data['kpp'],
										  "jurAddress" => $data['jurAddress'],
										  "createDate" => 0,
										  "modifyDate" => 0
							              )
									));
			$out = curl_exec($ch);
			header('Content-Type: application/json');
			echo json_encode($out);
	 }else
	  echo $state;
	}
	
	// returns client List from api
	public function ClientList(){
	//GET /{accountId}/clients
     if ($state = $this->loginToSandBox()){
	if( $ch = curl_init() ) {
			curl_setopt($ch, CURLOPT_URL, SANDBOX_URL.$this->account_id.'/clients?token='.$this->token);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			$out = curl_exec($ch);
			header('Content-Type: application/json');
			echo json_encode($out);
	} 
	 }else
	  echo $state;
	}
	
	//CURLOPT_PUT
	// login to api and returns pair of values: token and account_id
	private function loginToSandBox(){
	if( $session = curl_init() ) {
			$this->session = $session;
			curl_setopt($session, CURLOPT_URL, SANDBOX_URL.'login?user='.SANDBOX_LOGIN.'&password='.SANDBOX_PASSWORD);
			curl_setopt($session, CURLOPT_HEADER, null);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($session, CURLOPT_TIMEOUT, 30);
			curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($session, CURLOPT_SSL_VERIFYHOST, false);
			$out = curl_exec($session);
			$out = json_decode($out);
			if ($out->token){
			 $this->token = $out->token;
			 $this->account_id = $out->accountId;
			 return true;
			}else
			return 'error: '.$out->message;
	}else
	return 'error: Curl is not initialized' ;
}

}
?>