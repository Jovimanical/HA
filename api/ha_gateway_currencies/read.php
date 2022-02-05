<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_gateway_currencies.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_gateway_currencies object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_gateway_currencies = new Ha_Gateway_Currencies($db);

$ha_gateway_currencies->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_gateway_currencies->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read ha_gateway_currencies will be here

// query ha_gateway_currencies
$stmt = $ha_gateway_currencies->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_gateway_currencies array
    $ha_gateway_currencies_arr=array();
	$ha_gateway_currencies_arr["pageno"]=$ha_gateway_currencies->pageNo;
	$ha_gateway_currencies_arr["pagesize"]=$ha_gateway_currencies->no_of_records_per_page;
    $ha_gateway_currencies_arr["total_count"]=$ha_gateway_currencies->total_record_count();
    $ha_gateway_currencies_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_gateway_currencies_item=array(
            
"id" => $id,
"name" => $name,
"currency" => $currency,
"symbol" => $symbol,
"method_code" => $method_code,
"gateway_alias" => $gateway_alias,
"min_amount" => $min_amount,
"max_amount" => $max_amount,
"percent_charge" => $percent_charge,
"fixed_charge" => $fixed_charge,
"rate" => $rate,
"image" => html_entity_decode($image),
"gateway_parameter" => $gateway_parameter,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_gateway_currencies_arr["records"], $ha_gateway_currencies_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_gateway_currencies data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_gateway_currencies found","data"=> $ha_gateway_currencies_arr));
    
}else{
 // no ha_gateway_currencies found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_gateway_currencies found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_gateway_currencies found.","data"=> ""));
    
}
 


