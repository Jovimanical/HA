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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_product_reviews.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_product_reviews object
$ha_product_reviews = new Ha_Product_Reviews($db);
 
// get id of ha_product_reviews to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_product_reviews to be edited
$ha_product_reviews->id = $data->id;

if(!isEmpty($ha_product_reviews->id)){
 
// update the ha_product_reviews
if($ha_product_reviews->update_patch($data)){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_product_reviews, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_product_reviews","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_product_reviews. Data is incomplete.","data"=> ""));
}
?>