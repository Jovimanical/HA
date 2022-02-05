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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_support_tickets.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_support_tickets object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_support_tickets = new Ha_Support_Tickets($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$ha_support_tickets->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_support_tickets->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_support_tickets
$stmt = $ha_support_tickets->search($searchKey);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_support_tickets array
    $ha_support_tickets_arr=array();
	$ha_support_tickets_arr["pageno"]=$ha_support_tickets->pageNo;
	$ha_support_tickets_arr["pagesize"]=$ha_support_tickets->no_of_records_per_page;
    $ha_support_tickets_arr["total_count"]=$ha_support_tickets->total_record_count();
    $ha_support_tickets_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_support_tickets_item=array(
            
"id" => $id,
"user_id" => $user_id,
"seller_id" => $seller_id,
"name" => $name,
"email" => $email,
"ticket" => $ticket,
"subject" => html_entity_decode($subject),
"status" => $status,
"priority" => $priority,
"last_reply" => $last_reply,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_support_tickets_arr["records"], $ha_support_tickets_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_support_tickets data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_support_tickets found","data"=> $ha_support_tickets_arr));
    
}else{
 // no ha_support_tickets found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_support_tickets found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_support_tickets found.","data"=> ""));
    
}
 


