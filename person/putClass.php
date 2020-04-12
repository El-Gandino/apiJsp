<?php
namespace Api\person;
class put
{
	static private $instance = null;
	protected $myDbConnect;
	private $requieredRequest = array(
		'key' => true,
		'login' => true,
		'query' => true
	);
	private $requieredQuery = array(
		'name' => true,
		'surname' => true
	);
	public function __construct()
	{
		require_once('ground.php');
		$this -> ground = \api\Ground::getInstance();
		require_once('db/db.php');
		$this -> myDb = \connectDb::getInstance();	
		require_once('security/security.php');
		$this -> security = \api\security::getInstance();	
	}
	public static function getInstance()
	{
		if(!isset(self::$instance))
		{
			self::$instance = new put();
		}
		return self::$instance;
	}
	public function actionDefault(){
		$this -> myDbConnect = $this -> myDb -> getConnection();
		$this -> myDbConnect -> select_db('apidb');
		$query = $this -> setData($_POST);
		if($query['ok'] == false){
			return $query;
		}
		$user = array(
			'key' => $_POST['key'] ,
			'login' => $_POST['login']
		);
		$checkUser = $this -> security -> userAuth($user['key'],$user['login']);
		if($checkUser == false){
			return array(
				"ok" => false,
				"msg" => 'user or key false',
			);
		}
		$return = $this -> put($query['msg'],$user);
		$this -> myDbConnect -> close ();
		return $return;
	}
	private function setData(array $request)
	{
		if(empty($request)){
			return array(
				"ok" => false,
				"msg" => 'not POST data'
			);
		}
		$ok = true;
		foreach($this -> requieredRequest as $key => $val){
			if($val == false){
				continue;
			}
			if(empty($request[$key])){
				return array(
					"ok" => false,
					"msg" => 'data requiered : '.$key
				);
			}		
		}
		$query = json_decode($request['query'],true);
		foreach($this -> requieredQuery as $key => $val){
			if($val == false){
				continue;
			}
			if(empty($query[$key])){
				return array(
					"ok" => false,
					"msg" => 'data requiered in query: '.$key
				);
			}	
		}
		return array(
			"ok" => $ok,
			"msg" => $query
		);
	}
	private function put(array $data,array $user)
	{
		
		$token = md5(uniqid(rand(), true));
		$name = $this -> myDbConnect -> real_escape_string($data['name']);
		$surname = $this -> myDbConnect -> real_escape_string($data['surname']);
		$query = "INSERT INTO `person` (`token`, `tokenuser`,`name`, `surname`) VALUES ('$token','".$user['key']."', '$name', '$surname')";
		$result = $this -> myDbConnect -> query($query);
		if(!$result){
			return array(
				"ok" => false,
				"msg" => 'error in insert',
			);
		}
		return array(
			"ok" => true,
			"msg" => 'ok',
			"query" => array(
				'token'=>$token,
				'name'=>$name,
				'surname'=>$surname,
			),
		);
	}
}
?>