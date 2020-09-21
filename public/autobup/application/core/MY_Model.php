<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Model extends CI_Model {
    var $odb;

    function __construct() {
        parent::__construct();
        # load old database
        $this->odb = $this->load->database('production_old', TRUE);
    }
    
    public function now() {
        return date('Y-m-d H:i:s');
    }
    
}