<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customers extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    //all customer List
    public function all_employee_list() {
        $this->db->select('employee_id, employee_name');
        $this->db->from('employee_information');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}
?>