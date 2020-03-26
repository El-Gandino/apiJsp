<?php
/**
 * @name Security
 * @author Gandini Sylvain
 * @version 0.0.1
 * @package API test
**/
namespace api\Security;
class security
{
    	//CONSTRUCTOR
	public function __construct()
	{
		$this -> mysqlDB = \connectDb::connectDb();
		$this -> mysqlConnect = $this -> mysqlDB -> getConnection();
	}
	public static function getInstance()
	{
		if(!isset(self::$instance))
		{
			self::$instance = new security();
		}
		return self::$instance;
    }
	
}
?>