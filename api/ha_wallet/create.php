<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();


include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/helper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_wallet.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();
 
$ha_wallet = new Ha_Wallet($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->users_id)
&&!isEmpty($data->flag)
&&!isEmpty($data->amount)
&&!isEmpty($data->description)){
 
    // set ha_wallet property values
	 
if(!isEmpty($data->users_id)) { 
$ha_wallet->users_id = $data->users_id;
} else { 
$ha_wallet->users_id = '';
}
if(!isEmpty($data->flag)) { 
$ha_wallet->flag = $data->flag;
} else { 
$ha_wallet->flag = '';
}
if(!isEmpty($data->amount)) { 
$ha_wallet->amount = $data->amount;
} else { 
$ha_wallet->amount = '';
}
if(!isEmpty($data->description)) { 
$ha_wallet->description = $data->description;
} else { 
$ha_wallet->description = '';
}
$ha_wallet->updated_at = $data->updated_at;
 	$lastInsertedId=$ha_wallet->create();
    // create the ha_wallet
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> $lastInsertedId));
    }
 
    // if unable to create the ha_wallet, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_wallet","data"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_wallet. Data is incomplete.","data"=> ""));
}
?>
