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
 
// instantiate ha_categories object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_categories.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$ha_categories = new Ha_Categories($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->name)
&&!isEmpty($data->icon)
&&!isEmpty($data->is_top)
&&!isEmpty($data->is_special)
&&!isEmpty($data->in_filter_menu)){
 
    // set ha_categories property values
	 
$ha_categories->parent_id = $data->parent_id;
if(!isEmpty($data->name)) { 
$ha_categories->name = $data->name;
} else { 
$ha_categories->name = '';
}
if(!isEmpty($data->icon)) { 
$ha_categories->icon = $data->icon;
} else { 
$ha_categories->icon = '';
}
$ha_categories->meta_title = $data->meta_title;
$ha_categories->meta_description = $data->meta_description;
$ha_categories->meta_keywords = $data->meta_keywords;
$ha_categories->image = $data->image;
if(!isEmpty($data->is_top)) { 
$ha_categories->is_top = $data->is_top;
} else { 
$ha_categories->is_top = '0';
}
if(!isEmpty($data->is_special)) { 
$ha_categories->is_special = $data->is_special;
} else { 
$ha_categories->is_special = '0';
}
if(!isEmpty($data->in_filter_menu)) { 
$ha_categories->in_filter_menu = $data->in_filter_menu;
} else { 
$ha_categories->in_filter_menu = '0';
}
$ha_categories->created_at = $data->created_at;
$ha_categories->updated_at = $data->updated_at;
$ha_categories->deleted_at = $data->deleted_at;
 	$lastInsertedId=$ha_categories->create();
    // create the ha_categories
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> $lastInsertedId));
    }
 
    // if unable to create the ha_categories, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_categories","data"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_categories. Data is incomplete.","data"=> ""));
}
?>
