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
// get database connection
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';

// instantiate ha_wishlists object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_wishlists.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare ha_wishlists object
$ha_wishlists = new Ha_Wishlists($db);

// get id of ha_wishlists to be edited
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));


// set ID property of ha_wishlists to be edited
$ha_wishlists->id = $data->id;

if (
    !isEmpty($data->EntityParent)
    && !isEmpty($data->LinkedEntity)
    && !isEmpty($data->PropertyFloor)
    && !isEmpty($data->PropertyId)
    && !isEmpty($data->PropertyName)
    && !isEmpty($data->PropertyAmount)
    && !isEmpty($data->PropertyType)
    && !isEmpty($data->PropertyStatus)
    && !isEmpty($data->user_id)
) {
// set ha_wishlists property values

    if (!isEmpty($data->EntityParent)) {
        $ha_wishlists->EntityParent = $data->EntityParent;
    } else {
        $ha_wishlists->EntityParent = '';
    }
    if (!isEmpty($data->LinkedEntity)) {
        $ha_wishlists->LinkedEntity = $data->LinkedEntity;
    } else {
        $ha_wishlists->LinkedEntity = '';
    }
    if (!isEmpty($data->PropertyFloor)) {
        $ha_wishlists->PropertyFloor = $data->PropertyFloor;
    } else {
        $ha_wishlists->PropertyFloor = '';
    }
    if (!isEmpty($data->PropertyId)) {
        $ha_wishlists->PropertyId = $data->PropertyId;
    } else {
        $ha_wishlists->PropertyId = '';
    }
    if (!isEmpty($data->PropertyName)) {
        $ha_wishlists->PropertyName = $data->PropertyName;
    } else {
        $ha_wishlists->PropertyName = '';
    }
    if (!isEmpty($data->PropertyAmount)) {
        $ha_wishlists->PropertyAmount = $data->PropertyAmount;
    } else {
        $ha_wishlists->PropertyAmount = '0.00';
    }
    $ha_wishlists->PropertyJson = $data->PropertyJson;
    if (!isEmpty($data->PropertyType)) {
        $ha_wishlists->PropertyType = $data->PropertyType;
    } else {
        $ha_wishlists->PropertyType = '3';
    }
    if (!isEmpty($data->PropertyStatus)) {
        $ha_wishlists->PropertyStatus = $data->PropertyStatus;
    } else {
        $ha_wishlists->PropertyStatus = '1';
    }
    if (!isEmpty($data->user_id)) {
        $ha_wishlists->user_id = $data->user_id;
    } else {
        $ha_wishlists->user_id = '';
    }

// update the ha_wishlists
    if ($ha_wishlists->update()) {

        // set response code - 200 ok
        http_response_code(200);

        // tell the user
        echo json_encode(array("status" => "success", "code" => 1, "message" => "Updated Successfully", "data" => ""));
    } // if unable to update the ha_wishlists, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to update ha_wishlists", "data" => ""));

    }
} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to update ha_wishlists. Data is incomplete.", "data" => ""));
}
?>
