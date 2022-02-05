<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_support_messages.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_support_messages object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_support_messages = new Ha_Support_Messages($db);

$ha_support_messages->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_support_messages->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read ha_support_messages will be here

// query ha_support_messages
$stmt = $ha_support_messages->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_support_messages array
    $ha_support_messages_arr=array();
	$ha_support_messages_arr["pageno"]=$ha_support_messages->pageNo;
	$ha_support_messages_arr["pagesize"]=$ha_support_messages->no_of_records_per_page;
    $ha_support_messages_arr["total_count"]=$ha_support_messages->total_record_count();
    $ha_support_messages_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_support_messages_item=array(
            
"id" => $id,
"supportticket_id" => $supportticket_id,
"admin_id" => $admin_id,
"message" => $message,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_support_messages_arr["records"], $ha_support_messages_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_support_messages data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_support_messages found","data"=> $ha_support_messages_arr));
    
}else{
 // no ha_support_messages found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_support_messages found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_support_messages found.","data"=> ""));
    
}
 


