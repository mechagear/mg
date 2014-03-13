<?php

class Mg_Shop_Model_Item extends Mg_Model_Abstract 
{
    protected $_id_item;
    protected $_id_shop;
    protected $_id_category;
    protected $_url;
    protected $_name;
    protected $_marking;
    protected $_id_user;
    protected $_title;
    protected $_description;
    protected $_keywords;
    protected $_id_status;
    
    // getters
    
    public function getIdItem() {
        return $this->_id_item;
    }
    
    public function getIdShop() {
        return $this->_id_shop;
    }
    
    public function getIdCategory() {
        return $this->_id_category;
    }
    
    public function getUrl() {
        return $this->_url;
    }
    
    public function getName() {
        return $this->_name;
    }
    
    public function getMarking() {
        return $this->_marking;
    }
    
    public function getIdUser() {
        return $this->_id_user;
    }
    
    public function getTitle() {
        return $this->_title;
    }
    
    public function getDescription() {
        return $this->_description;
    }
    
    public function getKeywords() {
        return $this->_keywords;
    }
    
    public function getIdStatus() {
        return $this->_id_status;
    }
    
    // setters
    
    public function setIdItem($iIdItem) {
        $this->_id_item = intval($iIdItem);
    }
    
    public function setIdShop($iIdShop) {
        $this->_id_shop = intval($iIdShop);
    }
    
    public function setIdCategory($iIdCategory) {
        $this->_id_category = intval($iIdCategory);
    }
    
    public function setUrl($sUrl) {
        $this->_url = $sUrl;
    }
    
    public function setName($sName) {
        $this->_name = $sName;
    }
    
    public function setMarking($sMarking) {
        $this->_marking = $sMarking;
    }

    public function setIdUser($iIdUser) {
        $this->_id_user = intval($iIdUser);
    }
    
    public function setTitle($sTitle) {
        $this->_title = $sTitle;
    }
    
    public function setDescription($sDescription) {
        $this->_description = $sDescription;
    }
    
    public function setKeywords($sKeywords) {
        $this->_keywords = $sKeywords;
    }

    public function setIdStatus($iIdStatus) {
        $this->_id_status = intval($iIdStatus);
    }
}
