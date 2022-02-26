<?php
// required headers
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
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

// set ID property of record to read
$ha_kyc_documents->ERROR_NOPRIMARYKEYFOUND = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of ha_kyc_documents to be edited
$ha_kyc_documents->readOne();

if ($ha_kyc_documents->ERROR_NOPRIMARYKEYFOUND != null) {
    // create array
    $ha_kyc_documents_arr = array(

        "id" => $ha_kyc_documents->id,
        "user_id" => $ha_kyc_documents->user_id,
        "file_name" => html_entity_decode($ha_kyc_documents->file_name),
        "file_url" => $ha_kyc_documents->file_url,
        "file_status" => $ha_kyc_documents->file_status,
        "follow_up" => $ha_kyc_documents->follow_up,
        "provider" => $ha_kyc_documents->provider,
        "created_at" => $ha_kyc_documents->created_at,
        "updated_at" => $ha_kyc_documents->updated_at
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_kyc_documents found", "data" => $ha_kyc_documents_arr));
} else {
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user ha_kyc_documents does not exist
    echo json_encode(array("status" => "error", "code" => 0, "message" => "ha_kyc_documents does not exist.", "data" => ""));
}
?>
