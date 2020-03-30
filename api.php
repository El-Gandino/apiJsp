<?php
class Api
{
	//__PROPERTIES__________
	static private $instance=null;
	private $startScript;
	private $localHostName;
	private $pathUrl;
	private $authorization;
	private $endPoints;
	private $action;
	private $host;
	private $listClass;

	
	//__CONSTRUCTOR_________
	public function __construct()
	{
		require_once('ground.php');
		$this -> ground = \api\Ground::getInstance();
		$this -> endPoints = array (
			'object' => array(
				'actions' => array (
					'get' => true,
					'put' => true,
					'update' => true,
					'delete' => true,
					'auth' => true
				)
			)
		);
	}
	//GET CURRENT OBJECT
	public static function getInstance()
	{
		if(!isset(self::$instance))
		{
			self::$instance = new Api();
		}
		return self::$instance;
	}
	public function setParameters() 
	{		
		$this -> pathUrl = $this -> ground -> getPathName();
		if (!$this -> pathUrl['ok'] || $this -> pathUrl['nb'] != 2) 
		{
			$this -> status = 'error';
			$this -> setOutput(
				array(
					'status' => 'error',
					'msg' => 'syntaxerror'
				)
			);
		}
		$this->endPoint = $this->pathUrl['endPoint'];
		$this->action = $this->pathUrl['action'];
	}
	public function setData()
	{
		if(!is_file($this -> pathUrl['endPoint'].'/'.$this -> pathUrl['endPoint'].'.php' )){
			$this -> setOutput(
				array(
					'status' => 'error',
					'msg' => 'route not exist'
				)
			);
			return false;
		}
		if(!$this -> listClass && !$this -> listClass[$this -> pathUrl['endPoint']]){
			
			require($this -> pathUrl['endPoint'].'/'.$this -> pathUrl['endPoint'].'.php');
			
			$className = '\api\\'.$this->pathUrl['endPoint'];
			$this -> listClass[$this -> pathUrl['endPoint']] = $className::getInstance(); 
			//\api\Ground::getInstance();
		}
		$this -> setOutput($this -> listClass[$this -> pathUrl['endPoint']] -> setAction($this -> pathUrl['action']));
	}
	public function getAuthorization() 
	{
		$this -> authorization = true;
	}
	public function setEndPoint() 
	{
		$this -> setOutput(
			array(
				'status' => 'error',
				'msg' => 'syntaxerror2'
			)
		);
	}
	private function setOutput($data)
	{	
		//SET TIME COMPUTE
		$endScript = time();
		$dif = $endScript - $this -> startScript;
		header('Access-Control-Allow-Origin: *');
		header('Content-type: application/json; charset=UTF-8');
		header("Vary:Accept-Encoding");
		header("HTTP/1.1 200 OK");
		$output = array(
			"dataSet" => array (
				"status" => $data['ok'],
				"message" => $data['msg'],
				"hostResponding" => $this -> localHostName,
				"timeStart" => $this -> startScript,
				"timeEnd" => $endScript,
				"timeCompute" => $dif
			)		
		);
		if (isset($data['json']) && $data['json'] !== false) {
			$output['dataSet']['query'] = $data['json'];
		}
		echo json_encode($output, JSON_UNESCAPED_UNICODE);
	}
}
?>