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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_carts.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$ha_carts = new Ha_Carts($db);

// get posted data
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));


// make sure data is not empty
if (!isEmpty($data->EntityParent)
    && !isEmpty($data->LinkedEntity)
    && !isEmpty($data->PropertyFloor)
    && !isEmpty($data->PropertyId)
    && !isEmpty($data->PropertyName)
    && !isEmpty($data->PropertyAmount)
    && !isEmpty($data->PropertyType)
    && !isEmpty($data->PropertyStatus)
) {

    // set ha_carts property values

    if (!isEmpty($data->EntityParent)) {
        $ha_carts->EntityParent = $data->EntityParent;
    } else {
        $ha_carts->EntityParent = '';
    }
    if (!isEmpty($data->LinkedEntity)) {
        $ha_carts->LinkedEntity = $data->LinkedEntity;
    } else {
        $ha_carts->LinkedEntity = '';
    }
    if (!isEmpty($data->PropertyFloor)) {
        $ha_carts->PropertyFloor = $data->PropertyFloor;
    } else {
        $ha_carts->PropertyFloor = '';
    }
    if (!isEmpty($data->PropertyId)) {
        $ha_carts->PropertyId = $data->PropertyId;
    } else {
        $ha_carts->PropertyId = '';
    }
    if (!isEmpty($data->PropertyName)) {
        $ha_carts->PropertyName = $data->PropertyName;
    } else {
        $ha_carts->PropertyName = '';
    }
    if (!isEmpty($data->PropertyAmount)) {
        $ha_carts->PropertyAmount = $data->PropertyAmount;
    } else {
        $ha_carts->PropertyAmount = '0.00';
    }
    $ha_carts->PaymentMethod = $data->PaymentMethod;
    $ha_carts->PropertyJson = $data->PropertyJson;
    if (!isEmpty($data->PropertyType)) {
        $ha_carts->PropertyType = $data->PropertyType;
    } else {
        $ha_carts->PropertyType = '3';
    }
    if (!isEmpty($data->PropertyStatus)) {
        $ha_carts->PropertyStatus = $data->PropertyStatus;
    } else {
        $ha_carts->PropertyStatus = 'available';
    }

    $ha_carts->user_id = $profileData->id;
    $lastInsertedId = $ha_carts->create();
    // create the ha_carts
    if ($lastInsertedId != 0) {

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("status" => "success", "code" => 1, "message" => "Created Successfully", "data" => $lastInsertedId));
    } // if unable to create the ha_carts, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_carts", "data" => ""));
    }
} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_carts. Data is incomplete.", "data" => ""));
}
?>
