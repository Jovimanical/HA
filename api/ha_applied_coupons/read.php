<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_applied_coupons.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_applied_coupons object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_applied_coupons = new Ha_Applied_Coupons($db);

$ha_applied_coupons->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_applied_coupons->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read ha_applied_coupons will be here

// query ha_applied_coupons
$stmt = $ha_applied_coupons->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_applied_coupons array
    $ha_applied_coupons_arr=array();
	$ha_applied_coupons_arr["pageno"]=$ha_applied_coupons->pageNo;
	$ha_applied_coupons_arr["pagesize"]=$ha_applied_coupons->no_of_records_per_page;
    $ha_applied_coupons_arr["total_count"]=$ha_applied_coupons->total_record_count();
    $ha_applied_coupons_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_applied_coupons_item=array(
            
"id" => $id,
"user_id" => $user_id,
"coupon_id" => $coupon_id,
"order_id" => $order_id,
"amount" => $amount,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_applied_coupons_arr["records"], $ha_applied_coupons_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_applied_coupons data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_applied_coupons found","data"=> $ha_applied_coupons_arr));
    
}else{
 // no ha_applied_coupons found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_applied_coupons found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_applied_coupons found.","data"=> ""));
    
}
 


