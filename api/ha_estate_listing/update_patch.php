<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/ha_estate_listing.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_estate_listing object
$ha_estate_listing = new Ha_Estate_Listing($db);
 
// get id of ha_estate_listing to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_estate_listing to be edited
$ha_estate_listing->id = $data->id;

if(!isEmpty($ha_estate_listing->id)){
 
// update the ha_estate_listing
if($ha_estate_listing->update_patch($data)){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
 
// if unable to update the ha_estate_listing, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_estate_listing","document"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_estate_listing. Data is incomplete.","document"=> ""));
}
?>
