<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/helper.php';
// get database connection
include_once '../config/database.php';
 
// instantiate ha_estate_listing object
include_once '../objects/ha_estate_listing.php';
 include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$ha_estate_listing = new Ha_Estate_Listing($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->EntityParent)
&&!isEmpty($data->LinkedEntity)
&&!isEmpty($data->PropertyFloor)
&&!isEmpty($data->PropertyId)
&&!isEmpty($data->PropertyName)
&&!isEmpty($data->PropertyAmount)
&&!isEmpty($data->PropertyType)
&&!isEmpty($data->PropertyStatus)
&&!isEmpty($data->userid)){
 
    // set ha_estate_listing property values
	 
if(!isEmpty($data->EntityParent)) { 
$ha_estate_listing->EntityParent = $data->EntityParent;
} else { 
$ha_estate_listing->EntityParent = '';
}
if(!isEmpty($data->LinkedEntity)) { 
$ha_estate_listing->LinkedEntity = $data->LinkedEntity;
} else { 
$ha_estate_listing->LinkedEntity = '';
}
if(!isEmpty($data->PropertyFloor)) { 
$ha_estate_listing->PropertyFloor = $data->PropertyFloor;
} else { 
$ha_estate_listing->PropertyFloor = '';
}
if(!isEmpty($data->PropertyId)) { 
$ha_estate_listing->PropertyId = $data->PropertyId;
} else { 
$ha_estate_listing->PropertyId = '';
}
if(!isEmpty($data->PropertyName)) { 
$ha_estate_listing->PropertyName = $data->PropertyName;
} else { 
$ha_estate_listing->PropertyName = '';
}
if(!isEmpty($data->PropertyAmount)) { 
$ha_estate_listing->PropertyAmount = $data->PropertyAmount;
} else { 
$ha_estate_listing->PropertyAmount = '0.00';
}
$ha_estate_listing->PropertyJson = $data->PropertyJson;
if(!isEmpty($data->PropertyType)) { 
$ha_estate_listing->PropertyType = $data->PropertyType;
} else { 
$ha_estate_listing->PropertyType = '3';
}
if(!isEmpty($data->PropertyStatus)) { 
$ha_estate_listing->PropertyStatus = $data->PropertyStatus;
} else { 
$ha_estate_listing->PropertyStatus = '1';
}
if(!isEmpty($data->userid)) { 
$ha_estate_listing->userid = $data->userid;
} else { 
$ha_estate_listing->userid = '';
}
 	$lastInsertedId=$ha_estate_listing->create();
    // create the ha_estate_listing
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
 
    // if unable to create the ha_estate_listing, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_estate_listing","document"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_estate_listing. Data is incomplete.","document"=> ""));
}
?>
