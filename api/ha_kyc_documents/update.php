<?php
// required headers
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Methods: POST,GET, OPTIONS');
    header("Access-Control-Expose-Headers: Content-Length, X-JSON");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header('Access-Control-Allow-Credentials: true');
}
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
            header('Access-Control-Allow-Credentials: true');
            header("HTTP/1.1 200 OK");
            return;
        }
    }
}

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
 
// get id of ha_kyc_documents to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_kyc_documents to be edited
$ha_kyc_documents->ERROR_NOPRIMARYKEYFOUND = $data->ERROR_NOPRIMARYKEYFOUND;

if(
!isEmpty($data->id)
&&!isEmpty($data->user_id)
&&!isEmpty($data->file_name)
&&!isEmpty($data->file_url)
){
// set ha_kyc_documents property values

if(!isEmpty($data->id)) { 
$ha_kyc_documents->id = $data->id;
} else { 
$ha_kyc_documents->id = '';
}
if(!isEmpty($data->user_id)) { 
$ha_kyc_documents->user_id = $data->user_id;
} else { 
$ha_kyc_documents->user_id = '';
}
if(!isEmpty($data->file_name)) { 
$ha_kyc_documents->file_name = $data->file_name;
} else { 
$ha_kyc_documents->file_name = '';
}
if(!isEmpty($data->file_url)) { 
$ha_kyc_documents->file_url = $data->file_url;
} else { 
$ha_kyc_documents->file_url = '';
}
$ha_kyc_documents->file_status = $data->file_status;
$ha_kyc_documents->follow_up = $data->follow_up;
$ha_kyc_documents->provider = $data->provider;
$ha_kyc_documents->updated_at = $data->updated_at;
 
// update the ha_kyc_documents
if($ha_kyc_documents->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
 
// if unable to update the ha_kyc_documents, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_kyc_documents","document"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_kyc_documents. Data is incomplete.","document"=> ""));
}
?>
