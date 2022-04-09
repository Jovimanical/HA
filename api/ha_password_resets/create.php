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
        header("Access-Control-Allow-Methods: POST, OPTIONS");
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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_password_resets.php';

use \Mailjet\Resources;

$database = new Database();
$db = $database->getConnection();

$ha_users = new Ha_Users($db);
$ha_password_resets = new Ha_Password_Resets($db);

// get posted data
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));

$authentication_server_link = $_ENV['HA_APP_ENV'] == 'PROD' ? $_ENV['HA_EMAIL_RESET_PASSWORD_LIVE_SERVER_LINK'] : $_ENV['HA_EMAIL_RESET_PASSWORD_DEMO_SERVER_LINK'];
// Use your saved credentials, specify that you are using Send API v3.1

$mj = new \Mailjet\Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true, ['version' => 'v3.1']);


// make sure data is not empty
if (!isEmpty($data->email)) {

    // set ha_password_resets property values

    $email = $data->email;
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $error_message = array(
            "status" => "error",
            "code" => 0,
            "message" => 'Invalid email address please type a valid email address!',
            "time" => date('Y-m-d')
        );
        http_response_code(400);
        echo json_encode($error_message);
        return;
    }

    $ha_users->email = $email;

    if (!$ha_users->emailExits()) {
        $error_message = array(
            "status" => "error",
            "code" => 0,
            "message" => 'No user is registered with this email address!',
            "time" => date('Y-m-d')
        );
        http_response_code(400);
        echo json_encode($error_message);
        return;

    }


    $expFormat = mktime(
        date("H"), date("i"), date("s"), date("m"), date("d") + 1, date("Y")
    );

    $key = md5((2418 * 2) . $email);
    $addKey = substr(md5(uniqid(rand(), 1)), 3, 10);


    $ha_password_resets->email = $email;
    $ha_password_resets->token = $key . $addKey;
    $ha_password_resets->expDate = date("Y-m-d H:i:s", $expFormat);
    $ha_password_resets->status = '1';
    $ha_password_resets->updatedAt = date('Y-m-d H:m:s');
    $lastInsertedId = $ha_password_resets->create();
    // create the ha_password_resets
    if ($lastInsertedId != 0) {

        $authentication_web_link = $authentication_server_link . '/' . $key . '/' . $email;
        $output = '<p>Dear user,</p>';
        $output .= '<p>Please click on the following link to reset your password.</p>';
        $output .= '<p>-------------------------------------------------------------</p>';
        $output .= '<p><a style="background-color: #4CAF50;border: none;color: white;padding: 15px 32px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;" href="' . $authentication_web_link . '" target="_blank"> Click to Verify your Email</a></p>';
        $output .= '<p>-------------------------------------------------------------</p>';
        $output .= '<p>Or just copy and past this code into the form currently displayed</p>';
        $output .= '<p style="font-weight: 900; font-size: 20px; color:#4CAF50">' . $key . '</p>';
        $output .= '<p>Please be sure to copy the entire link into your browser.
The link will expire after 1 day for security reason.</p>';
        $output .= '<p>If you did not request this forgotten password email, no action 
is needed, your password will not be reset. However, you may want to log into 
your account and change your security password as someone may have guessed it.</p>';
        $output .= '<p>Thanks,</p>';
        $output .= '<p>HA SUPPORT TEAM</p>';
        $body = $output;
        $subject = "Password Recovery - HouseAfrica.io";
        $RECIPIENT_EMAIL = $email;
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
                                'Name' => "You"
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
            // set response code - 201 created
            http_response_code(201);

            // tell the user
            echo json_encode(array("status" => "success", "hasError" => false, "email" => $email, "message" => "An email has been sent to you with instructions on how to reset your password.", "forgotPasswordStep" => 1));

        } catch (Exception $e) {
            http_response_code(503);
            echo json_encode(array("status" => "error", "code" => 0, "message" => "Error in sending email. Mailer Error: {$mail->ErrorInfo}", "data" => ""));

        }
    } // if unable to create the ha_password_resets, tell the user
    else {
        // set response code - 503 service unavailable
        http_response_code(503);
        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_password_resets", "data" => ""));
    }
} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_password_resets. Data is incomplete.", "data" => ""));
}
?>
