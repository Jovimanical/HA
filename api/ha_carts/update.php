<?php
// required headers
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Methods: PUT,POST, OPTIONS');
    header("Access-Control-Expose-Headers: Content-Length, X-JSON");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header('Access-Control-Allow-Credentials: true');
}
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: PUT, POST, OPTIONS");
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
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare ha_carts object
$ha_carts = new Ha_Carts($db);

// get id of ha_carts to be edited
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));


// set ID property of ha_carts to be edited
$ha_carts->id = (int)$data->id;

if (
    !isEmpty($data->PropertyEstate)
    && !isEmpty($data->PropertyBlock)
    && !isEmpty($data->PropertyFloor)
    && !isEmpty($data->PropertyId)
    && !isEmpty($data->PropertyName)
    && !isEmpty($data->PropertyAmount)
    && !isEmpty($data->PropertyType)
    && !isEmpty($data->PropertyStatus)
    && !isEmpty($data->user_id)
) {
// set ha_carts property values


    $ha_carts->PropertyEstate = $data->PropertyEstate;
    $ha_carts->PropertyBlock = $data->PropertyBlock;
    $ha_carts->PropertyFloor = $data->PropertyFloor;
    $ha_carts->PropertyId = $data->PropertyId;
    $ha_carts->PropertyName = $data->PropertyName;
    $ha_carts->PropertyAmount = $data->PropertyAmount ?? '0.00';
    $ha_carts->PaymentMethod = $data->PaymentMethod;
    $ha_carts->PropertyJson = $data->PropertyJson;
    $ha_carts->PropertyType = $data->PropertyType ?? '3';
    $ha_carts->PropertyStatus = $data->PropertyStatus ?? '1';
    $ha_carts->ApplicationStatus = $data->ApplicationStatus ?? 'DRAFT';

    $ha_carts->MapSnapshot = $data->MapSnapshot ?? 'https://via.placeholder.com/1020x1020.png?text=HouseAfrica+Estates+No+Image';
    $ha_carts->user_id = $profileData->id;


// update the ha_carts
    if ($ha_carts->update()) {
        // set response code - 200 ok
        http_response_code(200);
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1, "message" => "Updated Successfully", "data" => ""));
    } // if unable to update the ha_carts, tell the user
    else {
        // set response code - 503 service unavailable
        http_response_code(503);
        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to update User Application Status", "data" => ""));
    }
} // tell the user data is incomplete
else {
    // set response code - 400 bad request
    http_response_code(400);
    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to update ha_carts. Data is incomplete.", "data" => ""));
}
?>
