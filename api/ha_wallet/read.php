<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Methods: GET, OPTIONS');
header("Access-Control-Expose-Headers: Content-Length, X-JSON");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // The request is using the POST method
    header("HTTP/1.1 200 OK");
    return;
}
require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();


include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_wallet.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_wallet object
$database = new Database();
$db = $database->getConnection();

// initialize object
$ha_wallet = new Ha_Wallet($db);

$ha_wallet->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_wallet->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read ha_wallet will be here

// query ha_wallet
$stmt = $ha_wallet->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {

    //ha_wallet array
    $ha_wallet_arr = array();
    $ha_wallet_arr["pageno"] = $ha_wallet->pageNo;
    $ha_wallet_arr["pagesize"] = $ha_wallet->no_of_records_per_page;
    $ha_wallet_arr["total_count"] = $ha_wallet->total_record_count();
    $ha_wallet_arr["records"] = array();

    // retrieve our table contents

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $ha_wallet_item = array(

            "username" => $username,
            "id" => $id,
            "users_id" => $users_id,
            "flag" => $flag,
            "amount" => $amount,
            "description" => html_entity_decode($description),
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );

        array_push($ha_wallet_arr["records"], $ha_wallet_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show ha_wallet data in json format
    echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_wallet found", "data" => $ha_wallet_arr));

} elseif ($num == 0) {
    // no ha_wallet found will be here

    // set response code - 404 Not found
    http_response_code(201);

    // tell the user no ha_wallet found
    echo json_encode(array("status" => "success", "code" => 1, "message" => "No ha-wallet found.", "data" => []));

} else {
    // no ha_wallet found will be here

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no ha_wallet found
    echo json_encode(array("status" => "error", "code" => 0, "message" => "No ha_wallet found.", "data" => ""));

}
 


