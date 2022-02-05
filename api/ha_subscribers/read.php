<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_subscribers.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_subscribers object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_subscribers = new Ha_Subscribers($db);

$ha_subscribers->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_subscribers->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read ha_subscribers will be here

// query ha_subscribers
$stmt = $ha_subscribers->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_subscribers array
    $ha_subscribers_arr=array();
	$ha_subscribers_arr["pageno"]=$ha_subscribers->pageNo;
	$ha_subscribers_arr["pagesize"]=$ha_subscribers->no_of_records_per_page;
    $ha_subscribers_arr["total_count"]=$ha_subscribers->total_record_count();
    $ha_subscribers_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_subscribers_item=array(
            
"id" => $id,
"email" => $email,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_subscribers_arr["records"], $ha_subscribers_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_subscribers data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_subscribers found","data"=> $ha_subscribers_arr));
    
}else{
 // no ha_subscribers found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_subscribers found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_subscribers found.","data"=> ""));
    
}
 


