<?php
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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_messages.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_messages object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_messages = new Ha_Messages($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$ha_messages->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_messages->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_messages
$stmt = $ha_messages->search($searchKey);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_messages array
    $ha_messages_arr=array();
	$ha_messages_arr["pageno"]=$ha_messages->pageNo;
	$ha_messages_arr["pagesize"]=$ha_messages->no_of_records_per_page;
    $ha_messages_arr["total_count"]=$ha_messages->total_record_count();
    $ha_messages_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_messages_item=array(
            
"id" => $id,
"user_to" => $user_to,
"user_from" => $user_from,
"subject" => $subject,
"message" => $message,
"respond" => $respond,
"sender_open" => $sender_open,
"receiver_open" => $receiver_open,
"sender_delete" => $sender_delete,
"receiver_delete" => $receiver_delete
        );
 
        array_push($ha_messages_arr["records"], $ha_messages_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_messages data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_messages found","data"=> $ha_messages_arr));
    
}else{
 // no ha_messages found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_messages found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_messages found.","data"=> ""));
    
}
 


