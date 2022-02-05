<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_withdraw_methods.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_withdraw_methods object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_withdraw_methods = new Ha_Withdraw_Methods($db);

$ha_withdraw_methods->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_withdraw_methods->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read ha_withdraw_methods will be here

// query ha_withdraw_methods
$stmt = $ha_withdraw_methods->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_withdraw_methods array
    $ha_withdraw_methods_arr=array();
	$ha_withdraw_methods_arr["pageno"]=$ha_withdraw_methods->pageNo;
	$ha_withdraw_methods_arr["pagesize"]=$ha_withdraw_methods->no_of_records_per_page;
    $ha_withdraw_methods_arr["total_count"]=$ha_withdraw_methods->total_record_count();
    $ha_withdraw_methods_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_withdraw_methods_item=array(
            
"id" => $id,
"name" => $name,
"image" => html_entity_decode($image),
"min_limit" => $min_limit,
"max_limit" => $max_limit,
"delay" => $delay,
"fixed_charge" => $fixed_charge,
"rate" => $rate,
"percent_charge" => $percent_charge,
"currency" => $currency,
"user_data" => $user_data,
"description" => $description,
"status" => $status,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_withdraw_methods_arr["records"], $ha_withdraw_methods_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_withdraw_methods data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_withdraw_methods found","data"=> $ha_withdraw_methods_arr));
    
}else{
 // no ha_withdraw_methods found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_withdraw_methods found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_withdraw_methods found.","data"=> ""));
    
}
 


