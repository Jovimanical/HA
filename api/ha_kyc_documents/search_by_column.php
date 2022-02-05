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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_kyc_documents.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_kyc_documents object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_kyc_documents = new Ha_Kyc_Documents($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$ha_kyc_documents->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_kyc_documents->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_kyc_documents
$stmt = $ha_kyc_documents->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_kyc_documents array
    $ha_kyc_documents_arr=array();
	$ha_kyc_documents_arr["pageno"]=$ha_kyc_documents->pageNo;
	$ha_kyc_documents_arr["pagesize"]=$ha_kyc_documents->no_of_records_per_page;
    $ha_kyc_documents_arr["total_count"]=$ha_kyc_documents->search_record_count($data,$orAnd);
    $ha_kyc_documents_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_kyc_documents_item=array(
            
"id" => $id,
"kyc_id" => html_entity_decode($kyc_id),
"file_name" => html_entity_decode($file_name),
"url" => html_entity_decode($url),
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_kyc_documents_arr["records"], $ha_kyc_documents_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_kyc_documents data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_kyc_documents found","data"=> $ha_kyc_documents_arr));
    
}else{
 // no ha_kyc_documents found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_kyc_documents found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_kyc_documents found.","data"=> ""));
    
}
 


