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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_carts.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_carts object
$ha_carts = new Ha_Carts($db);
 
// get id of ha_carts to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_carts to be edited
$ha_carts->id = $data->id;

if(
!isEmpty($data->product_id)
&&!isEmpty($data->quantity)
){
// set ha_carts property values

$ha_carts->user_id = $data->user_id;
$ha_carts->session_id = $data->session_id;
if(!isEmpty($data->product_id)) { 
$ha_carts->product_id = $data->product_id;
} else { 
$ha_carts->product_id = '';
}
$ha_carts->attributes = $data->attributes;
if(!isEmpty($data->quantity)) { 
$ha_carts->quantity = $data->quantity;
} else { 
$ha_carts->quantity = '';
}
$ha_carts->created_at = $data->created_at;
$ha_carts->updated_at = $data->updated_at;
 
// update the ha_carts
if($ha_carts->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_carts, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_carts","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_carts. Data is incomplete.","data"=> ""));
}
?>
