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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_offers.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_offers object
$ha_offers = new Ha_Offers($db);
 
// get id of ha_offers to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_offers to be edited
$ha_offers->id = $data->id;

if(
!isEmpty($data->name)
&&!isEmpty($data->discount_type)
&&!isEmpty($data->amount)
&&!isEmpty($data->status)
){
// set ha_offers property values

if(!isEmpty($data->name)) { 
$ha_offers->name = $data->name;
} else { 
$ha_offers->name = '';
}
if(!isEmpty($data->discount_type)) { 
$ha_offers->discount_type = $data->discount_type;
} else { 
$ha_offers->discount_type = '';
}
if(!isEmpty($data->amount)) { 
$ha_offers->amount = $data->amount;
} else { 
$ha_offers->amount = '0';
}
$ha_offers->start_date = $data->start_date;
$ha_offers->end_date = $data->end_date;
if(!isEmpty($data->status)) { 
$ha_offers->status = $data->status;
} else { 
$ha_offers->status = '1';
}
$ha_offers->created_at = $data->created_at;
$ha_offers->updated_at = $data->updated_at;
 
// update the ha_offers
if($ha_offers->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_offers, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_offers","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_offers. Data is incomplete.","data"=> ""));
}
?>
