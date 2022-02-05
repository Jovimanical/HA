<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/helper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_kyc_documents.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_kyc_documents object
$ha_kyc_documents = new Ha_Kyc_Documents($db);
 
// set ID property of record to read
$ha_kyc_documents->ERROR_NOPRIMARYKEYFOUND = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_kyc_documents to be edited
$ha_kyc_documents->readOne();
 
if($ha_kyc_documents->ERROR_NOPRIMARYKEYFOUND!=null){
    // create array
    $ha_kyc_documents_arr = array(
        
"id" => $ha_kyc_documents->id,
"kyc_id" => html_entity_decode($ha_kyc_documents->kyc_id),
"file_name" => html_entity_decode($ha_kyc_documents->file_name),
"url" => html_entity_decode($ha_kyc_documents->url),
"created_at" => $ha_kyc_documents->created_at,
"updated_at" => $ha_kyc_documents->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_kyc_documents found","data"=> $ha_kyc_documents_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_kyc_documents does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_kyc_documents does not exist.","data"=> ""));
}
?>
