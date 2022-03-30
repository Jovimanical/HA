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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_accounts.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_accounts object
$database = new Database();
$db = $database->getConnection();

// initialize object
$ha_accounts = new Ha_Accounts($db);

$ha_accounts->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_accounts->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$ha_accounts->user_id = $profileData->id;
// read ha_accounts will be here
try {
// query ha_accounts
    $stmt = $ha_accounts->read();
    $num = $stmt->rowCount();

// check if more than 0 record found
    if ($num > 0) {

        //ha_accounts array
        $ha_accounts_arr = array();
        $ha_accounts_arr["pageno"] = $ha_accounts->pageNo;
        $ha_accounts_arr["pagesize"] = $ha_accounts->no_of_records_per_page;
        $ha_accounts_arr["total_count"] = $ha_accounts->total_user_record_count();
        $ha_accounts_arr["records"] = array();

        // retrieve our table contents

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $ha_accounts_item = array(
                "id" => $id,
                "user_id" => $user_id,
                "account_number" => $account_number,
                "account_status" => $account_status,
                "account_type" => $account_type,
                "account_balance" => $account_balance,
                "account_point" => $account_point,
                "account_blockchain_address" => html_entity_decode($account_blockchain_address),
                "account_primary" => $account_primary,
                "createdAt" => $createdAt,
                "updatedAt" => $updatedAt
            );

            array_push($ha_accounts_arr["records"], $ha_accounts_item);
        }

        // set response code - 200 OK
        http_response_code(200);

        // show ha_accounts data in json format
        echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_accounts found", "data" => $ha_accounts_arr));

    } else {
        // no ha_accounts found will be here

        // set response code - 404 Not found
        http_response_code(201);

        // tell the user no ha_accounts found
        echo json_encode(array("status" => "error", "code" => 0, "message" => "No ha_accounts found.", "data" => []));

    }
} catch (Exception $exception) {
    http_response_code(404);

    // tell the user no ha_accounts found
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Error Caught." . $exception->getMessage(), "data" => null));

}
 


