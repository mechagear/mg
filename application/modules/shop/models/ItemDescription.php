<?php

class Mg_Shop_Model_ItemDescription extends Mg_Model_Abstract 
{
    protected $_id_description;
    protected $_id_item;
    protected $_code;
    protected $_text;
    
    // getters
    
    public function getIdDescription() {
        return $this->_id_description;
    }
    
    public function getIdItem() {
        return $this->_id_item;
    }
    
    public function getCode() {
        return $this->_code;
    }
    
    public function getText() {
        return $this->_text;
    }
    
    // setters
    
    public function setIdDescription($iIdDescription) {
        $this->_id_description = intval($iIdDescription);
    }
    
    public function setIdItem($iIdItem) {
        $this->_id_item = intval($iIdItem);
    }
    
    public function setCode($sCode) {
        $this->_code = strtoupper($sCode);
    }
    
    public function setText($sText) {
        $this->_text = $sText;
    }
    
}
