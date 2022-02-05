<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_gateways.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_gateways object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_gateways = new Ha_Gateways($db);

$ha_gateways->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_gateways->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read ha_gateways will be here

// query ha_gateways
$stmt = $ha_gateways->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_gateways array
    $ha_gateways_arr=array();
	$ha_gateways_arr["pageno"]=$ha_gateways->pageNo;
	$ha_gateways_arr["pagesize"]=$ha_gateways->no_of_records_per_page;
    $ha_gateways_arr["total_count"]=$ha_gateways->total_record_count();
    $ha_gateways_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_gateways_item=array(
            
"id" => $id,
"code" => $code,
"name" => $name,
"alias" => $alias,
"image" => html_entity_decode($image),
"status" => $status,
"gateway_parameters" => $gateway_parameters,
"supported_currencies" => $supported_currencies,
"crypto" => $crypto,
"extra" => $extra,
"description" => $description,
"input_form" => $input_form,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_gateways_arr["records"], $ha_gateways_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_gateways data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_gateways found","data"=> $ha_gateways_arr));
    
}else{
 // no ha_gateways found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_gateways found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_gateways found.","data"=> ""));
    
}
 


