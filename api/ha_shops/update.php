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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_shops.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_shops object
$ha_shops = new Ha_Shops($db);
 
// get id of ha_shops to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_shops to be edited
$ha_shops->id = $data->id;

if(
!isEmpty($data->seller_id)
){
// set ha_shops property values

if(!isEmpty($data->seller_id)) { 
$ha_shops->seller_id = $data->seller_id;
} else { 
$ha_shops->seller_id = '';
}
$ha_shops->name = $data->name;
$ha_shops->phone = $data->phone;
$ha_shops->logo = $data->logo;
$ha_shops->cover = $data->cover;
$ha_shops->opens_at = $data->opens_at;
$ha_shops->closed_at = $data->closed_at;
$ha_shops->address = $data->address;
$ha_shops->social_links = $data->social_links;
$ha_shops->meta_title = $data->meta_title;
$ha_shops->meta_description = $data->meta_description;
$ha_shops->meta_keywords = $data->meta_keywords;
$ha_shops->created_at = $data->created_at;
$ha_shops->updated_at = $data->updated_at;
 
// update the ha_shops
if($ha_shops->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_shops, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_shops","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_shops. Data is incomplete.","data"=> ""));
}
?>
