<?php
namespace src\Core\Auth;

use src\Core\DB\DB;
use src\Core\Utils\Debug;
use src\Core\Config\Config;

class DBAuth {

	private static $env;
	
	public function __construct(){
		self::$env = Config::getGenConfKey('ENV');
	}
	
	/**
	 * 
	 * @param String $username
	 * @param String $password
	 * @return boolean
	 */
	public static function login(String $username, String $password){
		$request = "SELECT * FROM appuser WHERE username= ? AND password= ?";
		$user = DB::prepare($request, [$username,sha1($password)], 'src\\app\\user\\Appuser', true);
		//if(self::$env === 'dev') echo '<script>console.log(DBAuth login user return '.var_dump($user).');</script>';
		//if(self::$env === 'dev')  var_dump($request); echo"<br><br>";  var_dump($user);die();
		return $user === false ? false : $user;
	}
	
}

