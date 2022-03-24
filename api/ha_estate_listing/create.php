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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_estate_listing.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$ha_estate_listing = new Ha_Estate_Listing($db);

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
    && !isEmpty($data->PropertyStatus)) {

    // set ha_estate_listing property values

    if (!isEmpty($data->EntityParent)) {
        $ha_estate_listing->EntityParent = $data->EntityParent;
    } else {
        $ha_estate_listing->EntityParent = '';
    }
    if (!isEmpty($data->LinkedEntity)) {
        $ha_estate_listing->LinkedEntity = $data->LinkedEntity;
    } else {
        $ha_estate_listing->LinkedEntity = '';
    }
    if (!isEmpty($data->PropertyFloor)) {
        $ha_estate_listing->PropertyFloor = $data->PropertyFloor;
    } else {
        $ha_estate_listing->PropertyFloor = '';
    }
    if (!isEmpty($data->PropertyId)) {
        $ha_estate_listing->PropertyId = $data->PropertyId;
    } else {
        $ha_estate_listing->PropertyId = '';
    }
    if (!isEmpty($data->PropertyName)) {
        $ha_estate_listing->PropertyName = $data->PropertyName;
    } else {
        $ha_estate_listing->PropertyName = '';
    }
    if (!isEmpty($data->PropertyAmount)) {
        $ha_estate_listing->PropertyAmount = $data->PropertyAmount;
    } else {
        $ha_estate_listing->PropertyAmount = '0.00';
    }
    $ha_estate_listing->PropertyJson = $data->PropertyJson;
    if (!isEmpty($data->PropertyType)) {
        $ha_estate_listing->PropertyType = $data->PropertyType;
    } else {
        $ha_estate_listing->PropertyType = '3';
    }
    if (!isEmpty($data->PropertyStatus)) {
        $ha_estate_listing->PropertyStatus = $data->PropertyStatus;
    } else {
        $ha_estate_listing->PropertyStatus = '1';
    }

    if (!isEmpty($data->ApplicationStatus)) {
        $ha_estate_listing->ApplicationStatus = $data->ApplicationStatus;
    } else {
        $ha_estate_listing->ApplicationStatus = 'PROCESSING';
    }


    $ha_estate_listing->userid = $profileData->id;

    $lastInsertedId = $ha_estate_listing->create();
    // create the ha_estate_listing
    if ($lastInsertedId != 0) {

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("status" => "success", "code" => 1, "message" => "Created Successfully", "data" => $lastInsertedId));
    } // if unable to create the ha_estate_listing, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_estate_listing", "data" => ""));
    }
} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_estate_listing. Data is incomplete.", "data" => ""));
}
?>
