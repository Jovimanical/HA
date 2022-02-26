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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/helper.php';
// get database connection
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
// instantiate ha_users object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_users.php';


$database = new Database();
$db = $database->getConnection();

$ha_users = new Ha_Users($db);

// get posted data
//$data = file_get_contents("php://input") !== false ? json_decode(file_get_contents("php://input")) : (object)$_REQUEST; //
$data =  (json_decode(file_get_contents("php://input"), true ) === NULL ) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));

// make sure data is not empty

if (!isEmpty($data->firstname)
    && !isEmpty($data->email)
    && !isEmpty($data->password)
    && !isEmpty($data->lastname)
    && !isEmpty($data->mobile)) {

    // set ha_users property values
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
    }

    if (!preg_match("/^[a-zA-Z ]*$/", $data->firstname)) {
        $error_message = array(
            "status" => "error",
            "code" => 0,
            "message" => 'Invalid FirstName! Only letters and white space allowed.',
            "time" => date('Y-m-d')
        );
        http_response_code(400);
        echo json_encode($error_message);
        return;
    } else {
        $ha_users->firstname = htmlspecialchars(strip_tags($data->firstname));
    }

    if (!preg_match("/^[a-zA-Z ]*$/", $data->lastname)) {
        $error_message = array(
            "status" => "error",
            "code" => 0,
            "message" => 'Invalid LastName! Only letters and white space allowed.',
            "time" => date('Y-m-d')
        );
        http_response_code(400);
        echo json_encode($error_message);
        return;
    } else {
        $ha_users->lastname = htmlspecialchars(strip_tags($data->lastname));
    }
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
        $ha_users->email = $data->email;
    }

    if (!preg_match("/^[0-9]{11}+$/", $data->mobile)) {
        $error_message = array(
            "status" => "error",
            "code" => 0,
            "message" => 'Invalid Phone number! Only 10-digit mobile numbers allowed.',
            "time" => date('Y-m-d')
        );
        http_response_code(400);
        echo json_encode($error_message);
        return;
    } else {
        $ha_users->mobile = htmlspecialchars(strip_tags($data->mobile));
    }

    if (!preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{6,20}$/", $data->password)) {
        $error_message = array(
            "status" => "error",
            "code" => 0,
            "message" => 'Password should be between 6 and 20 characters long, contains at least one special character, lowercase, uppercase and a digit.',
            "time" => date('Y-m-d')
        );
        http_response_code(400);
        echo json_encode($error_message);
        return;
    } else {
        $options = [
            'cost' => 12,
        ];
        $ha_users->password = password_hash($data->password, PASSWORD_BCRYPT, $options);
    }

    $ha_users->country_code = $data->country_code ?? 234;
    $ha_users->username = generate_username($ha_users->firstname . $ha_users->lastname, 30);

    $ha_users->profileImage = "https://via.placeholder.com/640x640.png?text=$ha_users->firstname+$ha_users->lastname";
    $ha_users->address = '';
    $ha_users->email_verified = 0;
    $ha_users->sms_verified = 0;

    // Generate random activation token
    $token = md5(rand() . time()) . uniqid();
    $ha_users->verification_code = $token;
    $ha_users->verification_code_send_at = date('Y-m-d H:m:s');
    $ha_users->status = 1;
    $ha_users->two_factor_status = 0;
    $ha_users->two_factor_verified = 1;


    $ha_users->roles = 'user';
    $ha_users->remember_token = 'false';
    $ha_users->created_at = date('Y-m-d H:m:s');
    $ha_users->updated_at = date('Y-m-d H:m:s');
    $lastInsertedId = $ha_users->create();

    // create the ha_users
    if ($lastInsertedId != 0) {
        $sign_up_message = array("status" => "success", "code" => 1, "message" => "Account Created Successfully! A verification link has been sent to your email $ha_users->email", "data" => $lastInsertedId);

        if (trim($_ENV["HA_APP_ENV"]) === "DEV") {
            //Send Account Activation Email
            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host = trim($_ENV['HA_SMTP_SERVER']) || "in-v3.mailjet.com";                     //Set the SMTP server to send through
                $mail->SMTPAuth = true;                                   //Enable SMTP authentication
                $mail->Username = trim($_ENV['HA_SMTP_USERNAME']) || "92dd3f94e09c5819cc47ee1f59b90824";                     //SMTP username
                $mail->Password = trim($_ENV['HA_SMTP_PASSWORD']) || "925dbe0e175350b9c349ebe2c2489241";                               //SMTP password
                $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
                $mail->CharSet = "utf-8";// set charset to utf8
                $mail->SMTPKeepAlive = true;
                $mail->Port = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('no_reply@houseafrica.io', 'HA SUPPORT-TEAM');
                $mail->addAddress($data->email, $data->firstname . ' ' . $data->lastname);     //Add a recipient


                //Attachments
                //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add HouseAfrica Logo
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

                //Content
                $activate_link = 'http://houseafrica.com/marketplace/activate.php?email=' . $data->email . '&code=' . $token;
                $message = '<p>Please click the following link to activate your account: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';


                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Account Activation Required';
                $mail->Body = $message;
                $mail->AltBody = 'Please copy and past link below on a browser,' . $activate_link;

                $mail->send();
                //echo 'Message has been sent';
            } catch (phpmailerException $e) {
                $sign_up_message["email_message"] = "An error occurred. {$e->errorMessage()}"; //Catch errors from PHPMailer.
            } catch (Exception $e) {
                $sign_up_message["email_message"] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"; //Catch errors from Amazon SES.
            }
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
