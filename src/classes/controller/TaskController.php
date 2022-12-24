<?php 
namespace src\classes\controller;

use src\classes\entity\Task;

class TaskController{
	
	private $task;
	
	public function __construct(Task $task){
		$this->task = $task;
	}
	
	public function processRequest(string $method, ?string $id): void {
		
		if($id === null){
			if($method == "GET"){
				
				echo json_encode($this->task->getAll());
				
			}elseif($method == "POST"){
// 				$data = (array) json_decode(file_get_contents("php://input"), true);
			    $data = array('name'=>'','priority'=>'rr');
			    $errors = $this->getValidationErrors($data);
			    var_dump($errors);die();
			    if(!empty($errors)){
			    	$this->respondUnprocessableEntity($errors);
			    	return;
			    }
			    $id = $this->task->create($data);
			    $this->respondCreated($id);
			    
			}else{
				$this->respondMethodNotAllowed("GET, POST");
			}
		}else{
			
			$task = $this->task->get($id);
			if($task === false){
				$this->respondNotFound($id);
				return;
			}
			
			switch($method){
				case "GET":
					echo json_encode($task);
					break;
					
				case "PATCH":
					echo "update $id";
					break;
					
				case "DELETE":
					echo "delete $id";
					break;
					
				default: $this->respondMethodNotAllowed("GET, PATCH, DELETE");
					
			}
		}
		
	}
	
	private function respondUnprocessableEntity(array $errors): void{
		http_response_code(422);
		echo json_encode(["errors" => $errors]);
	}
	
	private function respondMethodNotAllowed(string $allowed_methods): void{
		http_response_code(405);
		header("Allow: $allowed_methods");
	}
	
	private function respondNotFound(string $id): void{
		http_response_code(404);
		echo json_encode(["message" => "Task with id $id not found"]);
	}
	
	private function respondCreated(string $id): void{
		http_response_code(201);
		echo json_encode(["message" => "Task created", "id" => $id]);
	}
	
	private function getValidationErrors(array $data): array{
		$errors = [];
		
		if(empty($data["name"])){
			$errors[] = "Name is required";
		}
		if(!empty($data["priority"])){
			if(filter_var($data["priority"], FILTER_VALIDATE_INT) === false){
				echo 'la';
				$errors[] = "priority must be an integer";
			}
		}
		return $errors;
	}
}