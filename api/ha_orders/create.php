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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_orders.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$ha_orders = new Ha_Orders($db);

// get posted data
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));

try {
// make sure data is not empty
    if (!isEmpty($data->user_id)
        && !isEmpty($data->order_number)
        && !isEmpty($data->order_payment_method)
        && !isEmpty($data->order_charge)
        && !isEmpty($data->total_amount)
        && !isEmpty($data->order_type)
        && !isEmpty($data->payment_status)
        && !isEmpty($data->status)) {

        // set ha_orders property values

        if (!isEmpty($data->user_id)) {
            $ha_orders->user_id = $data->user_id;
        } else {
            $ha_orders->user_id = '';
        }
        if (!isEmpty($data->user_info)) {
            $ha_orders->user_info = $data->user_info;
        } else {
            $ha_orders->user_info = '';
        }
        if (!isEmpty($data->order_number)) {
            $ha_orders->order_number = $data->order_number;
        } else {
            $ha_orders->order_number = '';
        }
        if (!isEmpty($data->order_details)) {
            $ha_orders->order_details = $data->order_details;
        } else {
            $ha_orders->order_details = '';
        }
        if (!isEmpty($data->order_payment_method)) {
            $ha_orders->order_payment_method = $data->order_payment_method;
        } else {
            $ha_orders->order_payment_method = '';
        }
        if (!isEmpty($data->order_payment_details)) {
            $ha_orders->order_payment_details = $data->order_payment_details;
        } else {
            $ha_orders->order_payment_details = '';
        }
        if (!isEmpty($data->order_charge)) {
            $ha_orders->order_charge = $data->order_charge;
        } else {
            $ha_orders->order_charge = '0.00';
        }
        $ha_orders->coupon_code = $data->coupon_code;
        if (!isEmpty($data->coupon_amount)) {
            $ha_orders->coupon_amount = $data->coupon_amount;
        } else {
            $ha_orders->coupon_amount = '0.00';
        }
        if (!isEmpty($data->total_amount)) {
            $ha_orders->total_amount = $data->total_amount;
        } else {
            $ha_orders->total_amount = '0.00';
        }
        if (!isEmpty($data->order_type)) {
            $ha_orders->order_type = $data->order_type;
        } else {
            $ha_orders->order_type = '';
        }
        if (!isEmpty($data->payment_status)) {
            $ha_orders->payment_status = $data->payment_status;
        } else {
            $ha_orders->payment_status = 'processing';
        }
        if (!isEmpty($data->status)) {
            $ha_orders->status = $data->status;
        } else {
            $ha_orders->status = '0';
        }

        $ha_orders->updated_at = date('Y-m-d H:m:s');
        $lastInsertedId = $ha_orders->create();
        // create the ha_orders
        if ($lastInsertedId != 0) {
            // set response code - 201 created
            http_response_code(201);
            // tell the user
            echo json_encode(array("status" => "success", "code" => 1, "message" => "Created Successfully", "data" => $lastInsertedId));
        } // if unable to create the ha_orders, tell the user
        else {
            // set response code - 503 service unavailable
            http_response_code(503);
            // tell the user
            echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_orders", "data" => ""));
        }
    } // tell the user data is incomplete
    else {
        // set response code - 400 bad request
        http_response_code(400);
        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_orders. Data is incomplete.", "data" => ""));
    }
} catch (Exception $exception) {
    echo $exception->getMessage();
}
?>
