<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/helper.php';
// get database connection
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
 
// instantiate ha_product_reviews object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_product_reviews.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$ha_product_reviews = new Ha_Product_Reviews($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->user_id)
&&!isEmpty($data->product_id)
&&!isEmpty($data->review)
&&!isEmpty($data->rating)){
 
    // set ha_product_reviews property values
	 
if(!isEmpty($data->user_id)) { 
$ha_product_reviews->user_id = $data->user_id;
} else { 
$ha_product_reviews->user_id = '';
}
if(!isEmpty($data->product_id)) { 
$ha_product_reviews->product_id = $data->product_id;
} else { 
$ha_product_reviews->product_id = '';
}
if(!isEmpty($data->review)) { 
$ha_product_reviews->review = $data->review;
} else { 
$ha_product_reviews->review = '';
}
if(!isEmpty($data->rating)) { 
$ha_product_reviews->rating = $data->rating;
} else { 
$ha_product_reviews->rating = '';
}
$ha_product_reviews->created_at = $data->created_at;
$ha_product_reviews->updated_at = $data->updated_at;
$ha_product_reviews->deleted_at = $data->deleted_at;
 	$lastInsertedId=$ha_product_reviews->create();
    // create the ha_product_reviews
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> $lastInsertedId));
    }
 
    // if unable to create the ha_product_reviews, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_product_reviews","data"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_product_reviews. Data is incomplete.","data"=> ""));
}
?>
