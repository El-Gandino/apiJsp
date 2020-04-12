<?php
namespace Api\person;
class delete
{
	static private $instance = null;
	protected $myDbConnect;
	private $requieredRequest = array(
		'key' => true,
		'login' => true,
		'query' => true
	);
	private $requieredQuery = array(
		'parent' => true,
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
			self::$instance = new delete();
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
		$return = $this -> delete($query['msg'],$user);
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
	private function delete(array $data,array $user)
	{
		$query = "UPDATE `apidb`.`person` SET `deleted`=b'1' WHERE  `token`='".$data['parent']."';";
		$result = $this -> myDbConnect -> query($query);
		if(!$result){
			return array(
				"ok" => false,
				"msg" => 'error in delete',
			);
		}
		return array(
			"ok" => true,
			"msg" => 'ok',
		);
	}
}
?>