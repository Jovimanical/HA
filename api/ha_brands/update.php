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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_brands.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_brands object
$ha_brands = new Ha_Brands($db);
 
// get id of ha_brands to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_brands to be edited
$ha_brands->id = $data->id;

if(
!isEmpty($data->name)
&&!isEmpty($data->top)
){
// set ha_brands property values

if(!isEmpty($data->name)) { 
$ha_brands->name = $data->name;
} else { 
$ha_brands->name = '';
}
$ha_brands->logo = $data->logo;
if(!isEmpty($data->top)) { 
$ha_brands->top = $data->top;
} else { 
$ha_brands->top = '0';
}
$ha_brands->meta_title = $data->meta_title;
$ha_brands->meta_description = $data->meta_description;
$ha_brands->meta_keywords = $data->meta_keywords;
$ha_brands->created_at = $data->created_at;
$ha_brands->updated_at = $data->updated_at;
$ha_brands->deleted_at = $data->deleted_at;
 
// update the ha_brands
if($ha_brands->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_brands, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_brands","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_brands. Data is incomplete.","data"=> ""));
}
?>
