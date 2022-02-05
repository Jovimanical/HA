<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();

$filename = isset($_GET['filename']) ? $_GET['filename'] : die();
try{
$dir = "upload/";
$type = pathinfo($filename, PATHINFO_EXTENSION);
$path=$dir.$filename;
if(file_exists($path)){
$imgData = base64_encode(file_get_contents($path));
if(!empty($imgData)){
$base64 = 'data: '.mime_content_type($path).';base64,'.$imgData;

 http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "File found","data"=> $base64));
}else{
	
	http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No file found.","data"=> ""));
}
}else{
	
	http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No file found.","data"=> ""));
}
}
catch(Exception $e) {
	http_response_code(500);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Error","data"=> $e->getMessage()));
}
?>
