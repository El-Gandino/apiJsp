<?php
namespace Api\person;
class get
{
	static private $instance = null;
	protected $myDbConnect;
	private $requieredRequest = array(
		'key' => true,
		'login' => true
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
			self::$instance = new get();
		}
		return self::$instance;
	}
	public function actionDefault(){
		$this -> myDbConnect = $this -> myDb -> getConnection();
		$this -> myDbConnect -> select_db('apidb');
		$query = $this -> setData($_POST);
		$checkUser = $this -> security -> userAuth($query['msg']['key'],$query['msg']['login']);
		if($checkUser == false){
			return array(
				"ok" => false,
				"msg" => 'user or key false',
			);
		}
		if($query['ok'] == false){
			return $query;
		}
		$return = $this -> getData($query['msg']);
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
					"ok" => $ok,
					"msg" => 'data requiered : '.$key
				);
			}		
		}
		return array(
			"ok" => $ok,
			"msg" => $request
		);
	}
	private function getData(array $data)
	{
		$key = $this -> myDbConnect -> real_escape_string($data['key']);
		$query = "SELECT * FROM `person` WHERE  `tokenuser` = '$key' && deleted = '0'";
		$values = $this -> myDbConnect -> query($query);
		$returnArray =[];
		while ($value = mysqli_fetch_assoc($values)) {
			$value['type'] = 'person';
			$returnArray[] = $value;
		}
		if($returnArray){
			return array(
				"ok" => true,
				"msg" => 'ok',
				"query" => $returnArray
			);
		}
		return array(
			"ok" => false,
			"msg" => 'user or mdp false',
		);
	}
}
?>