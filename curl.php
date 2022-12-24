<?php 
declare(strict_types=1);

require_once(__DIR__.'/vendor/autoload.php');
use Curl\Curl;

// $curl = new Curl();
// $curl->post('https://localhost/Api/tasks', [
// 		'name' => 'myusername',
// 		'priority' => 5,
// ]);
// if ($curl->isSuccess()) {
// 	// do something with response
// 	var_dump($curl->response);
// }


$curl = new Curl();
// $curl->setBasicAuthentication('username', 'password');
// $curl->setUserAgent('');
// $curl->setHeader('X-Requested-With', 'XMLHttpRequest');
// $curl->setCookie('key', 'value');
$curl->get('https://localhost/Api/tasks');

if ($curl->error) {
	echo $curl->error_code;
} else {
	echo $curl->response;
}

var_dump($curl->request_headers);
var_dump($curl->response_headers);



// ensure to close the curl connection
$curl->close();


