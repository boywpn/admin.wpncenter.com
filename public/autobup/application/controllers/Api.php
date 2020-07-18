<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

    var $model_name = 'Api_model';
    var $page_title = "Api";
    var $output_data = array();

    function __construct() {
        parent::__construct();
        $this->load->library('user_agent');
        $this->load->model($this->model_name, 'models');

        # Define value by Dynamic
        foreach($this->raw_data as $key => $row) {
            $this->${key} = $row;
        }

        # Initial message
        $this->data->status = 1;
        $this->data->message = 'data_not_found';
        $this->data->data = [];
    }

    public function index() {
        $this->data->message = 'WPN Autoscript API (v1.0.21)';
        $this->renderJson($this->data, $this->data->header);
    }

    public function save_gameresult() {
        # setting method type
        $this->accept_method(['post']);

        # require permission or not
        $this->header_auth(true);

        # SET Required field
        $this->required_field = array('game_result', 'betlist_id');

        # Validate
        $validate_result = $this->re_validate($this->required_field, $this->raw_data);

        # Checkpont and process
        if ($validate_result['check_point'] > 0):
            $this->data->message = $validate_result['message'];
        else:
            $resultIns = $this->main->insert_function($this->game_result, $this->betlist_id);
            
            if ($resultIns):
                $this->data->status = 0;
                $this->data->message = 'save_successful';
                $this->data->data = $resultIns;
            else:
                $this->data->status = 1;
                $this->data->message = 'save_failed';
            endif;
        endif;

        $this->renderJson($this->data, $this->data->header);
    }

}