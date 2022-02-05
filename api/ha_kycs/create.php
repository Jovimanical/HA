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
 
// instantiate ha_kycs object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_kycs.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$ha_kycs = new Ha_Kycs($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->id)
&&!isEmpty($data->first_name)
&&!isEmpty($data->last_name)
&&!isEmpty($data->city)
&&!isEmpty($data->country)
&&!isEmpty($data->city_of_birth)
&&!isEmpty($data->country_of_birth)
&&!isEmpty($data->nationality)
&&!isEmpty($data->document_number)
&&!isEmpty($data->issuing_authority)
&&!isEmpty($data->issue_on)
&&!isEmpty($data->valid_until)
&&!isEmpty($data->order_amount)){
 
    // set ha_kycs property values
	 
if(!isEmpty($data->id)) { 
$ha_kycs->id = $data->id;
} else { 
$ha_kycs->id = '';
}
if(!isEmpty($data->first_name)) { 
$ha_kycs->first_name = $data->first_name;
} else { 
$ha_kycs->first_name = '';
}
if(!isEmpty($data->last_name)) { 
$ha_kycs->last_name = $data->last_name;
} else { 
$ha_kycs->last_name = '';
}
if(!isEmpty($data->city)) { 
$ha_kycs->city = $data->city;
} else { 
$ha_kycs->city = '';
}
if(!isEmpty($data->country)) { 
$ha_kycs->country = $data->country;
} else { 
$ha_kycs->country = '';
}
if(!isEmpty($data->city_of_birth)) { 
$ha_kycs->city_of_birth = $data->city_of_birth;
} else { 
$ha_kycs->city_of_birth = '';
}
if(!isEmpty($data->country_of_birth)) { 
$ha_kycs->country_of_birth = $data->country_of_birth;
} else { 
$ha_kycs->country_of_birth = '';
}
if(!isEmpty($data->nationality)) { 
$ha_kycs->nationality = $data->nationality;
} else { 
$ha_kycs->nationality = '';
}
$ha_kycs->document_type = $data->document_type;
if(!isEmpty($data->document_number)) { 
$ha_kycs->document_number = $data->document_number;
} else { 
$ha_kycs->document_number = '';
}
if(!isEmpty($data->issuing_authority)) { 
$ha_kycs->issuing_authority = $data->issuing_authority;
} else { 
$ha_kycs->issuing_authority = '';
}
if(!isEmpty($data->issue_on)) { 
$ha_kycs->issue_on = $data->issue_on;
} else { 
$ha_kycs->issue_on = '';
}
if(!isEmpty($data->valid_until)) { 
$ha_kycs->valid_until = $data->valid_until;
} else { 
$ha_kycs->valid_until = '';
}
if(!isEmpty($data->order_amount)) { 
$ha_kycs->order_amount = $data->order_amount;
} else { 
$ha_kycs->order_amount = '';
}
$ha_kycs->internal = $data->internal;
$ha_kycs->external = $data->external;
$ha_kycs->follow_up = $data->follow_up;
$ha_kycs->comment = $data->comment;
$ha_kycs->created_at = $data->created_at;
$ha_kycs->updated_at = $data->updated_at;
 	$lastInsertedId=$ha_kycs->create();
    // create the ha_kycs
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> $lastInsertedId));
    }
 
    // if unable to create the ha_kycs, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_kycs","data"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_kycs. Data is incomplete.","data"=> ""));
}
?>
