<?php
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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_kyc_employment_status.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_kyc_employment_status object
$database = new Database();
$db = $database->getConnection();

// initialize object
$ha_kyc_employment_status = new Ha_Kyc_Employment_Status($db);

$data =  (json_decode(file_get_contents("php://input"), true ) === NULL ) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));

$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$ha_kyc_employment_status->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_kyc_employment_status->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_kyc_employment_status
$stmt = $ha_kyc_employment_status->searchByColumn($data, $orAnd);

$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {

    //ha_kyc_employment_status array
    $ha_kyc_employment_status_arr = array();
    $ha_kyc_employment_status_arr["pageno"] = $ha_kyc_employment_status->pageNo;
    $ha_kyc_employment_status_arr["pagesize"] = $ha_kyc_employment_status->no_of_records_per_page;
    $ha_kyc_employment_status_arr["total_count"] = $ha_kyc_employment_status->search_record_count($data, $orAnd);
    $ha_kyc_employment_status_arr["records"] = array();

    // retrieve our table contents

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $ha_kyc_employment_status_item = array(

            "id" => $id,
            "customer_employment_status" => html_entity_decode($customer_employment_status),
            "customer_employer_name" => html_entity_decode($customer_employer_name),
            "customer_employer_office_number" => html_entity_decode($customer_employer_office_number),
            "customer_employer_address" => html_entity_decode($customer_employer_address),
            "customer_employer_nearest_bustop" => html_entity_decode($customer_employer_nearest_bustop),
            "customer_employer_state" => html_entity_decode($customer_employer_state),
            "customer_employer_city" => html_entity_decode($customer_employer_city),
            "customer_employer_lga" => html_entity_decode($customer_employer_lga),
            "customer_employer_country" => html_entity_decode($customer_employer_country),
            "customer_employer_doe" => html_entity_decode($customer_employer_doe),
            "customer_account_bvn" => html_entity_decode($customer_account_bvn),
            "customer_account_monthly_salary" => html_entity_decode($customer_account_monthly_salary),
            "customer_account_primary_bank" => html_entity_decode($customer_account_primary_bank),
            "customer_account_primary_bank_account" => html_entity_decode($customer_account_primary_bank_account),
            "user_id" => $user_id,
            "follow_up" => $follow_up,
            "comment" => $comment,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );

        array_push($ha_kyc_employment_status_arr["records"], $ha_kyc_employment_status_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show ha_kyc_employment_status data in json format
    echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_kyc_employment_status found", "data" => $ha_kyc_employment_status_arr));

} else {
    // no ha_kyc_employment_status found will be here

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no ha_kyc_employment_status found
    echo json_encode(array("status" => "error", "code" => 0, "message" => "No ha_kyc_employment_status found.", "data" => ""));

}
 


