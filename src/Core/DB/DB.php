<?php
namespace src\Core\DB;

use \PDO;
use \Exception;
use src\Core\Config\Config;
use src\Core\Utils\Debug;

class DB{

	
	// TODO pour eviter injections SQL
	// Requetes préparées :  $post = $db->prepare('SELECT * from skills where id = ?', [$param1, $param2 ...]); // tableau de params
	
    private static $instance = null;
    private $id;
    private function __construct(){}

    public static function getInstance(){
        return is_null(self::$instance) ? self::getPDOConnection() : self::$instance;
    }

    public function clone(){}

    private function getPDOConnection(){
    	
    	$config = Config::getGenConf();
    	
        // Display PDO Errors when dev
    	if($config['ENV'] === 'dev'){
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_CASE => PDO::CASE_NATURAL,
                PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING
            ];
        }else{
            $options = [];
        }

        $dsn = 'mysql:dbname='.$config['DBNAME'].';host='.$config['DBHOST'];
        $user = $config['DBUSER'];
        $password = $config['DBPASS'];
        $pdoConnection = new PDO($dsn, $user, $password, $options);

        return $pdoConnection;
    }

    public static function query($query){
        try{
            $pdo = self::getInstance();
        }catch(Exception $e){
            echo $e->getMessage();
        }
        $statement = $pdo->query($query);
        // Result as array: FETCH_ALL, array: FETCH_BOTH, Object: FETCH_OBJ or FETCH_CLASS
        $data = $statement->fetchAll(PDO::FETCH_OBJ);
        return $data;
    }

    public static function queryClass($query, $class){
        try{
            $pdo = self::getInstance();
        }catch(Exception $e){
            echo $e->getMessage();
        }
        $statement = $pdo->query($query);
        // Result as array: FETCH_ALL, array: FETCH_BOTH, Object: FETCH_OBJ or FETCH_CLASS
        $oClass = $statement->fetchAll(PDO::FETCH_CLASS, $class);
        return $oClass;
    }

    /**
     * 
     * @param String $request = SQL request
     * @param array $parameters prepared request avoid SQL injections
     * @param mixed $class = class we want to implement automatically
     * @param boolean $one = number of record in return
     * @return array of classe one or several record(s)
     */
    public static function prepare(String $request, array $parameters, $class, $one = false){
    	$statement = self::getInstance()->prepare($request, $parameters);
    	$statement->execute($parameters);
    	$statement->setFetchMode(PDO::FETCH_CLASS, $class);
    	if($one){
    		$datas = $statement->fetch();
    	}else{
    		$datas = $statement->fetchAll();
    	}
    	return $datas;
    }
    
}