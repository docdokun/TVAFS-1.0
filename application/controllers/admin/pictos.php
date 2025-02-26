<?php

session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pictos extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_admin')) {
            redirect('admin/connexion', 'refresh');
        }
        $this->load->model('ImagePicto');
    }

    public function add() {
        $this->load->view('admin/picto/upload/upload_image_picto.php');
    }

    public function delete() {

        if (!$this->input->post('id')) {
            redirect('admin/picto/liste', 'refresh');
        }

        $this->ImagePicto->setId($this->input->post('id'));
        $data["pictos"] = $this->ImagePicto->getPicto();
        if ($this->ImagePicto->deletePicto()) {
            $this->load->view('admin/picto/upload/delete_image_picto.php', $data);
        } else {
            echo -1;
        }
    }

    public function liste() {
        $data["adminJs"] = array("picto/picto");
        $this->load->helper(array('form'));
        $data["pictos"] = $this->ImagePicto->getPictos();
        $this->load->templateAdmin('/picto/list_picto', $data);
    }

}
