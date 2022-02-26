<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/ha_estate_listing.php';
 include_once '../token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_estate_listing object
$ha_estate_listing = new Ha_Estate_Listing($db);
 
// set ID property of record to read
$ha_estate_listing->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_estate_listing to be edited
$ha_estate_listing->readOne();
 
if($ha_estate_listing->id!=null){
    // create array
    $ha_estate_listing_arr = array(
        
"id" => $ha_estate_listing->id,
"EntityParent" => $ha_estate_listing->EntityParent,
"LinkedEntity" => $ha_estate_listing->LinkedEntity,
"PropertyFloor" => $ha_estate_listing->PropertyFloor,
"PropertyId" => $ha_estate_listing->PropertyId,
"PropertyName" => html_entity_decode($ha_estate_listing->PropertyName),
"PropertyAmount" => $ha_estate_listing->PropertyAmount,
"PropertyJson" => $ha_estate_listing->PropertyJson,
"PropertyType" => $ha_estate_listing->PropertyType,
"PropertyStatus" => $ha_estate_listing->PropertyStatus,
"userid" => $ha_estate_listing->userid,
"createdAt" => $ha_estate_listing->createdAt
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_estate_listing found","document"=> $ha_estate_listing_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_estate_listing does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_estate_listing does not exist.","document"=> ""));
}
?>
