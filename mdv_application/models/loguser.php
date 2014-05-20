<?php

/**
 * Description 
 *
 * @author Niyas <niyast@live.com>
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Loguser extends CI_Model {

    public function checkUser($id = "") {

        $this->db->select(" * ");
        $this->db->from("users");
        $this->db->where("g_user_id = '" . $id . "'");
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    function addUser($data) {

        $try = $this->db->insert('users', $data);
        if ($try) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

}

?>
