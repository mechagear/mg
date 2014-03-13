<?php

class Mg_Base_Model_IblockCategory extends Mg_Model_Abstract
{
    protected $_id_category;
    protected $_id_parent;
    protected $_id_iblock;
    protected $_name;
    protected $_url;
    
    // getters 
    
    public function getIdCategory() {
        return $this->_id_category;
    }
    
    public function getIdParent() {
        return $this->_id_parent;
    }
    
    public function getIdIblock() {
        return $this->_id_iblock;
    }
    
    public function getName() {
        return $this->_name;
    }
    
    public function getUrl() {
        return $this->_url;
    }
    
    
    // setters
    
    public function setIdCategory($iIdCategory) {
        $this->_id_category = intval($iIdCategory);
    }
    
    public function setIdParent($iIdParent) {
        $this->_id_parent = intval($iIdParent);
    }
    
    public function setIdIblock($iIdIblock) {
        $this->_id_iblock = intval($iIdIblock);
    }
    
    public function setName($sName) {
        $this->_name = trim($sName);
    }
    
    public function setUrl($sUrl) {
        $this->_url = trim($sUrl);
    }
}
