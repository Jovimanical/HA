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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_offers_products.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_offers_products object
$ha_offers_products = new Ha_Offers_Products($db);
 
// get id of ha_offers_products to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_offers_products to be edited
$ha_offers_products->id = $data->id;

if(
!isEmpty($data->offer_id)
&&!isEmpty($data->product_id)
){
// set ha_offers_products property values

if(!isEmpty($data->offer_id)) { 
$ha_offers_products->offer_id = $data->offer_id;
} else { 
$ha_offers_products->offer_id = '';
}
if(!isEmpty($data->product_id)) { 
$ha_offers_products->product_id = $data->product_id;
} else { 
$ha_offers_products->product_id = '';
}
 
// update the ha_offers_products
if($ha_offers_products->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_offers_products, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_offers_products","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_offers_products. Data is incomplete.","data"=> ""));
}
?>
