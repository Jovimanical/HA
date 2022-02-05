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
 
// instantiate ha_agents object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_agents.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$ha_agents = new Ha_Agents($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->username)
&&!isEmpty($data->email)
&&!isEmpty($data->balance)
&&!isEmpty($data->password)
&&!isEmpty($data->status)
&&!isEmpty($data->ev)
&&!isEmpty($data->sv)
&&!isEmpty($data->ts)
&&!isEmpty($data->tv)
&&!isEmpty($data->featured)){
 
    // set ha_agents property values
	 
$ha_agents->firstname = $data->firstname;
$ha_agents->lastname = $data->lastname;
if(!isEmpty($data->username)) { 
$ha_agents->username = $data->username;
} else { 
$ha_agents->username = '';
}
if(!isEmpty($data->email)) { 
$ha_agents->email = $data->email;
} else { 
$ha_agents->email = '';
}
$ha_agents->country_code = $data->country_code;
$ha_agents->mobile = $data->mobile;
if(!isEmpty($data->balance)) { 
$ha_agents->balance = $data->balance;
} else { 
$ha_agents->balance = '0.00000000';
}
if(!isEmpty($data->password)) { 
$ha_agents->password = $data->password;
} else { 
$ha_agents->password = '';
}
$ha_agents->image = $data->image;
$ha_agents->address = $data->address;
if(!isEmpty($data->status)) { 
$ha_agents->status = $data->status;
} else { 
$ha_agents->status = '1';
}
if(!isEmpty($data->ev)) { 
$ha_agents->ev = $data->ev;
} else { 
$ha_agents->ev = '0';
}
if(!isEmpty($data->sv)) { 
$ha_agents->sv = $data->sv;
} else { 
$ha_agents->sv = '0';
}
$ha_agents->ver_code = $data->ver_code;
$ha_agents->ver_code_send_at = $data->ver_code_send_at;
if(!isEmpty($data->ts)) { 
$ha_agents->ts = $data->ts;
} else { 
$ha_agents->ts = '0';
}
if(!isEmpty($data->tv)) { 
$ha_agents->tv = $data->tv;
} else { 
$ha_agents->tv = '1';
}
$ha_agents->roles = $data->roles;
if(!isEmpty($data->featured)) { 
$ha_agents->featured = $data->featured;
} else { 
$ha_agents->featured = '0';
}
$ha_agents->remember_token = $data->remember_token;
$ha_agents->created_at = $data->created_at;
$ha_agents->updated_at = $data->updated_at;
 	$lastInsertedId=$ha_agents->create();
    // create the ha_agents
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> $lastInsertedId));
    }
 
    // if unable to create the ha_agents, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_agents","data"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_agents. Data is incomplete.","data"=> ""));
}
?>
