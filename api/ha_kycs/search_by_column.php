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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_kycs.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_kycs object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_kycs = new Ha_Kycs($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$ha_kycs->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_kycs->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_kycs
$stmt = $ha_kycs->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_kycs array
    $ha_kycs_arr=array();
	$ha_kycs_arr["pageno"]=$ha_kycs->pageNo;
	$ha_kycs_arr["pagesize"]=$ha_kycs->no_of_records_per_page;
    $ha_kycs_arr["total_count"]=$ha_kycs->search_record_count($data,$orAnd);
    $ha_kycs_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_kycs_item=array(
            
"id" => $id,
"first_name" => html_entity_decode($first_name),
"last_name" => html_entity_decode($last_name),
"city" => html_entity_decode($city),
"country" => html_entity_decode($country),
"city_of_birth" => html_entity_decode($city_of_birth),
"country_of_birth" => html_entity_decode($country_of_birth),
"nationality" => html_entity_decode($nationality),
"document_type" => $document_type,
"document_number" => html_entity_decode($document_number),
"issuing_authority" => html_entity_decode($issuing_authority),
"issue_on" => $issue_on,
"valid_until" => $valid_until,
"order_amount" => $order_amount,
"internal" => $internal,
"external" => $external,
"follow_up" => $follow_up,
"comment" => $comment,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_kycs_arr["records"], $ha_kycs_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_kycs data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_kycs found","data"=> $ha_kycs_arr));
    
}else{
 // no ha_kycs found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_kycs found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_kycs found.","data"=> ""));
    
}
 


