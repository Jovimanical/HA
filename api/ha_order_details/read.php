<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_order_details.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_order_details object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_order_details = new Ha_Order_Details($db);

$ha_order_details->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_order_details->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read ha_order_details will be here

// query ha_order_details
$stmt = $ha_order_details->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_order_details array
    $ha_order_details_arr=array();
	$ha_order_details_arr["pageno"]=$ha_order_details->pageNo;
	$ha_order_details_arr["pagesize"]=$ha_order_details->no_of_records_per_page;
    $ha_order_details_arr["total_count"]=$ha_order_details->total_record_count();
    $ha_order_details_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_order_details_item=array(
            
"id" => $id,
"seller_id" => $seller_id,
"order_id" => $order_id,
"product_id" => $product_id,
"quantity" => $quantity,
"base_price" => $base_price,
"total_price" => $total_price,
"details" => $details,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_order_details_arr["records"], $ha_order_details_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_order_details data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_order_details found","data"=> $ha_order_details_arr));
    
}else{
 // no ha_order_details found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_order_details found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_order_details found.","data"=> ""));
    
}
 


