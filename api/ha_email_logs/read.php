<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_email_logs.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_email_logs object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_email_logs = new Ha_Email_Logs($db);

$ha_email_logs->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_email_logs->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read ha_email_logs will be here

// query ha_email_logs
$stmt = $ha_email_logs->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_email_logs array
    $ha_email_logs_arr=array();
	$ha_email_logs_arr["pageno"]=$ha_email_logs->pageNo;
	$ha_email_logs_arr["pagesize"]=$ha_email_logs->no_of_records_per_page;
    $ha_email_logs_arr["total_count"]=$ha_email_logs->total_record_count();
    $ha_email_logs_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_email_logs_item=array(
            
"id" => $id,
"user_id" => $user_id,
"seller_id" => $seller_id,
"mail_sender" => $mail_sender,
"email_from" => $email_from,
"email_to" => $email_to,
"subject" => html_entity_decode($subject),
"message" => $message,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_email_logs_arr["records"], $ha_email_logs_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_email_logs data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_email_logs found","data"=> $ha_email_logs_arr));
    
}else{
 // no ha_email_logs found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_email_logs found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_email_logs found.","data"=> ""));
    
}
 


