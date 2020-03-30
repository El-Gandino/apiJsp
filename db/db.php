<?php

	//namespace api;
    class connectDb 
    {
        private static $instance = null;
        public $connection = null;

        private function __construct()
        {
            mysqli_report(MYSQLI_REPORT_STRICT);
        }
		//skeleton
		public static function getInstance()
		{
			if (!isset(self::$instance)) {
				self::$instance = new connectDb();
			}
			return self::$instance;

		}
		//public method
		public function getConnection()
		{
			if (isset($this->connection)
				&& !$this->connection->connect_errno ){
				return $this->connection;
			}
			return $this->creatConnection();
		}
		public function creatConnection()
		{
			try{
				$this -> connection = new mysqli('localhost',"root","",'apidb',3306);
				$this -> connection -> set_charset("utf8");

				return $this->connection;
			}catch(Exception $e){
				return array(
					"ok" => false,
					"msg" => 'error dbConnect :'.$e
				);
			}

		}
		public function __destruct()
		{
			if (isset($this->connection) 
			&& is_resource($this->connection) 
			&& get_resource_type($this->connection) === 'mysql link') {
					$this -> connection -> close();
			}
		}
    }
?>
