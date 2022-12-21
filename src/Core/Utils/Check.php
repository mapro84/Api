<?php
namespace src\Core\Utils;

use src\Core\Config\Config;
use src\Core\Utils\Debug;

class Check {
	
	private static $genconf;
	
	public function __construct(){
		$this->genconf = Config::getGenConf();
	}
	
	// TODO check enter : Type, length, remove cotes, html, scripts; spaces or not authorized
	
	/**
	 * make secure an array got by $_POST, $_GET ...
	 * @param array $associativeArray
	 * @return string[]
	 */
	public static function makeSafeAssociativeArray(array $associativeArray){
		$safeAssociativeArray = [];
		foreach($associativeArray as $key => $value){
			if($key === 'url' || $key === 'further'){
				$safeValue = self::isUrl($value,true) ? $value : NULL;
			}else{
				$safeValue = self::is_safe_alphanumeric($value,true) ? $value : NULL;
			}
			$safeValue = $value;
			$safeAssociativeArray[$key] = $safeValue;
		}
		return $safeAssociativeArray;
	}
	
	function assertValidUTF8($str) {
		if (strlen($str) AND !preg_match('/^.{1}/us', $str)) {
			return false;
		}
		return true;
	}
	
	public static function is_numeric($int){
		$int = intval($int);
		$filteredValue = filter_var($int, FILTER_VALIDATE_INT);
		$filteredValue = (preg_match('/^[0-9\s]+$/', $int));
		if($filteredValue === false) {
			throw(new \Exception('Exception: This value is not an integer \n'));
		}else{
			return true;
		}
	}

	public static function is_safe_string($str,$spaces=false){
		$check = false;
		if($spaces === true){
			if (preg_match('/^[A-Za-z\s]+$/', $str)) {
				$check = true;
			}
		}else{
			if (preg_match('/^[a-zA-Z]+$/', $str)) {
				$check = true;
			}
		}
		return $check;
	}
	
	public static function is_safe_alphanumeric($str,$spaces=false){
		$check = false;
		if($spaces === true){
			if (preg_match('/^[\/À-žA-Za-z0-9\s,.:;*éèàçù!]+$/', $str)) {
				$check = true;
			}
		}else{
			if (preg_match('/^[a-zA-Z0-9,!]+$/', $str)) {
				$check = true;
			}
		}
		return $check;
	}
	
	public static function isUrl($str){
		$check = false;
		if(preg_match( '/^(http|https):\\/\\/[a-z0-9_]+([\\-\\.]{1}[a-z_0-9]+)*\\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i' ,$str)){
			//^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$ https://www.regextester.com/94502
			$check = true; //self::assertValidUTF8($str) === true ? true : false;
		}
		return $check;
	}
	
	public static function isPdf($str){
		$check = false;
		if(preg_match( '/\.pdf$/i' ,$str)){
			$check = true;
		}
		return $check;
	}
	
	/**
	 * Password preg_match with letters and at least 1 special character, 1 integer and an uppercase letter 
	 * @param String $password
	 * @return boolean
	 */
	public static function is_safe_password(String $password){
		$match = '/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/';
		if (preg_match($match, $password)) {
			return true;
		}else{
			return false;
		}
	}
	
}

