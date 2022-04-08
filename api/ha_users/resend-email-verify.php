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

require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/helper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_users.php';


use \Mailjet\Resources;
use Sop\CryptoTypes\Asymmetric\EC\ECPublicKey;
use Sop\CryptoTypes\Asymmetric\EC\ECPrivateKey;
use Sop\CryptoEncoding\PEM;
use kornrunner\Keccak;

$authentication_server_link = $_ENV['HA_APP_ENV'] == 'PROD' ? $_ENV['HA_EMAIL_VERIFICATION_LIVE_SERVER_LINK'] : $_ENV['HA_EMAIL_VERIFICATION_DEMO_SERVER_LINK'];


$database = new Database();
$db = $database->getConnection();

$ha_users = new Ha_Users($db);

// get posted data
//$data = file_get_contents("php://input") !== false ? json_decode(file_get_contents("php://input")) : (object)$_REQUEST; //
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));

$mj = new \Mailjet\Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true, ['version' => 'v3.1']);


// make sure data is not empty

if (!isEmpty($data->email)) {

    if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
        $error_message = array(
            "status" => "error",
            "code" => 0,
            "message" => 'Invalid Email! Email format is invalid.',
            "time" => date('Y-m-d')
        );
        http_response_code(400);
        echo json_encode($error_message);
        return;
    } else {
        // check if Email Exist in the database
        if ($ha_users->CheckEmailExist($data->email)) {
            $error_message = array(
                "status" => "error",
                "code" => 0,
                "message" => 'An Account User with this email already exist! please try another or login',
                "time" => date('Y-m-d')
            );
            http_response_code(400);
            echo json_encode($error_message);
            return;
        } else {
            $ha_users->email = $data->email;
        }
    }

    // Generate random activation token
    $token = md5(rand() . time()) . uniqid();
    $ha_users->verification_code = $token;
    $ha_users->verification_code_send_at = date('Y-m-d H:m:s');


    // create the ha_users
    if ($ha_users->resendUserVerificationToken()) {


        $sign_up_message = array("status" => "success", "code" => 1, "message" => "Account Created Successfully! A verification link has been sent to your email $ha_users->email", "data" => 0);

        $authentication_web_link = $authentication_server_link . '/' . $token . '/' . $data->email;
        $output = '<p>Dear User,</p>';
        $output .= '<p>Please click on the following link to Activate your account.</p>';
        $output .= '<p>-------------------------------------------------------------</p>';
        $output .= '<p><a style="background-color: #4CAF50;border: none;color: white;padding: 15px 32px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;" href="' . $authentication_web_link . '" target="_blank"> Click to Verify your Email</a></p>';
        $output .= '<p>-------------------------------------------------------------</p>';
        $output .= '<p>Or just copy and past this code into the form currently displayed</p>';
        $output .= '<p>Please be sure to copy the entire link into your browser. The link will expire after 1 day for security reason.</p>';
        $output .= '<p>Thanks,</p>';
        $output .= '<p>HA SUPPORT TEAM</p>';
        $body = $output;
        $subject = "Account Activation Required - HouseAfrica.io";
        $RECIPIENT_EMAIL = $data->email;
        $RECIPIENT_FULLNAME = 'New Account User';
        $SENDER_EMAIL = 'no_reply@houseafrica.io';

        try {
            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => "$SENDER_EMAIL",
                            'Name' => "HA SUPPORT-TEAM"
                        ],
                        'To' => [
                            [
                                'Email' => "$RECIPIENT_EMAIL",
                                'Name' => "$RECIPIENT_FULLNAME"
                            ]
                        ],
                        'Subject' => $subject,
                        'TextPart' => 'Please copy and past link below on a browser,' . $authentication_web_link,
                        'HTMLPart' => $body
                    ]
                ]
            ];

            // All resources are located in the Resources class
            $response = $mj->post(Resources::$Email, ['body' => $body]);

            // Read the response
            $response->success();
            //Send Account Activation Email
            //Create an instance; passing `true` enables exceptions
        } catch (Exception $e) {
            $sign_up_message["email_message"] = "Message could not be sent. Mailer Error: {$e->errorMessage()}"; //Catch errors from Amazon SES.
        }

        // set response code - 201 created
        http_response_code(201);
        // tell the user
        echo json_encode($sign_up_message);
    } // if unable to create the ha_users, tell the user
    else {
        // set response code - 503 service unavailable
        http_response_code(503);
        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_users", "data" => ""));
    }
} // tell the user data is incomplete
else {
    // set response code - 400 bad request
    http_response_code(400);
    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_users. Data is incomplete.", "data" => ""));
}
?>
