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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_wishlists.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_wishlists object
$ha_wishlists = new Ha_Wishlists($db);
 
// get id of ha_wishlists to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_wishlists to be edited
$ha_wishlists->id = $data->id;

if(
!isEmpty($data->product_id)
){
// set ha_wishlists property values

$ha_wishlists->user_id = $data->user_id;
$ha_wishlists->session_id = $data->session_id;
if(!isEmpty($data->product_id)) { 
$ha_wishlists->product_id = $data->product_id;
} else { 
$ha_wishlists->product_id = '';
}
$ha_wishlists->created_at = $data->created_at;
$ha_wishlists->updated_at = $data->updated_at;
 
// update the ha_wishlists
if($ha_wishlists->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_wishlists, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_wishlists","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_wishlists. Data is incomplete.","data"=> ""));
}
?>
