<?php

/**
 * Created by PhpStorm.
 * User: steven
 * Date: 15/03/15
 * Time: 18:47
 */
Class Voyage extends CI_Model {

    private $id;
    private $input = array('titre', 'phrase_accroche', 'phrase_accroche_slider', 'duree', 'description_first_bloc', 'description_second_bloc', 'description_third_bloc', 'lattitude', 'longitude', 'image_sous_slider');
    private $data = array();
    private $visible;

    function __construct() {
        parent::__construct();
    }

    function addVoyage() {
        $this->db->insert('voyage', $this->data);
        return $this->db->insert_id();
    }

    function editerVoyage() {

        if ($this->data["image_sous_slider"] == null) {
            unset($this->data["image_sous_slider"]);
        }
        $this->db->where('id', $this->id);
        $this->db->update('voyage', $this->data);

        return true;
    }

    function getVoyage() {
        $this->db->select('*');
        $this->db->from('voyage');
        $this->db->where('id', $this->id);
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    function setVoyageVisible() {
        $data = array(
            'visible' => $this->visible
        );
        $this->db->where('id', $this->id);
        if ($this->db->update('voyage', $data) == 1) {
            return true;
        } else {
            return false;
        }
    }

    function getVoyageFiche() {
        $this->db->select('*');
        $this->db->from('voyage');
        $this->db->where('id', $this->id);
        $this->db->where('visible', "1");
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getVoyages() {
        $this->db->select('id, titre, visible');
        $this->db->from('voyage');
        $this->db->order_by("id", "desc");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getVoyageOrderInfo($id) {
        $this->db->select('v.visible,v.id as vId,v.titre as titre,o.prix_total as prix_total,iv.date_depart as date_depart,iv.date_arrivee as date_arrivee,o.nb_participant as nb_participant,o.payment as payment,o.id as id,o.statut as statut');
        $this->db->from('voyage as v');
        $this->db->join('order as o', 'v.id = o.id_voyage', 'inner');
        $this->db->join('info_voyage as iv', 'v.id = iv.id_voyage', 'inner');
        $this->db->where('o.id_utilisateur', $id);
        $this->db->group_by("v.id");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getVoyagesHome() {
        $this->db->_protect_identifiers = false;
        $this->db->select('v.id as vId, v.titre as titre, v.phrase_accroche, phrase_accroche, i.nom as nom, i.lien as lien');
        $this->db->from('voyage AS v');
        $this->db->join("images AS i", "emplacement = 'image_slider' AND i.id_voyage = v.id", "inner");
        $this->db->order_by("v.id", "desc");
        $this->db->where("v.visible", "1");
        $this->db->group_by("v.id");
        
        $this->db->_protect_identifiers = TRUE;
        $this->db->limit(4);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getVoyagesHomeWithID($id) {
        $this->db->_protect_identifiers = false;
        $this->db->select('v.id as vId, v.titre as titre, v.phrase_accroche, phrase_accroche, i.nom as nom, i.lien as lien');
        $this->db->from('voyage AS v');
        $this->db->join("images AS i", "emplacement = 'image_slider' AND i.id_voyage = v.id", "inner");
        $this->db->or_where('v.id', $id);
        $this->db->where('v.visible', '1');
        $this->db->_protect_identifiers = TRUE;

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getVoyagesCustomer() {
        $this->db->select('id, titre, prix');
        $this->db->from('voyage');
        $this->db->where('id', $this->id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getImageSousSlider() {
        $this->db->select('image_sous_slider');
        $this->db->from('voyage');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getAllVoyages($limit, $start) {


        $this->db->_protect_identifiers = false;  //empeche l'ajout de quotes ( `` ) automatique
        $this->db->select('v.id as vId, v.titre as titre, v.phrase_accroche, phrase_accroche, i.nom as nom, i.lien as lien');
        $this->db->from('voyage AS v');
        $this->db->join("images AS i", "emplacement = 'image_slider' AND i.id_voyage = v.id", "inner");
        $this->db->where("v.visible", "1");
        $this->db->order_by("v.id", "desc");
        $this->db->group_by("v.id");
        $this->db->_protect_identifiers = TRUE; //remet l'ajout de quotes automatique
        if (isset($limit) && isset($start)) {
            $this->db->limit($limit, $start);
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getRowAllVoyages() {

        $this->db->_protect_identifiers = false;  //empeche l'ajout de quotes ( `` ) automatique
        $this->db->select('*');
        $this->db->from('voyage');
        $this->db->join("images", "emplacement = 'image_slider' AND images.id = voyage.id", "inner");
        $this->db->order_by("titre", "asc");
        $this->db->_protect_identifiers = TRUE; //remet l'ajout de quotes automatique

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return false;
        }
    }

    function getVoyagesByContinent($continent) {
        $this->db->_protect_identifiers = false;
        $this->db->select('v.id as vId, v.titre as titre, v.phrase_accroche, phrase_accroche, i.nom as nom, i.lien as lien');
        $this->db->from('voyage as v');
        $this->db->join("images AS i", "emplacement = 'image_slider' AND i.id_voyage = v.id", "inner");
        $this->db->join("pays as p", "v.id = p.id_voyage");
        $this->db->join("continent as c", "c.id = p.id_continent");
        $this->db->where('c.id', $continent);
        $this->db->where('v.visible', "1");
        $this->db->order_by("v.id", "desc");
        $this->db->group_by('v.id');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function deleteVoyage() {
        $this->db->where('id', $this->id);
        $this->db->delete('voyage');
    }

    function setId($id) {
        $this->id = $id;
    }

    function __set($name, $value) {
        $this->data[$name] = $value;
    }

    function getInput() {
        return $this->input;
    }

    function getInfoVoyageById($id) {
        $this->db->select('*');
        $this->db->from('info_voyage');
        $this->db->where('id', $id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function setVisible($visible) {
        $this->visible = $visible;
    }

}
