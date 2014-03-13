<?php

class Mg_Cars_Model_CarSubmodel extends Mg_Model_Abstract
{
    protected $_id_submodel;
    protected $_id_model;
    protected $_name;
    protected $_id_status;
    
    // getters
    
    public function getIdSubmodel() {
        return $this->_id_submodel;
    }
    
    public function getIdModel() {
        return $this->_id_model;
    }
    
    public function getName() {
        return $this->_name;
    }
    
    public function getIdStatus() {
        return $this->_id_status;
    }
    
    // setters
    
    public function setIdSubmodel($iIdSubmodel) {
        $this->_id_submodel = intval($iIdSubmodel);
    }
    
    public function setIdModel($iIdModel) {
        $this->_id_model = intval($iIdModel);
    }
    
    public function setName($sName) {
        $this->_name = trim($sName);
    }
    
    public function setIdStatus($iIdStatus) {
        $this->_id_status = intval($iIdStatus);
    }
}
