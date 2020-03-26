<?php
namespace Api\user;
class put
{
	static private $instance = null;
	protected $myDbConnect;

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
			$this -> insertUser($query['msg']);
			$this -> myDbConnect -> close ();
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
		if(!$_GET){
			return array(
				"ok" => false,
				"msg" => 'not GET data'
			);
		}
		$ok = true;
		$msg = 'No data : ';
		if(empty(['name'])){
			$ok = false;
			$msg .= 'name / ';
		}
		if(empty($_GET['surname'])){
			$ok = false;
			$msg .= 'surname / ';
		}
		if(empty($_GET['email'])){
			$ok = false;
			$msg .= 'email / ';
		}
		if(empty($_GET['password'])){
			$ok = false;
			$msg .= 'password / ';
		}
		if($ok == true){
			$msg = $_GET;
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
			"ok" => $user,
		); 
	}
}
?>