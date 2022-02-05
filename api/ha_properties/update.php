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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_properties.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_properties object
$ha_properties = new Ha_Properties($db);
 
// get id of ha_properties to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_properties to be edited
$ha_properties->id = $data->id;

if(
!isEmpty($data->seller_id)
&&!isEmpty($data->brand_id)
&&!isEmpty($data->name)
&&!isEmpty($data->has_variants)
&&!isEmpty($data->track_inventory)
&&!isEmpty($data->show_in_frontend)
&&!isEmpty($data->main_image)
&&!isEmpty($data->base_price)
&&!isEmpty($data->is_featured)
&&!isEmpty($data->status)
&&!isEmpty($data->sold)
){
// set ha_properties property values

if(!isEmpty($data->seller_id)) { 
$ha_properties->seller_id = $data->seller_id;
} else { 
$ha_properties->seller_id = '0';
}
if(!isEmpty($data->brand_id)) { 
$ha_properties->brand_id = $data->brand_id;
} else { 
$ha_properties->brand_id = '';
}
$ha_properties->sku = $data->sku;
if(!isEmpty($data->name)) { 
$ha_properties->name = $data->name;
} else { 
$ha_properties->name = '';
}
$ha_properties->model = $data->model;
if(!isEmpty($data->has_variants)) { 
$ha_properties->has_variants = $data->has_variants;
} else { 
$ha_properties->has_variants = '0';
}
if(!isEmpty($data->track_inventory)) { 
$ha_properties->track_inventory = $data->track_inventory;
} else { 
$ha_properties->track_inventory = '1';
}
if(!isEmpty($data->show_in_frontend)) { 
$ha_properties->show_in_frontend = $data->show_in_frontend;
} else { 
$ha_properties->show_in_frontend = '1';
}
if(!isEmpty($data->main_image)) { 
$ha_properties->main_image = $data->main_image;
} else { 
$ha_properties->main_image = '';
}
$ha_properties->video_link = $data->video_link;
$ha_properties->description = $data->description;
$ha_properties->summary = $data->summary;
$ha_properties->specification = $data->specification;
$ha_properties->extra_descriptions = $data->extra_descriptions;
if(!isEmpty($data->base_price)) { 
$ha_properties->base_price = $data->base_price;
} else { 
$ha_properties->base_price = '0.00';
}
if(!isEmpty($data->is_featured)) { 
$ha_properties->is_featured = $data->is_featured;
} else { 
$ha_properties->is_featured = '0';
}
$ha_properties->meta_title = $data->meta_title;
$ha_properties->meta_description = $data->meta_description;
$ha_properties->meta_keywords = $data->meta_keywords;
if(!isEmpty($data->status)) { 
$ha_properties->status = $data->status;
} else { 
$ha_properties->status = '0';
}
if(!isEmpty($data->sold)) { 
$ha_properties->sold = $data->sold;
} else { 
$ha_properties->sold = '0';
}
$ha_properties->created_at = $data->created_at;
$ha_properties->updated_at = $data->updated_at;
$ha_properties->deleted_at = $data->deleted_at;
 
// update the ha_properties
if($ha_properties->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_properties, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_properties","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_properties. Data is incomplete.","data"=> ""));
}
?>
