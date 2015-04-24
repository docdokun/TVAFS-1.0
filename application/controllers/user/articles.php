<?php

session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Articles extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('user/account/connexion', 'refresh');
        }
        $this->load->helper(array('form'));
        $this->load->model('article');
    }

    public function add() {
        if (!$this->input->get('id_carnet_voyage')) {
            echo "0";
        } else {
            $this->load->helper(array('form'));
            $data["librairieCss"] = array("font-awesome.min", "froala_editor.min", "froala_style.min");
            $data["librairieJs"] = array(
                "froala_editor.min",
                "plugins/tables.min",
                "plugins/lists.min",
                "plugins/colors.min",
                "plugins/media_manager.min",
                "plugins/font_family.min",
                "plugins/font_size.min",
                "plugins/block_styles.min",
                "plugins/video.min",
                "langs/fr"
            );
            $data["id_carnet_voyage"] = $this->input->get('id_carnet_voyage');
            $this->load->view('user/article/add_article', $data);
        }
    }

    public function edit() {
        if (!$this->input->get('id')) {
            echo "0";
        } else {
            $data["librairieCss"] = array("font-awesome.min", "froala_editor.min", "froala_style.min");
            $data["librairieJs"] = array(
                "froala_editor.min",
                "plugins/tables.min",
                "plugins/lists.min",
                "plugins/colors.min",
                "plugins/media_manager.min",
                "plugins/font_family.min",
                "plugins/font_size.min",
                "plugins/block_styles.min",
                "plugins/video.min",
                "langs/fr"
            );
            $this->article->id = $this->input->get('id');
            $data["article"] = $this->article->getArticle();
            $this->load->helper(array('form'));
            $this->load->view('user/article/edit_article', $data);
        }
    }

    public function liste() {
        if (!$this->input->post('id')) {
            echo "0";
        }
        $this->load->helper(array('form'));
        $this->article->id_carnetvoyage = $this->input->post('id');
        $data["articles"] = $this->article->getArticles();
        $data["id_carnet_voyage"] = $this->input->post('id');
        $this->load->view('user/article/list_article', $data);
    }

    public function upload() {
        $this->load->view('user/article/upload_image.php');
    }

    public function delete() {
        $this->load->view('user/article/delete_image.php');
    }

}