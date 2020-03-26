<?php
/**
 * @name common
 * @author Gandini Sylvain
 * @version 0.0.1
 * @package API test
**/
namespace api;
class Ground
{
    static private $instance=null; 

	public function __construct()
	{
    
        
	}
	public static function getInstance()
	{
		if(!isset(self::$instance))
		{
			self::$instance = new Ground();
		}
		return self::$instance;
    }
    public function getPathName()
    {
        $pathName = array(
			'ok' => false,
		);
		if (!isset($_SERVER['REQUEST_URI'])) {
			return $pathName;
		}
		
		$path  = explode('?', $_SERVER['REQUEST_URI']);
        $pathName['name'] = $path[0];
        
		if ($pathName['name']) {
			$pathName['path'] = explode('/',$pathName['name']);
			array_splice($pathName['path'], 0, 1);
			$last = count($pathName['path']) - 1;
			if ($pathName['path'][$last] == '') {
				array_splice($pathName['path'], $last, 1);
			}
			$pathName['nb'] = count($pathName['path']);
        }
        $pathName['ok'] = true;
        $pathName['endPoint'] = $pathName['path'][1];
        $pathName['action'] = $pathName['path'][2]; 
		return $pathName;

    }

}
?>
