<?php
/**
 * @name Security
 * @author Gandini Sylvain
 * @version 0.0.1
 * @package API test
**/
namespace api;
class security
{	
	static private $instance = null;
	protected $myDbConnect;
    //CONSTRUCTOR
	public function __construct()
	{
		require_once('db/db.php');
		$this -> myDb = \connectDb::getInstance();	
	}
	public static function getInstance()
	{
		if(!isset(self::$instance))
		{
			self::$instance = new security();
		}
		return self::$instance;
	}
	public function userAuth(string $key,string $email){
		$this -> myDbConnect = $this -> myDb -> getConnection();
		$this -> myDbConnect -> select_db('apidb');
		
		$query = "SELECT `token` FROM `user` WHERE  `email` = '$email' && `token` = '$key' ";
		$user = $this -> myDbConnect -> query($query);
		if($user->num_rows == 1){
			return true;
		}
		return false;
	}
	
}
?>