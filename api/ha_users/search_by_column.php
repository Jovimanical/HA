<?php
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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_users.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_users object
$database = new Database();
$db = $database->getConnection();

// initialize object
$ha_users = new Ha_Users($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$ha_users->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_users->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_users
$stmt = $ha_users->searchByColumn($data, $orAnd);

$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {

    //ha_users array
    $ha_users_arr = array();
    $ha_users_arr["pageno"] = $ha_users->pageNo;
    $ha_users_arr["pagesize"] = $ha_users->no_of_records_per_page;
    $ha_users_arr["total_count"] = $ha_users->search_record_count($data, $orAnd);
    $ha_users_arr["records"] = array();

    // retrieve our table contents

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $ha_users_item = array(
            "id" => $id,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "username" => $username,
            "profileImage" => html_entity_decode($profileImage),
            "address" => $address,
            "status" => $status,
            "created_at" => $created_at,

        );

        array_push($ha_users_arr["records"], $ha_users_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show ha_users data in json format
    echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_users found", "data" => $ha_users_arr));

} else {
    // no ha_users found will be here

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no ha_users found
    echo json_encode(array("status" => "error", "code" => 0, "message" => "No ha_users found.", "data" => ""));

}
 


