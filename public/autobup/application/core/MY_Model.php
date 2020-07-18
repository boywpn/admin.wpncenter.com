<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Model extends CI_Model {

    public function now() {
        return date('Y-m-d H:i:s');
    }
    
}