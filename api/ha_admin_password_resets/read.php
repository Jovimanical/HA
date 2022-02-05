<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_admin_password_resets.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_admin_password_resets object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_admin_password_resets = new Ha_Admin_Password_Resets($db);

$ha_admin_password_resets->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_admin_password_resets->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read ha_admin_password_resets will be here

// query ha_admin_password_resets
$stmt = $ha_admin_password_resets->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_admin_password_resets array
    $ha_admin_password_resets_arr=array();
	$ha_admin_password_resets_arr["pageno"]=$ha_admin_password_resets->pageNo;
	$ha_admin_password_resets_arr["pagesize"]=$ha_admin_password_resets->no_of_records_per_page;
    $ha_admin_password_resets_arr["total_count"]=$ha_admin_password_resets->total_record_count();
    $ha_admin_password_resets_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_admin_password_resets_item=array(
            
"id" => $id,
"email" => $email,
"token" => $token,
"status" => $status,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_admin_password_resets_arr["records"], $ha_admin_password_resets_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_admin_password_resets data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_admin_password_resets found","data"=> $ha_admin_password_resets_arr));
    
}else{
 // no ha_admin_password_resets found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_admin_password_resets found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_admin_password_resets found.","data"=> ""));
    
}
 


