<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // The request is using the POST method
    header("HTTP/1.1 200 OK");
    return;
}

require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();


include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_properties.php';

// instantiate database and ha_properties object
$database = new Database();
$db = $database->getConnection();

// initialize object
$ha_properties = new Ha_Properties($db);



try {
    $ha_properties->pageNo = isset($_GET['pageno']) ? (int)$_GET['pageno'] : 1;
    $ha_properties->no_of_records_per_page = isset($_GET['pagesize']) ? (int)$_GET['pagesize'] : 30;
// read ha_properties will be here

// query ha_properties
    $stmt = $ha_properties->read();
    $num = $stmt->rowCount();

// check if more than 0 record found
    if ($num > 0) {

        //ha_properties array
        $ha_properties_arr = array();
        $ha_properties_arr["pageno"] = $ha_properties->pageNo;
        $ha_properties_arr["pagesize"] = $ha_properties->no_of_records_per_page;
        $ha_properties_arr["total_count"] = $ha_properties->total_record_count();
        $ha_properties_arr["records"] = array();

        // retrieve our table contents

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $ha_properties_item = array(

                "id" => $id,
                "seller_id" => $seller_id,
                "brand_id" => $brand_id,
                "sku" => $sku,
                "name" => html_entity_decode($name),
                "model" => $model,
                "has_variants" => $has_variants,
                "track_inventory" => $track_inventory,
                "show_in_frontend" => $show_in_frontend,
                "main_image" => $main_image,
                "video_link" => $video_link,
                "description" => $description,
                "summary" => $summary,
                "specification" => $specification,
                "extra_descriptions" => $extra_descriptions,
                "base_price" => $base_price,
                "is_featured" => $is_featured,
                "meta_title" => html_entity_decode($meta_title),
                "meta_description" => html_entity_decode($meta_description),
                "meta_keywords" => $meta_keywords,
                "status" => $status,
                "sold" => $sold,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "deleted_at" => $deleted_at
            );

            array_push($ha_properties_arr["records"], $ha_properties_item);
        }

        // set response code - 200 OK
        http_response_code(200);

        // show ha_properties data in json format
        echo json_encode(array("status" => "success", "code" => 1, "message" => "properties listing found", "data" => $ha_properties_arr));

    } else {
        // no ha_properties found will be here
        // set response code - 404 Not found
        http_response_code(201);
        // tell the user no ha_properties found
        echo json_encode(array("status" => "error", "code" => 0, "message" => "No properties listing found.", "data" => []));

    }
}catch (Exception $exception){
    http_response_code(404);
    // tell the user no ha_properties found
    echo json_encode(array("status" => "error", "code" => 0, "message" => $exception->getMessage(), "data" => []));

}

