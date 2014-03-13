<?php

class Mg_Base_Model_Iblock extends Mg_Model_Abstract
{
    protected $_id_iblock;
    protected $_name;
    protected $_code;
    
    // getters
    
    public function getIdIblock() {
        return $this->_id_iblock; 
    }
    
    public function getName() {
        return $this->_name;
    }
    
    public function getCode() {
        return $this->_code;
    }
    
    
    // setters
    
    public function setIdIblock($iIdIblock) {
        $this->_id_iblock = intval($iIdIblock);
    }
    
    public function setName($sName) {
        $this->_name = trim($sName);
    }
    
    public function setCode($sCode) {
        $this->_code = trim($sCode);
    }
}
