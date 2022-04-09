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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/meeting_schedules.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';

// instantiate database and meeting_schedules object
$database = new Database();
$db = $database->getConnection();

// initialize object
$meeting_schedules = new Meeting_Schedules($db);

$meeting_schedules->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$meeting_schedules->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read meeting_schedules will be here

// query meeting_schedules
$stmt = $meeting_schedules->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {

    //meeting_schedules array
    $meeting_schedules_arr = array();
    $meeting_schedules_arr["pageno"] = $meeting_schedules->pageNo;
    $meeting_schedules_arr["pagesize"] = $meeting_schedules->no_of_records_per_page;
    $meeting_schedules_arr["total_count"] = $meeting_schedules->total_record_count();
    $meeting_schedules_arr["records"] = array();

    // retrieve our table contents

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $meeting_schedules_item = array(

            "id" => $id,
            "meeting_topic" => html_entity_decode($meeting_topic),
            "meeting_fullname" => html_entity_decode($meeting_fullname),
            "meeting_email" => $meeting_email,
            "meeting_phone" => $meeting_phone,
            "meeting_date" => $meeting_date,
            "meeting_time" => $meeting_time,
            "meeting_duration_hours" => $meeting_duration_hours,
            "meeting_duration_minutes" => $meeting_duration_minutes,
            "meeting_status" => $meeting_status,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );

        array_push($meeting_schedules_arr["records"], $meeting_schedules_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show meeting_schedules data in json format
    echo json_encode(array("status" => "success", "code" => 1, "message" => "meeting_schedules found", "data" => $meeting_schedules_arr));

} else {
    // no meeting_schedules found will be here

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no meeting_schedules found
    echo json_encode(array("status" => "error", "code" => 0, "message" => "No meeting_schedules found.", "data" => ""));

}
 


