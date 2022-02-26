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
// get database connection
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';

// instantiate meeting_schedules object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/meeting_schedules.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare meeting_schedules object
$meeting_schedules = new Meeting_Schedules($db);
 
// set ID property of record to read
$meeting_schedules->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of meeting_schedules to be edited
$meeting_schedules->readOne();
 
if($meeting_schedules->id!=null){
    // create array
    $meeting_schedules_arr = array(
        
"id" => $meeting_schedules->id,
"meeting_topic" => html_entity_decode($meeting_schedules->meeting_topic),
"meeting_fullname" => html_entity_decode($meeting_schedules->meeting_fullname),
"meeting_email" => $meeting_schedules->meeting_email,
"meeting_phone" => $meeting_schedules->meeting_phone,
"meeting_date" => $meeting_schedules->meeting_date,
"meeting_time" => $meeting_schedules->meeting_time,
"meeting_duration_hours" => $meeting_schedules->meeting_duration_hours,
"meeting_duration_minutes" => $meeting_schedules->meeting_duration_minutes,
"meeting_status" => $meeting_schedules->meeting_status,
"created_at" => $meeting_schedules->created_at,
"updated_at" => $meeting_schedules->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "meeting_schedules found","document"=> $meeting_schedules_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user meeting_schedules does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "meeting_schedules does not exist.","document"=> ""));
}
?>
