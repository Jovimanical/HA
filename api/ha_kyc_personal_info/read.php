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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/helper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_kyc_personal_info.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_kyc_personal_info object
$database = new Database();
$db = $database->getConnection();

try {


// initialize object
    $ha_kyc_personal_info = new Ha_Kyc_Personal_Info($db);

    $ha_kyc_personal_info->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
    $ha_kyc_personal_info->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read ha_kyc_personal_info will be here
// set ID property of record to read
    $ha_kyc_personal_info->user_id = $profileData->id;

// query ha_kyc_personal_info
    $stmt = $ha_kyc_personal_info->read();
    $num = $stmt->rowCount();

// check if more than 0 record found
    if ($num > 0) {

        //ha_kyc_personal_info array
        $ha_kyc_personal_info_arr = array();
        $ha_kyc_personal_info_arr["pageno"] = $ha_kyc_personal_info->pageNo;
        $ha_kyc_personal_info_arr["pagesize"] = $ha_kyc_personal_info->no_of_records_per_page;
        $ha_kyc_personal_info_arr["total_count"] = $ha_kyc_personal_info->total_user_record_count();
        $ha_kyc_personal_info_arr["records"] = array();

        // retrieve our table contents

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $ha_kyc_personal_info_item = array(

                "id" => $id,
                "customer_firstname" => html_entity_decode($customer_firstname),
                "customer_lastname" => html_entity_decode($customer_lastname),
                "customer_dob" => html_entity_decode($customer_dob),
                "customer_gender" => $customer_gender,
                "customer_phone_no" => $customer_phone_no,
                "customer_email" => $customer_email,
                "customer_residence_type" => $customer_residence_type,
                "customer_house_number" => $customer_house_number,
                "customer_house_address" => html_entity_decode($customer_house_address),
                "customer_state" => $customer_state,
                "customer_city" => $customer_city,
                "customer_lga" => $customer_lga,
                "customer_country" => $customer_country,
                "customer_stay_duration" => $customer_stay_duration,
                "user_id" => $user_id,
                "follow_up" => $follow_up,
                "comment" => $comment,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            );

            array_push($ha_kyc_personal_info_arr["records"], $ha_kyc_personal_info_item);
        }

        // set response code - 200 OK
        http_response_code(200);

        // show ha_kyc_personal_info data in json format
        echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_kyc_personal_info found", "data" => $ha_kyc_personal_info_arr));

    } else {
        // no ha_kyc_personal_info found will be here

        // set response code - 404 Not found
        http_response_code(201);

        // tell the user no ha_kyc_personal_info found
        echo json_encode(array("status" => "success", "code" => 2, "message" => "No ha_kyc_personal_info found.", "data" => null));

    }

} catch (Exception $exception) {


    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no ha_kyc_personal_info found
    echo json_encode(array("status" => "error", "code" => 0, "message" =>  $exception->getMessage(), "data" => ""));

}


