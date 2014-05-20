<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        
//        $this->config->load("application");
//        $this->user = get_userdata("user_logged_in");    
        //if (!user_logged_in())
           // redirect('/login', 'refresh');
    }
//
//    public function template($template_name, $vars = array(), $return = FALSE) {
//        $data["user"] = $this->user;
//        $content = $this->load->view('header', $data, $return);
//        if (is_array($template_name)) {
//            foreach ($template_name as $template) {
//                $content .= $this->load->view($template, $vars, $return);
//            }
//        } else {
//            $content .= $this->load->view($template_name, $vars, $return);
//        }
//        $content .= $this->load->view('footer', $vars, $return);
//
//        if ($return) {
//            return $content;
//        }
//   }
}

?>
