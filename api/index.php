<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
// ini_set('error_reporting', E_ALL);

header('Content-Type: application/json');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

try {
    require_once './functions.php';

    $data = json_decode(file_get_contents('php://input'), true);
    $data = is_array($data) && !empty($data) ? $data : [];
    $data = is_array($data) && !empty($_POST) ? array_merge($data, $_POST) : $data;
    $data = is_array($data) && !empty($_GET) ? array_merge($data, $_GET) : $data;

    if (isset($data['access_token'])) {
        unset($data['access_token']);
    }

    $headers            = getallheaders();
    $data               = is_array($data) && isset($headers['Authorization']) && !empty(getAccessToken($headers['Authorization'])) && str_contains($headers['Authorization'], 'Bearer') ? array_merge($data, ['access_token' => getAccessToken($headers['Authorization'])]) : $data;
    $data['base_path']  = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR;

    $controller = new rest_v1_library_Core_Endpoint_Controller($data);
    throw new Exception('UNKNOWN_ERROR', 500);
} catch (Throwable | Exception $e) {
    http_response_code(!empty($e->getCode()) ? (int) $e->getCode() : 500);
    exit(json_encode(['status' => 'error', 'message' => 'INTERNAL_SERVER_ERROR', 'debug_info_index' => $e->getMessage()]));
}
