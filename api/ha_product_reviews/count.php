<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_product_reviews.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_product_reviews object
$database = new Database();
$db = $database->getConnection();

// initialize object
$ha_product_reviews = new Ha_Product_Reviews($db);

$ha_product_reviews->user_id = $profileData->id;

// read ha_product_reviews will be here

// query ha_product_reviews
$REVIEW_COUNT = $ha_product_reviews->user_total_record_count();


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
 


