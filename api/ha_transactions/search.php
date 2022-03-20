<?php
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
// instantiate database and ha_transactions object
$database = new Database();
$db = $database->getConnection();

// initialize object
$ha_transactions = new Ha_Transactions($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$ha_transactions->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_transactions->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_transactions
$stmt = $ha_transactions->search($searchKey);
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {

    //ha_transactions array
    $ha_transactions_arr = array();
    $ha_transactions_arr["pageno"] = $ha_transactions->pageNo;
    $ha_transactions_arr["pagesize"] = $ha_transactions->no_of_records_per_page;
    $ha_transactions_arr["total_count"] = $ha_transactions->total_record_count();
    $ha_transactions_arr["records"] = array();

    // retrieve our table contents

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $ha_transactions_item = array(

            "id" => $id,
            "sender_id" => $sender_id,
            "receiver_id" => $receiver_id,
            "amount" => $amount,
            "charge" => $charge,
            "post_balance" => $post_balance,
            "transaction_type" => $transaction_type,
            "sender_Account" => $sender_Account,
            "receiver_Account" => $receiver_Account,
            "trx" => $trx,
            "details" => html_entity_decode($details),
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );

        array_push($ha_transactions_arr["records"], $ha_transactions_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show ha_transactions data in json format
    echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_transactions found", "document" => $ha_transactions_arr));

} else {
    // no ha_transactions found will be here

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no ha_transactions found
    echo json_encode(array("status" => "error", "code" => 0, "message" => "No ha_transactions found.", "document" => ""));

}
 


