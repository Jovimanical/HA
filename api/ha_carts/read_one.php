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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_carts.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare ha_carts object
$ha_carts = new Ha_Carts($db);

// set ID property of record to read
$ha_carts->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of ha_carts to be edited
$ha_carts->readOne();

if ($ha_carts->id != null) {
    // create array
    $ha_carts_arr = array(

        "id" => $ha_carts->id,
        "PropertyEstate" => $ha_carts->PropertyEstate,
        "PropertyBlock" => $ha_carts->PropertyBlock,
        "PropertyFloor" => $ha_carts->PropertyFloor,
        "PropertyId" => $ha_carts->PropertyId,
        "PropertyName" => html_entity_decode($ha_carts->PropertyName),
        "PropertyAmount" => $ha_carts->PropertyAmount,
        "PaymentMethod" => $ha_carts->PaymentMethod,
        "PropertyJson" => $ha_carts->PropertyJson,
        "PropertyType" => $ha_carts->PropertyType,
        "PropertyStatus" => $ha_carts->PropertyStatus,
        "ApplicationStatus"=>$ha_carts->ApplicationStatus,
        "user_id" => $ha_carts->user_id,
        "createdAt" => $ha_carts->createdAt
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_carts found", "data" => $ha_carts_arr));
} else {
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user ha_carts does not exist
    echo json_encode(array("status" => "error", "code" => 0, "message" => "ha_carts does not exist.", "data" => null));
}
?>
