<?php 
namespace src\classes\controller;

use src\classes\entity\Task;

class TaskController{
	
	private $task;
	
	public function __construct(Task $task){
		$this->task = $task;
	}
	
	public function processRequest(string $method, ?string $id): void {
		
		if(empty($id)){
			if($method == "GET"){

				echo json_encode($this->task->getAll());

			}elseif($method == "POST"){

				$data = $_POST;
			    $errors = $this->getValidationErrors($data);
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
					$id = $this->task->update($data);
				break;
					
				case "PUT":
					$data = array();
                    parse_str(file_get_contents('php://input'), $data);
                    $errors = $this->getValidationErrors($data);
					if(!empty($errors)){
						$this->respondUnprocessableEntity($errors);
						return;
					}
					$res = $this->task->update($id, $data);
					if($res === false){
						// TODO WHY DOES NOT WORK
					  $this->respondNotUpdated($id);
					}else{
						$this->respondUpdated($id);
					}
				break;

				case "DELETE":
					$res = $this->task->delete($id);
					if($res === false){
						// TODO WHY DOES NOT WORK
					  $this->respondNotDeleted($id);
					}else{
					  $this->respondDeleted($id);
					}
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

	private function respondUpdated(string $id): void{
		http_response_code(201);
		echo json_encode(["message" => "Task updated", "id" => $id]);
	}

	private function respondDeleted(string $id): void{
		http_response_code(201);
		echo json_encode(["message" => "Task deleted", "id" => $id]);
	}

	private function respondNotDeleted(string $id): void{
		http_response_code(500);
		echo json_encode(["message" => "Task with id $id not deleted"]);
	}

	private function respondNotUpdated(string $id): void{
		http_response_code(500);
		echo json_encode(["message" => "Task with id $id not updated"]);
	}

	private function getValidationErrors(array $data): array{
		$errors = [];
		
		if(empty($data["name"])){
			$errors[] = "Name is required";
		}

		if(isset($data["priority"])){
			if(!empty($data["priority"])){
				if(filter_var($data["priority"], FILTER_VALIDATE_INT) === false){
					$errors[] = "priority must be an integer";
				}
			}elseif($data["priority"] === '' || $data["priority"] == NULL){
				$errors[] = "You should set the value priority at 0 or 1";
			}
	    }

		if(isset($data["is_completed"])){
			if(!empty($data["is_completed"])){
				if(filter_var($data["is_completed"], FILTER_VALIDATE_INT) === false){
					$errors[] = "is_completed must be an integer 0 or 1";
				}
			}elseif($data["is_completed"] === ''){
				$errors[] = "You should set the value is_completed at 0 or 1";
			}
        }

		return $errors;
		
	}
}