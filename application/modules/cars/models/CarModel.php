<?php

class Mg_Cars_Model_CarModel extends Mg_Model_Abstract
{
    protected $_id_model;
    protected $_id_maker;
    protected $_name;
    protected $_id_status;
    
    // getters
    
    public function getIdModel() {
        return $this->_id_model;
    }
    
    public function getIdMaker() {
        return $this->_id_maker;
    }
    
    public function getName() {
        return $this->_name;
    }
    
    public function getIdStatus() {
        return $this->_id_status;
    }
    
    // setters
    
    public function setIdModel($iIdModel) {
        $this->_id_model = intval($iIdModel);
    }
    
    public function setIdMaker($iIdMaker) {
        $this->_id_maker = intval($iIdMaker);
    }
    
    public function setName($sName) {
        $this->_name = trim($sName);
    }
    
    public function setIdStatus($iIdStatus) {
        $this->_id_status = intval($iIdStatus);
    }
}
