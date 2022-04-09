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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_orders.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare ha_orders object
$ha_orders = new Ha_Orders($db);

// set ID property of record to read
$ha_orders->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of ha_orders to be edited
$ha_orders->readOne();

if ($ha_orders->id != null) {
    // create array
    $ha_orders_arr = array(

        "id" => $ha_orders->id,
        "user_id" => $ha_orders->user_id,
        "user_info" => $ha_orders->user_info,
        "order_number" => html_entity_decode($ha_orders->order_number),
        "order_details" => $ha_orders->order_details,
        "order_payment_method" => $ha_orders->order_payment_method,
        "order_payment_details" => $ha_orders->order_payment_details,
        "order_charge" => $ha_orders->order_charge,
        "coupon_code" => $ha_orders->coupon_code,
        "coupon_amount" => $ha_orders->coupon_amount,
        "total_amount" => $ha_orders->total_amount,
        "order_type" => $ha_orders->order_type,
        "payment_status" => $ha_orders->payment_status,
        "status" => $ha_orders->status,
        "created_at" => $ha_orders->created_at,
        "updated_at" => $ha_orders->updated_at
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_orders found", "data" => $ha_orders_arr));
} else {
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user ha_orders does not exist
    echo json_encode(array("status" => "error", "code" => 0, "message" => "ha_orders does not exist.", "data" => ""));
}
?>
