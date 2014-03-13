<?php

class Mg_Shop_Model_Category extends Mg_Model_Abstract 
{
    protected $_id_category;
    protected $_id_shop;
    protected $_id_parent;
    protected $_url;
    protected $_name;
    protected $_id_user;
    protected $_id_status;
    
    // getters
    public function getIdCategory() {
        return $this->_id_category;
    }
    
    public function getIdShop() {
        return $this->_id_shop;
    }
    
    public function getIdParent() {
        return $this->_id_parent;
    }
    
    public function getUrl() {
        return $this->_url;
    }
    
    public function getName() {
        return $this->_name;
    }
    
    public function getIdUser() {
        return $this->_id_user;
    }
    
    public function getIdStatus() {
        return $this->_id_status;
    }
    
    // setters
    
    public function setIdCategory($iIdCategory) {
        $this->_id_category = intval($iIdCategory);
    }
    
    public function setIdShop($iIdShop) {
        $this->_id_shop = intval($iIdShop);
    } 

    public function setIdParent($iIdParent) {
        $this->_id_parent = intval($iIdParent);
    }
    
    public function setUrl($sUrl) {
        $this->_url = $sUrl;
    }
    
    public function setName($sName) {
        $this->_name = $sName;
    }

    public function setIdUser($iIdUser) {
        $this->_id_user = intval($iIdUser);
    }

    public function setIdStatus($iIdStatus) {
        $this->_id_status = intval($iIdStatus);
    }
}
