<?php
namespace Api\user;
class put
{
	static private $instance = null;
	public function __construct()
	{
		require_once('ground.php');
		$this -> ground = \api\Ground::getInstance();
		require_once('db/db.php');
		$this -> myDb = \connectDb::getInstance();	
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
		/*check exist*/
		try{
			/*check query*/
			$this -> myDbConnect = $this -> myDb -> getConnection();
			$this -> myDbConnect -> select_db('apidb');
			$query = $this -> setData();
			if($query['ok'] == false){
				return $query;
			}
			$exist = $this -> checkExist($query['msg']);
			if(isset($exist['ok'])){
				return $exist;
			}
			/*set query*/
			$return = $this -> insertUser($query['msg']);
			$this -> myDbConnect -> close ();
			return $return;
		}catch (Exception $e) {
			return array(
				"ok" => false,
				"msg" => 'error instert database :'.$e
			);
		}
	}
	/**
		* need upddate_
		* to optimise		
	*/
	private function setData(){
		if(!$_POST){
			return array(
				"ok" => false,
				"msg" => 'not POST data'
			);
		}
		$ok = true;
		$msg = 'No data : ';
		if(empty($_POST['query'])){
			$ok = false;
			$msg = 'no query';
			return array(
				"ok" => $ok,
				"msg" => $msg
			);
		}
		$query = json_decode($_POST['query'],true);
		if(empty($query['name'])){
			$ok = false;
			$msg .= 'name / ';
		}
		if(empty($query['surname'])){
			$ok = false;
			$msg .= 'surname / ';
		}
		if(empty($query['email'])){
			$ok = false;
			$msg .= 'email / ';
		}
		if(empty($query['password'])){
			$ok = false;
			$msg .= 'password / ';
		}
		if($ok == true){
			$msg = $query;
		}else{
			$msg = substr($msg,0,-2);
		}
		return array(
			"ok" => $ok,
			"msg" => $msg
		);
	}
	private function checkExist(array $data){
		$email = $this -> myDbConnect -> real_escape_string($data['email']);
		$query = "SELECT `token` FROM `user` WHERE`email` = '$email'";
		$user = $this -> myDbConnect -> query($query); 
		if($user->num_rows != 0){
			return array(
				"ok" => false,
				"msg" => 'user exist '
			);
		}
		return true;
	}
	private function insertUser($data){
		$token = md5(uniqid(rand(), true));
		$name = $this -> myDbConnect -> real_escape_string($data['name']);
		$email = $this -> myDbConnect -> real_escape_string($data['email']);
		$password = hash('SHA512',$data['password']);
		$query = "INSERT INTO `user` (`token`, `name`, `email`, `password`) VALUES ('$token', '$name', '$email', '$password')";
		$user = $this -> myDbConnect -> query($query);
		return array(
			"ok" => true,
			"msg" => 'ok',
			"query" => array('token'=>$token),
		);
	}
}
?>