<?php

namespace Api;
class user 
{
    static private $instance = null;
	private $listClass;

	public function __construct()
	{
		require_once('ground.php');
		$this -> ground = \api\Ground::getInstance();
		
	}
	public static function getInstance()
	{
		if(!isset(self::$instance))
		{
			self::$instance = new user();
		}
		return self::$instance;
    }
	public function setAction(string $action)
	{
		if(!$this->listClass[$action]){
			require($action.'Class.php');
			$className = '\api\user\\'.$action;
			$this->listClass[$action] = $className::getInstance();
		}
		return $this->listClass[$action] -> actionDefault();
	}
}