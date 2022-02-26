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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_product_reviews.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare ha_product_reviews object
$ha_product_reviews = new Ha_Product_Reviews($db);

// set ID property of record to read
$ha_product_reviews->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of ha_product_reviews to be edited
$ha_product_reviews->readOne();

if ($ha_product_reviews->id != null) {
    // create array
    $ha_product_reviews_arr = array(

        "id" => $ha_product_reviews->id,
        "user_id" => $ha_product_reviews->user_id,
        "product_id" => $ha_product_reviews->product_id,
        "review" => $ha_product_reviews->review,
        "rating" => $ha_product_reviews->rating,
        "created_at" => $ha_product_reviews->created_at,
        "updated_at" => $ha_product_reviews->updated_at,
        "deleted_at" => $ha_product_reviews->deleted_at
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_product_reviews found", "data" => $ha_product_reviews_arr));
} else {
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user ha_product_reviews does not exist
    echo json_encode(array("status" => "error", "code" => 0, "message" => "ha_product_reviews does not exist.", "data" => ""));
}
?>
