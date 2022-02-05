<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_support_attachments.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_support_attachments object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_support_attachments = new Ha_Support_Attachments($db);

$ha_support_attachments->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_support_attachments->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read ha_support_attachments will be here

// query ha_support_attachments
$stmt = $ha_support_attachments->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_support_attachments array
    $ha_support_attachments_arr=array();
	$ha_support_attachments_arr["pageno"]=$ha_support_attachments->pageNo;
	$ha_support_attachments_arr["pagesize"]=$ha_support_attachments->no_of_records_per_page;
    $ha_support_attachments_arr["total_count"]=$ha_support_attachments->total_record_count();
    $ha_support_attachments_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_support_attachments_item=array(
            
"id" => $id,
"support_message_id" => $support_message_id,
"attachment" => html_entity_decode($attachment),
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_support_attachments_arr["records"], $ha_support_attachments_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_support_attachments data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_support_attachments found","data"=> $ha_support_attachments_arr));
    
}else{
 // no ha_support_attachments found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_support_attachments found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_support_attachments found.","data"=> ""));
    
}
 


