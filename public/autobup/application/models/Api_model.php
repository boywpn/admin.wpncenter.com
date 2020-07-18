<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends MY_Model {

    function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
    {
        $sets = array();
        if(strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if(strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if(strpos($available_sets, 'd') !== false)
            $sets[] = '23456789';
        if(strpos($available_sets, 's') !== false)
            $sets[] = '!@#$%&*?';

        $all = '';
        $password = '';
        foreach($sets as $set)
        {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }

        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++)
            $password .= $all[array_rand($all)];

        $password = str_shuffle($password);

        if(!$add_dashes)
            return $password;

        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while(strlen($password) > $dash_len)
        {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }

    public function checkPassword($pwd, &$errors) {
        $errors_init = $errors;
    
        if (strlen($pwd) < 8) {
            $errors[] = "Password too short!";
        }
    
        if (!preg_match("#[0-9]+#", $pwd)) {
            $errors[] = "Password must include at least one number!";
        }
    
        if (!preg_match("#[a-zA-Z]+#", $pwd)) {
            $errors[] = "Password must include at least one letter!";
        }     
    
        return ($errors == $errors_init);
    }

    public function imagesaver($image_data = '', $path = '/uploads/images/'){

        list($type, $data) = explode(';', $image_data); // exploding data for later checking and validating 
    
        if (preg_match('/^data:image\/(\w+);base64,/', $image_data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif
    
            if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                throw new \Exception('invalid image type');
            }
    
            $data = base64_decode($data);
    
            if ($data === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }
    
        $fullname = 'image-' . time() .'.'.$type;
    
        if(file_put_contents(dirname($_SERVER["SCRIPT_FILENAME"]) . $path . $fullname, $data)){
            $result = $fullname;
        }else{
            $result =  false;
        }
        /* it will return image name if image is saved successfully 
        or it will return error on failing to save image. */
        return $result; 
    }

    public function get_allfield($table = '', $except = []) {
        if(count($except)>0) {
            $result = $this->db->query('SHOW FIELDS FROM `' . $table . '` WHERE FIELD NOT IN (\'' . implode('\',\'',$except) . '\');');
        } else {
            $result = $this->db->query('SHOW FIELDS FROM `' . $table . '`;');
        }
        # For PHP v7+
        return (string) implode(',', $result->result_array()['Field']);
    }

    public function get_list($table = '', $data = array(), $field = '', $order = 'desc', $except = array()) {
        $select_field = $this->get_allfield($table, $except);
        $this->db->select($select_field);
        $this->db->from($table);
        if (!empty($data)):
            if (is_array($data)):
                foreach ($data as $key => $rows):
                    $this->db->where($key, $rows);
                endforeach;
            endif;
        endif;
        $this->db->order_by($field, $order);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_listwj($table = '', $data = array(), $join_table, $join_data, $join_left, $select = '', $field = '', $order = 'desc') {
        $this->db->select($select);
        $this->db->from($table);
        if (!empty($data)):
            if (is_array($data)):
                foreach ($data as $key => $rows):
                    $this->db->where($key, $rows);
                endforeach;
            endif;
        endif;
        if($join_table) {
            $this->db->join($join_table, $join_data, $join_left);
        }
        $this->db->order_by($field, $order);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_info($table = '', $data = array(), $except = array()) {
        $select_field = $this->get_allfield($table, $except);
        $this->db->select($select_field);
        $this->db->from($table);
        if (!empty($data)):
            if (is_array($data)):
                foreach ($data as $key => $rows):
                    $this->db->where($key, $rows);
                endforeach;
            endif;
        endif;
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_info_sl($table = '', $data = array(), $field = '*') {
        $this->db->select($field);
        $this->db->from($table);
        if (!empty($data)):
            if (is_array($data)):
                foreach ($data as $key => $rows):
                    $this->db->where($key, $rows);
                endforeach;
            endif;
        endif;
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_info_wj($table = '', $data = array(), $select = '*', $join_table = '', $join_con = '', $join_type = 'left') {
        $this->db->select($select);
        $this->db->from($table);
        if($join_table) {
            $this->db->join($join_table, $join_con, $join_type);
        }
        if (!empty($data)):
            if (is_array($data)):
                foreach ($data as $key => $rows):
                    $this->db->where($key, $rows);
                endforeach;
            endif;
        endif;
        $query = $this->db->get();
        return $query->row_array();
    }

}