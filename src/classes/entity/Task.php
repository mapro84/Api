<?php
namespace src\classes\entity;

use src\classes\DB\DB;
use \PDO;

class Task{
	
	private $PdoConnection;
	
	public function __construct(){ //__construct(private $PdoConnection) in php8
		$this->PdoConnection = DB::getInstance();
	}
	
	public function get(string $id) { // array | false in php8
		$sql = "SELECT * FROM task WHERE id= :id";
		$statement = $this->PdoConnection->prepare($sql);
		$statement->bindValue(":id", $id, PDO::PARAM_INT);
		$statement->execute();
		$data = $statement->fetch(PDO::FETCH_ASSOC);
		
		if($data !== false){
			$data['is_completed'] = (bool) $data['is_completed'] ;
		}
		
		return $data;
	}
	
	public function getAll(): array {
		$sql = "SELECT * FROM task ORDER BY name";
		$statement = $this->PdoConnection->query($sql);
		$data = [];
		$data = $statement->fetchAll(PDO::FETCH_ASSOC);
		for($i=0;$i<count($data);$i++){
			$data[$i]['is_completed'] = (bool) $data[$i]['is_completed'];
		}
		
		return $data;
	}
	
	public function create(array $data): string {
		$sql = "INSERT INTO task (name, priority, is_completed)
                VALUES (:name, :priority, :is_completed)";
		$statement = $this->PdoConnection->prepare($sql);
		$statement->bindValue(":name", $data["name"], PDO::PARAM_STR);
		if(empty($data["priority"])){
			$statement->bindValue(":priority", null, PDO::PARAM_NULL);
		}else{
			$statement->bindValue(":priority", $data["priority"], PDO::PARAM_INT);
		}
		$statement->bindValue(":is_completed", $data["is_completed"] ?? false, PDO::PARAM_BOOL);
		$statement->execute();
        
		return $this->PdoConnection->lastInsertId();
	}
	
}