<?php
namespace Api\user;
class auth
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
			self::$instance = new auth();
		}
		return self::$instance;
	}
	public function actionDefault(){
		$this -> myDbConnect = $this -> myDb -> getConnection();
		$this -> myDbConnect -> select_db('apidb');
		$query = $this -> setData(); 
		if($query['ok'] == false){
			return $query;
		}
		$return = $this -> checkExist($query['msg']);
		$this -> myDbConnect -> close ();
		return $return;
	}
	private function setData()
	{
		if(!$_POST){
			return array(
				"ok" => false,
				"msg" => 'not POST data'
			);
		}
		$ok = true;
		$msg = 'No data : ';
		$query = json_decode($_POST['query'],true);
		if(empty($query['login'])){
			$ok = false;
			$msg .= 'login / ';
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
	private function checkExist(array $data)
	{
		$email = $this -> myDbConnect -> real_escape_string($data['login']);
		$pass = hash('SHA512',$data['password']);
		$query = "SELECT `email`,`token` FROM `user` WHERE  `email` = '$email' && `password` = '$pass' ";
		$user = $this -> myDbConnect -> query($query);
		$value = mysqli_fetch_assoc($user);
		
		if($user->num_rows != 0){
			return array(
				"ok" => true,
				"msg" => 'ok',
				"query" => $value
			);
		}
		return array(
			"ok" => false,
			"msg" => 'user or mdp false',
			
		);
	}
}
?>