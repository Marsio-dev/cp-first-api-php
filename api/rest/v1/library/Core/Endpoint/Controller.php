<?php

class rest_v1_library_Core_Endpoint_Controller {

    private string  $api_ground_route   = 'rest_v1_library_Core_Endpoint_';
    private string  $access_token       = 'HALLO_ICH_BIN_DER_ACCESS_TOKEN';
    private int     $http_response_code = 200;

    public function __construct(array $data = [])
    {
        // $data = escapeInput($data);
        $this->init($data);
    }

    private function init(array $data = []) {
        try {
            if(!isset($data['access_token']) || empty($data['access_token']) || $data['access_token'] !== $this->access_token) {
                throw new Exception('UNAUTHORIZED', 401);
            }

            if(!isset($data['route']) || empty($data['route'])) {
                throw new Exception('BAD REQUEST (No route)', 400);
            }

            $class_name = $this->getFullClassName(escapeInput($data['route']));
            if(!class_exists($class_name)) {
                throw new Exception('BAD REQUEST (Route does not exist)', 400);
            }

            $class = new $class_name($data);
            $class->validate();
            $execute = $class->execute();

            if(!is_array($execute)) {
                $execute = [$execute];
            }

            if(!isset($execute['status'])) {
                $execute = array_merge(['status' => 'success'], $execute);
            }

            http_response_code((int) $this->http_response_code);
            exit(json_encode($execute));
        } catch (Throwable | Exception $e) {
            http_response_code(!empty($e->getCode()) ? (int) $e->getCode() : 500);
            exit(json_encode(['status' => 'error', 'message' => 'INTERNAL_SERVER_ERROR', 'debug_info_controller' => $e->getMessage()]));
        }
    }


    private function getFullClassName(string $route = ''): string {
        $exp = explode('/', trim(trim(trim($route), '/')));
        $class_name = $this->api_ground_route;

        foreach($exp as $value) {
            if(!empty($value)) {
                $class_name .= ucfirst($value) . '_';
            }
        }
        return substr($class_name, 0, -1);
    }
}