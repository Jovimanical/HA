<?php
// required headers
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Methods: POST, OPTIONS');
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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_transactions.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$ha_transactions = new Ha_Transactions($db);

// get posted data
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));


// make sure data is not empty
if (!isEmpty($data->sender_id)
    && !isEmpty($data->receiver_id)
    && !isEmpty($data->amount)
    && !isEmpty($data->charge)) {

    // set ha_transactions property values

    if (!isEmpty($data->sender_id)) {
        $ha_transactions->sender_id = $data->sender_id;
    } else {
        $ha_transactions->sender_id = '';
    }
    if (!isEmpty($data->receiver_id)) {
        $ha_transactions->receiver_id = $data->receiver_id;
    } else {
        $ha_transactions->receiver_id = '';
    }
    if (!isEmpty($data->amount)) {
        $ha_transactions->amount = $data->amount;
    } else {
        $ha_transactions->amount = '0.00000000';
    }
    if (!isEmpty($data->charge)) {
        $ha_transactions->charge = $data->charge;
    } else {
        $ha_transactions->charge = '0.00000000';
    }
    if (!isEmpty($data->post_balance)) {
        $ha_transactions->post_balance = $data->post_balance;
    } else {
        $ha_transactions->post_balance = '0.00000000';
    }
    $ha_transactions->transaction_type = $data->transaction_type;
    $ha_transactions->sender_Account = $data->sender_Account;
    $ha_transactions->receiver_Account = $data->receiver_Account;
    $ha_transactions->trx = $data->trx;
    $ha_transactions->details = $data->details;
    $ha_transactions->updated_at = date('Y-m-d H:m:s');
    $lastInsertedId = $ha_transactions->create();
    // create the ha_transactions
    if ($lastInsertedId != 0) {

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("status" => "success", "code" => 1, "message" => "Created Successfully", "document" => $lastInsertedId));
    } // if unable to create the ha_transactions, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_transactions", "document" => ""));
    }
} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_transactions. Data is incomplete.", "document" => ""));
}
?>
