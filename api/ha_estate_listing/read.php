<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/ha_estate_listing.php';
 include_once '../token/validatetoken.php';
// instantiate database and ha_estate_listing object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_estate_listing = new Ha_Estate_Listing($db);

$ha_estate_listing->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_estate_listing->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read ha_estate_listing will be here

// query ha_estate_listing
$stmt = $ha_estate_listing->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_estate_listing array
    $ha_estate_listing_arr=array();
	$ha_estate_listing_arr["pageno"]=$ha_estate_listing->pageNo;
	$ha_estate_listing_arr["pagesize"]=$ha_estate_listing->no_of_records_per_page;
    $ha_estate_listing_arr["total_count"]=$ha_estate_listing->total_record_count();
    $ha_estate_listing_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_estate_listing_item=array(
            
"id" => $id,
"EntityParent" => $EntityParent,
"LinkedEntity" => $LinkedEntity,
"PropertyFloor" => $PropertyFloor,
"PropertyId" => $PropertyId,
"PropertyName" => html_entity_decode($PropertyName),
"PropertyAmount" => $PropertyAmount,
"PropertyJson" => $PropertyJson,
"PropertyType" => $PropertyType,
"PropertyStatus" => $PropertyStatus,
"userid" => $userid,
"createdAt" => $createdAt
        );
 
        array_push($ha_estate_listing_arr["records"], $ha_estate_listing_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_estate_listing data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_estate_listing found","document"=> $ha_estate_listing_arr));
    
}else{
 // no ha_estate_listing found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_estate_listing found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_estate_listing found.","document"=> ""));
    
}
 


