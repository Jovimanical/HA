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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_admin_notifications.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_admin_notifications object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_admin_notifications = new Ha_Admin_Notifications($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$ha_admin_notifications->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_admin_notifications->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_admin_notifications
$stmt = $ha_admin_notifications->search($searchKey);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_admin_notifications array
    $ha_admin_notifications_arr=array();
	$ha_admin_notifications_arr["pageno"]=$ha_admin_notifications->pageNo;
	$ha_admin_notifications_arr["pagesize"]=$ha_admin_notifications->no_of_records_per_page;
    $ha_admin_notifications_arr["total_count"]=$ha_admin_notifications->total_record_count();
    $ha_admin_notifications_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_admin_notifications_item=array(
            
"id" => $id,
"user_id" => $user_id,
"seller_id" => $seller_id,
"title" => html_entity_decode($title),
"read_status" => $read_status,
"click_url" => $click_url,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_admin_notifications_arr["records"], $ha_admin_notifications_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_admin_notifications data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_admin_notifications found","data"=> $ha_admin_notifications_arr));
    
}else{
 // no ha_admin_notifications found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_admin_notifications found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_admin_notifications found.","data"=> ""));
    
}
 


