<?php 
declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); //E_ALL

require_once(__DIR__.'/vendor/autoload.php');
use src\classes\exception\ErrorHandler;
use Curl\Curl;
use src\classes\controller\TaskController;
use src\classes\DB\DB;
use src\classes\entity\Task;

// set_error_handler("ErrorHandler::handleError");
// set_exception_handler("src\classes\exception\ErrorHandler::handleException");

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$parts = explode("/", $path);
$resource = $parts[3];
$id = $parts[4] ?? null;

if($resource != "tasks"){
// 	header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
	http_response_code(404);
	exit;
}

// echo $resource, ", ", $id, ' ';
// echo $_SERVER["REQUEST_METHOD"];
header("Content-type: application/json; charset=UTF-8");

// $instance = DB::getInstance();
// $db = new DB();
$task = new Task();
$controller = new TaskController($task);

try{
	$r = $controller->processRequest($_SERVER['REQUEST_METHOD'], $id);
}catch(Exception $exception){
// 	ErrorHandler::handleException($e);
	echo json_encode([
			"code" => $exception->getCode(),
			"message" => $exception->getMessage(),
			"file" => $exception->getFile(),
			"line" => $exception->getLine()
	]);
}



