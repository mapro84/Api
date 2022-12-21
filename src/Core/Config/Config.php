<?php
namespace src\Core\Config;

class Config{
	
	private static $genconf;
	
	private function __construct(){}
	
	public static function getGenConf(){
		if(is_null(self::$genconf)){
			require_once(__DIR__."/../../conf/conf.php");
			self::$genconf = GENCONF;
		}
		return self::$genconf;
	}
	
	public static function getGenConfKey($key){
		if(is_null(self::$genconf)){
			require_once(__DIR__."/../../app/conf/conf.php");
			self::$genconf = GENCONF;
		}
		return self::$genconf[$key];
	}

	public function __clone(){}
}