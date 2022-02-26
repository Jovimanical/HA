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
 
// get ha_estate_listing id
$data = json_decode(file_get_contents("php://input"));
 
// set ha_estate_listing id to be deleted
$ha_estate_listing->id = $data->id;
 
// delete the ha_estate_listing
if($ha_estate_listing->delete()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Ha_Estate_Listing was deleted","document"=> ""));
    
}
 
// if unable to delete the ha_estate_listing
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to delete ha_estate_listing.","document"=> ""));
}
?>
