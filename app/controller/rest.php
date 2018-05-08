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
	
	private function sendCURL($link, $options){
	 foreach($options as $option => $value){curl_setopt($link, $option, $value);}
	 return curl_exec($link);	
	}
	
	// method send delete-request to api and kills client by ID
	public function deleteClient(){
	 $id = $this->getID();
	 if (!$id) {echo 'error getting client ID'; return;}
	 if ($state = $this->loginToSandBox()){
			$ch = $this->session;
			$data = array( CURLOPT_URL     => SANDBOX_URL . $this->account_id . '/clients/' . $id . '?token=' . $this->token, 
						   CURLOPT_HEADER   => 'Accept: application/json;',
						   CURLOPT_TIMEOUT  => 30,
						   CURLOPT_RETURNTRANSFER => true,
						   CURLOPT_CUSTOMREQUEST  => "DELETE"
			);

			header('Content-Type: application/json');
			echo $this->sendCurl($ch, $data);
	 }else
	  echo json_encode(array('message' => 'Error operation'));
	}

	// get single client function	
	public function getClient(){
	 $id = $this->getID();
	 if (!$id) {echo 'error getting client ID'; return;}
	 if ($state = $this->loginToSandBox()){
			$ch = $this->session;
			header('Content-Type: application/json');
			$data = array(
					 CURLOPT_URL   => SANDBOX_URL . $this->account_id . '/clients/' . $id . '?token=' . $this->token . '&id=' . $id . '&accountId=' . $this->account_id, 
					 CURLOPT_HEADER   => 'Accept: application/json;',
					 CURLOPT_RETURNTRANSFER => true,
					 CURLOPT_TIMEOUT  => 20
					);
			echo $this->sendCurl($ch, $data);
	}else
	 echo json_encode(array('message' => 'Error operation'));
	}
	
	// get city list from api
	public function getCities(){
	if($session = curl_init()){
			$this->session = $session;
			$data = array(
					 CURLOPT_URL      => SANDBOX_URL.'cities?lang=ru', 
					 CURLOPT_HEADER   => 'Content-Type: application/json; charset=UTF-8',
					 CURLOPT_RETURNTRANSFER => 1,
					 CURLOPT_TIMEOUT  => 300, 
					 CURLOPT_SSL_VERIFYPEER => false,
					 CURLOPT_SSL_VERIFYHOST => false
					);
			
			header('Content-Type: application/json');
			echo json_encode($this->sendCurl($session, $data));
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
			$url = SANDBOX_URL.$this->account_id.'/clients?token='.$this->token; 
			else
			$url = SANDBOX_URL.$this->account_id.'/clients/'.$data['id'].'?token='.$this->token;
			$params = array(
					CURLOPT_URL            => $url, 
					CURLOPT_HEADER         => 'Content-Type: application/json; Accept: application/json;',
					CURLOPT_RETURNTRANSFER => 1, 
					CURLOPT_TIMEOUT		 => 20,
			);
			if ($data['operation'] == 'create')
			$params[CURLOPT_POST] = true; //curl_setopt($ch, CURLOPT_POST, true);
			else
			$params[CURLOPT_CUSTOMREQUEST] = 'PUT';
			$params[CURLOPT_POSTFIELDS] = json_encode(array("title" => $data['title'],
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
									);
			//$out = curl_exec($ch);
			header('Content-Type: application/json');
			echo json_encode($this->sendCurl($ch, $params));
	 }else
	  echo $state;
	}
	
	// returns client List from api
	public function ClientList(){
	//GET /{accountId}/clients
		if ($state = $this->loginToSandBox()){
	if( $ch = curl_init() ) {
			header('Content-Type: application/json');
			$data = array(
					 CURLOPT_URL   => SANDBOX_URL . $this->account_id . '/clients?token=' . $this->token, 
					 CURLOPT_HEADER   => false,
					 CURLOPT_RETURNTRANSFER => 1,
					 CURLOPT_TIMEOUT  => 10,
					 CURLOPT_SSL_VERIFYPEER => false,
					 CURLOPT_SSL_VERIFYHOST => false
					);
			echo $this->sendCurl($ch, $data);
    	} 
	 }else
	  echo $state;
	}
	
	//CURLOPT_PUT
	// login to api and returns pair of values: token and account_id
	private function loginToSandBox(){
	if( $session = curl_init() ) {
			$this->session = $session;
			$data = array(
					 CURLOPT_URL   => SANDBOX_URL.'login?user='.SANDBOX_LOGIN.'&password='.SANDBOX_PASSWORD, 
					 CURLOPT_HEADER   => false,
					 CURLOPT_RETURNTRANSFER => 1,
					 CURLOPT_TIMEOUT  => 10,
					 CURLOPT_SSL_VERIFYPEER => false,
					 CURLOPT_SSL_VERIFYHOST => false
					);
			$out = $this->sendCurl($session, $data);
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