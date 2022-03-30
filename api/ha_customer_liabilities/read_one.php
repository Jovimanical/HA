<?php
// required headers
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Methods: GET, OPTIONS');
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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_customer_liabilities.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare ha_customer_liabilities object
$ha_customer_liabilities = new Ha_Customer_Liabilities($db);

// set ID property of record to read
$ha_customer_liabilities->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of ha_customer_liabilities to be edited
$ha_customer_liabilities->readOne();

if ($ha_customer_liabilities->id != null) {
    // create array
    $ha_customer_liabilities_arr = array(
        "id" => $ha_customer_liabilities->id,
        "user_id" => $ha_customer_liabilities->user_id,
        "accountNumber" => $ha_customer_liabilities->accountNumber,
        "balance" => $ha_customer_liabilities->balance,
        "description" => $ha_customer_liabilities->description,
        "monthlyPayment" => $ha_customer_liabilities->monthlyPayment,
        "liability_status" => $ha_customer_liabilities->liability_status,
        "createdAt" => $ha_customer_liabilities->createdAt,
        "updatedAt" => $ha_customer_liabilities->updatedAt
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_customer_liabilities found", "document" => $ha_customer_liabilities_arr));
} else {
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user ha_customer_liabilities does not exist
    echo json_encode(array("status" => "error", "code" => 0, "message" => "ha_customer_liabilities does not exist.", "document" => ""));
}
?>
