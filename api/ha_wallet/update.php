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
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_wallet object
$ha_wallet = new Ha_Wallet($db);
 
// get id of ha_wallet to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_wallet to be edited
$ha_wallet->id = $data->id;

if(
!isEmpty($data->users_id)
&&!isEmpty($data->flag)
&&!isEmpty($data->amount)
&&!isEmpty($data->description)
){
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
 
// update the ha_wallet
if($ha_wallet->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_wallet, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_wallet","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_wallet. Data is incomplete.","data"=> ""));
}
?>
