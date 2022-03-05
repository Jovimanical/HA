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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_wishlists.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';

// instantiate database and ha_product_reviews object
$database = new Database();
$db = $database->getConnection();

// initialize object
$ha_wishlists = new Ha_Wishlists($db);

$ha_wishlists->user_id = $profileData->id;

// read ha_product_reviews will be here

// query ha_product_reviews
$REVIEW_COUNT = $ha_wishlists->user_total_record_count();


// check if more than 0 record found
if ($REVIEW_COUNT > 0) {

      // set response code - 200 OK
    http_response_code(200);

    // show ha_product_reviews data in json format
    echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_product_reviews found", "data" => $REVIEW_COUNT));

} else if($REVIEW_COUNT == 0){
    // no ha_product_reviews found will be here

    // set response code - 404 Not found
    http_response_code(201);

    // tell the user no ha_product_reviews found
    echo json_encode(array("status" => "success", "code" => 1, "message" => "No ha_product_reviews found.", "data" => $REVIEW_COUNT));

}else {
    // no ha_product_reviews found will be here

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no ha_product_reviews found
    echo json_encode(array("status" => "error", "code" => 0, "message" => "No ha_product_reviews found.", "data" => ""));

}
 


