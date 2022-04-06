<?php
// required headers
ini_set('post_max_size', '20M');
ini_set('upload_max_filesize', '20M');
ini_set('memory_limit', '512M'); // or you could use 1G

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


use Aws\CommandPool;
use Guzzle\Service\Exception\CommandTransferException;

$return_json_response = array();
$image_array_holder = array();
$error_response = array();

require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();

$AWS_CLOUD_FRONT_URL = $_ENV['AWS_CLOUD_FRONT_URL'];

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/helper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_kyc_documents.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();
$ha_kyc_documents = new Ha_Kyc_Documents($db);

// get posted data
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));


if (isset($_REQUEST) && $_SERVER['REQUEST_METHOD'] == "POST") {


    if (!empty(array_filter($_FILES['fileUpload']['name']))) {
        /**
         * Check temporary directory and Create temporary directory if it does not Exist
         */

        $file_upload_directory = '../../assets/media-storage';

        if (!is_dir($file_upload_directory) && !file_exists($file_upload_directory)) {
            mkdir($file_upload_directory, 0777, true);
        }

        /**
         * Set Maximum file size
         */
        $max_file_size = $_ENV['HA_FILE_UPLOAD_LIMIT'] || 20 * 1024 * 1024; //2MB

        /**
         * Valid Extension formats
         */
        $valid_file_formats = array("jpg", "png", "jpeg", "pdf", "docx", "doc", "PNG", "JPG", "JPEG", "PDF", "DOCX", "DOC");

        // checking if the file is selected or not
        // Loop through file items
        foreach ($_FILES['fileUpload']['name'] as $id => $val) {
            $file_name = $_FILES['fileUpload']['name'][$id];
            $fileTempLocation = $_FILES['fileUpload']['tmp_name'][$id];
            $file_size = $_FILES['fileUpload']['size'][$id];
            $file_error = $_FILES['fileUpload']['error'][$id];
            $file_type = $_FILES['fileUpload']['type'][$id];
            $file_ext = explode('.', $file_name);
            $file_act_ext = strtolower(end($file_ext));
            $path = $file_upload_directory;

            // an error occurs
            if ($file_error !== UPLOAD_ERR_OK) {
                $error_response[$file_name] = ERROR_MESSAGES[$file_error];
                continue;
            }

            if (!in_array(pathinfo($file_name, PATHINFO_EXTENSION), $valid_file_formats)) {

                $message = 'You have uploaded file with file types that\'s not Allowed!';
                $error_response[$file_name] = $message;
                continue;
            }

            // validate the file size
            $filesize = filesize($fileTempLocation);
            if ($filesize > $max_file_size) {
                // construct an error message
                $message = sprintf("The file %s is %s which is greater than the allowed size %s",
                    $file_name,
                    format_filesize($filesize),
                    format_filesize($max_file_size));
                $error_response[$filesize] = $message;
                continue;
            }


            $new_file_name = pathinfo($file_name, PATHINFO_FILENAME) . "_" . date('m-d-Y') . '_' . md5(round(microtime(true))) . uniqid() . "." . $file_act_ext;
            $file_destination = $path . '/' . $new_file_name;
            $move = move_uploaded_file($fileTempLocation, $file_destination);

            if (!$move) {
                $error_response[$file_name] = "Server Error! Sorry, there was a problem uploading your file $file_name , process failed.";
            } else {
                array_push($image_array_holder, $file_destination);
            }
        }

        //print_r($image_array_holder);
        if (count($image_array_holder) > 0) {
            /** Let's initialize our AWS Client for the file uploads */
            $clientS3 = new Aws\S3\S3Client([
                /** Region you had selected, if don't know check in S3 listing */
                'region' => $_ENV['AWS_REGION_ID'],
                'version' => 'latest',
                /** Your AWS S3 Credential will be added here */
                'credentials' => [
                    'key' => $_ENV['AWS_ACCESS_KEY'],
                    'secret' => $_ENV['AWS_SECRET_KEY'],
                ]
            ]);

            $commands = array();
            foreach ($image_array_holder as $key => $file) {
                // echo pathinfo($file, PATHINFO_FILENAME);
                $file_info = new finfo(FILEINFO_MIME_TYPE);
                $file_mime = $file_info->file($file);
                $extension = pathinfo(parse_url($file, PHP_URL_PATH), PATHINFO_EXTENSION);
                $filename = pathinfo(parse_url($file, PHP_URL_PATH), PATHINFO_FILENAME);

                $objParams = array(
                    'ACL' => 'public-read',
                    'Bucket' => $_ENV['AWS_S3_BUCKET'],
                    'Key' => $filename . '.' . $extension,
                    'SourceFile' => $file,
                    'ContentType' => $file_mime
                );

                $commands[] = $clientS3->getCommand('PutObject', $objParams);
            }

            try {

                $results = CommandPool::batch($clientS3, $commands);
//                print_r($results);

                //Add to database
                $aws_object = array();
                foreach ($results as $key => $fileUploaded) {
                    array_push($aws_object, $AWS_CLOUD_FRONT_URL . basename($fileUploaded['@metadata']['effectiveUri']));

                    $ha_kyc_documents->file_name = generateFileName(4);
                    $ha_kyc_documents->file_url = $AWS_CLOUD_FRONT_URL . basename($fileUploaded['@metadata']['effectiveUri']);
                    $ha_kyc_documents->user_id = $profileData->id;
                    $ha_kyc_documents->file_status = 'pending';
                    $ha_kyc_documents->follow_up = 0;
                    $ha_kyc_documents->provider = 'locally uploaded';
                    $ha_kyc_documents->updated_at = date('Y-m-d H:m:s');
                    $ha_kyc_documents->file_password = $data->file_password ? $data->file_password : 'unknown';
                    $ha_kyc_documents->create();
                }

                $return_json_response[] = array(
                    "status" => 'success',
                    "code" => 1,
                    "message" => 'Image Uploaded Successfully',
                    "time" => date('Y-m-d'),
                    "data" => $aws_object,
                    "error" => $error_response
                );

                /**
                 * Deletes File to free server space
                 */
                foreach ($image_array_holder as $key => $file) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }

                // set response code - 201 created
                http_response_code(201);
                echo json_encode($return_json_response);

                return;
            } catch (CommandTransferException $e) {
                $succeeded = $e->getSuccessfulCommands();
                echo "Failed Commands:\n";
                foreach ($e->getFailedCommands() as $failedCommand) {
                    echo $e->getExceptionForFailedCommand($failedCommand)->getMessage() . "\n";
                }

                // set response code - 503 service unavailable
                http_response_code(503);

                // tell the user
                echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_kyc_documents", "data" => ""));


            }

        } else {
            $return_json_response[] = array(
                "status" => 'error',
                "code" => 0,
                "message" => 'You are trying to upload an Empty file',
                "time" => date('Y-m-d'),
                "uploads" => null,
                "error" => $error_response
            );

            echo json_encode($return_json_response);
            return;
        }


    } else {
        $return_json_response[] = array(
            "status" => 'error',
            "message" => 'You are trying to upload an Empty file',
            "time" => date('Y-m-d'),
            "data" => null,
            "error" => $error_response
        );

        echo json_encode($return_json_response);
        return;
    }
} else {
// make sure data is not empty
    if (
        !isEmpty($data->provider)
        && !isEmpty($data->file_name)
        && !isEmpty($data->file_url)
    ) {

        // set ha_kyc_documents property values
        $ha_kyc_documents->file_name = $data->file_name;
        $ha_kyc_documents->file_url = $data->file_url;
        $ha_kyc_documents->user_id = $profileData->id;
        $ha_kyc_documents->file_status = 'pending';
        $ha_kyc_documents->follow_up = 0;
        $ha_kyc_documents->provider = $data->provider ? $data->provider : 'localupload';
        $ha_kyc_documents->updated_at = date('Y-m-d H:m:s');

        $lastInsertedId = $ha_kyc_documents->create();
        // create the ha_kyc_documents
        if ($lastInsertedId != 0) {

            // set response code - 201 created
            http_response_code(201);

            // tell the user
            echo json_encode(array("status" => "success", "code" => 1, "message" => "Created Successfully", "data" => $lastInsertedId));
        } // if unable to create the ha_kyc_documents, tell the user
        else {

            // set response code - 503 service unavailable
            http_response_code(503);

            // tell the user
            echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_kyc_documents", "data" => ""));
        }
    } // tell the user data is incomplete
    else {

        // set response code - 400 bad request
        http_response_code(400);

        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_kyc_documents. Data is incomplete.", "data" => ""));
    }

}
?>
