<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    var $module_config = array();
    var $required_field = array();
    var $api_master_key = 'WPN_ACCESS_TO_API';
    var $show_datareq = false;
    var $show_resptime = true;
    var $data;

    function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Bangkok");

        $this->benchmark->mark('api_start');
        $this->clear_cache();
        # set input data
        $this->raw_data = json_decode($this->input->raw_input_stream, true);
        $this->get_data = $this->input->get();
        $this->post_data = $this->input->post();

        # set default header
        $this->data->header = 200;
        $this->data->status = 1;
        $this->data->message = '';
        $this->data->data = [];

        /* HTTP Respone code
        * 
        * 200	OK
        * 201	Created
        * 304	Not Modified
        * 400	Bad Request
        * 401	Unauthorized
        * 403	Forbidden
        * 404	Not Found
        * 422	Unprocessable Entity
        * 500	Internal Server Error
        */
    }

    function checkPermission($activate = 1)
    {
        if (@$_REQUEST['api_key'] != $this->api_master_key)
        {
            $this->data->header = 403;
            $this->data->status = 1;
            $this->data->message = 'Unauthorized';
            $this->renderJson($this->data, 403);
        }
    }

    /* Authorization with API_KEY */
    function checkPermissionJson($activate = 1, $rawdata = array())
    {
        if (@$rawdata['api_key'] != $this->api_master_key && $activate == 1)
        {
            $this->data->header = 403;
            $this->data->status = 1;
            $this->data->message = 'Unauthorized';
            $this->renderJson($this->data, 403);
        }
    }

    /* Authorization with Header bearer */
    function header_auth($active = true) {
        if($active) {
            $getToken = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            $getToken = explode(' ', $getToken)[1];
            if($getToken) {
                if($getToken != md5($this->api_master_key)) {
                    $this->data->header = 403;
                    $this->data->status = 1;
                    $this->data->message = 'Unauthorized';
                    $this->renderJson($this->data, 403);
                }
            } else {
                $this->data->header = 403;
                $this->data->status = 1;
                $this->data->message = 'Unauthorized';
                $this->renderJson($this->data, 403);
            }
        }
    }

    function re_validate($required_field, $input) {
        $required_is = array();
        if (count($required_field) > 0):
            $check_point = 0;
            $data['message'] .= 'The fields \'';
            foreach ($required_field as $row):
                if ($input[$row] == ''):
                    $required_is[] = $row;
                    $check_point++;
                endif;
            endforeach;
            $data['check_point'] = $check_point;
            $data['message'] .= implode(', ', $required_is);
            $data['message'] .= '\' is required.';
            return $data;
        endif;
    }


    function accept_method($method = 'post') {
        if(is_array($method) && in_array(strtolower($_SERVER['REQUEST_METHOD']), $method)) {
            return true;
        } else {
            $this->data->header = 400;
            $this->data->status = 1;
            $this->data->message = 'Method not allow.';
            $this->renderJson($this->data, $this->data->header);
        }
    }

    function clear_cache()
    {
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }

    function renderJson($data = array(), $http = 200)
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=utf-8');
        # check data
        (is_object($data) ? $data = (array)$data:''); 
        # End API Response time
        $this->benchmark->mark('api_end');
        $data['message'] = @strip_tags($data['message']);
        ($this->show_resptime ? $data['response_time'] = $this->benchmark->elapsed_time('api_start', 'api_end') : '');
        ($this->show_datareq ? $data['data_request'] = array_merge(@$_REQUEST, json_decode($this->input->raw_input_stream, true)) : '');

        # Replace Null value 

        function replace_null_with_empty_string($array)
        {
            foreach ($array as $key => $value)
            {
                if (is_array($value))
                    $array[$key] = replace_null_with_empty_string($value);
                else
                {
                    if (is_null($value))
                        $array[$key] = "";
                }
            }
            return $array;
        }

        $data = replace_null_with_empty_string($data);

        $this->output->set_status_header($http)
                ->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT')
                ->set_header('Cache-Control: no-store, no-cache, must-revalidate')
                ->set_header('Cache-Control: post-check=0, pre-check=0')
                ->set_header('Pragma: no-cache')
                ->set_content_type('application/json', 'UTF-8')
                ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                ->_display();
        exit;
    }

    function now() {
        return date('Y-m-d H:i:s');
    }

}