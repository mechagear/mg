<?php

class Mg_Cars_Model_CarMaker extends Mg_Model_Abstract
{
    protected $_id_maker;
    protected $_name;
    protected $_id_status;
    
    // getters
    
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
