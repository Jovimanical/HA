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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_orders.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_orders object
$database = new Database();
$db = $database->getConnection();

// initialize object
$ha_orders = new Ha_Orders($db);

$ha_orders->pageNo = isset($_GET['pageno']) ? (int)$_GET['pageno'] : 1;
$ha_orders->no_of_records_per_page = isset($_GET['pagesize']) ? (int)$_GET['pagesize'] : 30;
// read ha_orders will be here
try {


// query ha_orders
    $stmt = $ha_orders->read();
    $num = $stmt->rowCount();

// check if more than 0 record found
    if ($num > 0) {

        //ha_orders array
        $ha_orders_arr = array();
        $ha_orders_arr["pageno"] = $ha_orders->pageNo;
        $ha_orders_arr["pagesize"] = $ha_orders->no_of_records_per_page;
        $ha_orders_arr["total_count"] = $ha_orders->total_record_count();
        $ha_orders_arr["records"] = array();

        // retrieve our table contents

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $ha_orders_item = array(

                "id" => $id,
                "order_number" => html_entity_decode($order_number),
                "user_id" => $user_id,
                "shipping_address" => $shipping_address,
                "shipping_method_id" => $shipping_method_id,
                "shipping_charge" => $shipping_charge,
                "coupon_code" => $coupon_code,
                "coupon_amount" => $coupon_amount,
                "total_amount" => $total_amount,
                "order_type" => $order_type,
                "payment_status" => $payment_status,
                "status" => $status,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            );

            array_push($ha_orders_arr["records"], $ha_orders_item);
        }

        // set response code - 200 OK
        http_response_code(200);

        // show ha_orders data in json format
        echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_orders found", "data" => $ha_orders_arr));

    } else {
        // no ha_orders found will be here
        // set response code - 404 Not found
        http_response_code(404);
        // tell the user no ha_orders found
        echo json_encode(array("status" => "error", "code" => 0, "message" => "No ha_orders found.", "data" => ""));

    }
}catch (Exception $exception){
    // no ha_orders found will be here
    // set response code - 404 Not found
    http_response_code(500);
    // tell the user no ha_orders found
    echo json_encode(array("status" => "error", "code" => 0, "message" => $exception->getMessage(), "data" => ""));

}
 


