<?php

class Mg_Model_Status extends Mg_Model_Abstract
{
    protected $_id_status;
    protected $_code;
    protected $_name;
    
    // getters
    
    public function getIdStatus() {
        return $this->_id_status; 
    }
    
    public function getCode() {
        return $this->_code;
    }
    
    public function getName() {
        return $this->_name;
    }
    
    
    // setters
    
    public function setIdStatus($iIdStatus) {
        $this->_id_status = intval($iIdStatus);
    }
    
    public function setCode($sCode) {
        $this->_code = trim($sCode);
    }
    
    public function setName($sName) {
        $this->_name = trim($sName);
    }
}
