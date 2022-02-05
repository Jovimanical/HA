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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_transactions.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_transactions object
$ha_transactions = new Ha_Transactions($db);
 
// get id of ha_transactions to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_transactions to be edited
$ha_transactions->id = $data->id;

if(
!isEmpty($data->amount)
&&!isEmpty($data->charge)
&&!isEmpty($data->post_balance)
){
// set ha_transactions property values

$ha_transactions->user_id = $data->user_id;
$ha_transactions->seller_id = $data->seller_id;
if(!isEmpty($data->amount)) { 
$ha_transactions->amount = $data->amount;
} else { 
$ha_transactions->amount = '0.00000000';
}
if(!isEmpty($data->charge)) { 
$ha_transactions->charge = $data->charge;
} else { 
$ha_transactions->charge = '0.00000000';
}
if(!isEmpty($data->post_balance)) { 
$ha_transactions->post_balance = $data->post_balance;
} else { 
$ha_transactions->post_balance = '0.00000000';
}
$ha_transactions->trx_type = $data->trx_type;
$ha_transactions->trx = $data->trx;
$ha_transactions->details = $data->details;
$ha_transactions->created_at = $data->created_at;
$ha_transactions->updated_at = $data->updated_at;
 
// update the ha_transactions
if($ha_transactions->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_transactions, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_transactions","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_transactions. Data is incomplete.","data"=> ""));
}
?>
