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

use GuzzleHttp\Client;

require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/helper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/meeting_schedules.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$meeting_schedules = new Meeting_Schedules($db);

$token = $_ENV['HA_ZOOM_TOKEN'];
$zoom_email = $_ENV['HA_ZOOM_REG_EMAIL'];
$zoom_is_active = $_ENV['HA_ACTIVATE_ZOOM_SCHEDULE'];

// get posted data
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));


// make sure data is not empty
if (!isEmpty($data->meeting_topic)
    && !isEmpty($data->meeting_fullname)
    && !isEmpty($data->meeting_email)
    && !isEmpty($data->meeting_phone)
    && !isEmpty($data->meeting_date)
    && !isEmpty($data->meeting_time)
    && !isEmpty($data->meeting_duration_hours)
    && !isEmpty($data->meeting_duration_minutes)
    && !isEmpty($data->meeting_status)) {

    // set meeting_schedules property values

    if (!isEmpty($data->meeting_topic)) {
        $meeting_schedules->meeting_topic = $data->meeting_topic;
    } else {
        $meeting_schedules->meeting_topic = '';
    }
    if (!isEmpty($data->meeting_fullname)) {
        $meeting_schedules->meeting_fullname = $data->meeting_fullname;
    } else {
        $meeting_schedules->meeting_fullname = '';
    }
    if (!isEmpty($data->meeting_email)) {
        $meeting_schedules->meeting_email = $data->meeting_email;
    } else {
        $meeting_schedules->meeting_email = '';
    }
    if (!isEmpty($data->meeting_phone)) {
        $meeting_schedules->meeting_phone = $data->meeting_phone;
    } else {
        $meeting_schedules->meeting_phone = '';
    }
    if (!isEmpty($data->meeting_date)) {
        $meeting_schedules->meeting_date = $data->meeting_date;
    } else {
        $meeting_schedules->meeting_date = '';
    }
    if (!isEmpty($data->meeting_time)) {
        $meeting_schedules->meeting_time = $data->meeting_time;
    } else {
        $meeting_schedules->meeting_time = '';
    }
    if (!isEmpty($data->meeting_duration_hours)) {
        $meeting_schedules->meeting_duration_hours = $data->meeting_duration_hours;
    } else {
        $meeting_schedules->meeting_duration_hours = '';
    }
    if (!isEmpty($data->meeting_duration_minutes)) {
        $meeting_schedules->meeting_duration_minutes = $data->meeting_duration_minutes;
    } else {
        $meeting_schedules->meeting_duration_minutes = '';
    }
    if (!isEmpty($data->meeting_status)) {
        $meeting_schedules->meeting_status = $data->meeting_status;
    } else {
        $meeting_schedules->meeting_status = 'active';
    }
    $meeting_schedules->updated_at = $data->updated_at;


    $client = new Client(['base_uri' => 'https://api.zoom.us']);
    $headers = ['Authorization' => 'Bearer ' . $token, 'Content-Type' => 'application/json', 'Accept' => 'application/json',];


    try {
        $response = $client->request('POST', '/v2/users/' . $zoom_email . '/meetings', [
            "headers" => $headers,
            'form_params' => [
                'start_time' => $data->meeting_date,
                'duration' => $data->duration,
                'topic' => $data->meeting_topic,
                'type' => 2,

            ]]);


        $lastInsertedId = $meeting_schedules->create();
        // create the meeting_schedules
        if ($lastInsertedId != 0) {

            // set response code - 201 created
            http_response_code(201);

            // tell the user
            echo json_encode(array("status" => "success", "code" => 1, "message" => "Created Successfully", "data" => $response->getBody()));
        } // if unable to create the meeting_schedules, tell the user
        else {

            // set response code - 503 service unavailable
            http_response_code(503);

            // tell the user
            echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create meeting_schedules", "data" => ""));
        }
    } catch (\GuzzleHttp\Exception\GuzzleException $e) {
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create meeting_schedules", "data" =>$e->getMessage()));
    }


} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create meeting_schedules. Data is incomplete.", "data" => ""));
}
?>
