<?php

class Mg_Base_Model_IblockElement extends Mg_Model_Abstract implements Zend_Acl_Resource_Interface
{
    protected $_id_element;
    protected $_id_category;
    protected $_url;
    protected $_name;
    protected $_date_create;
    protected $_date_change;
    protected $_date_publish;
    protected $_id_user;
    protected $_short_text;
    protected $_text;
    protected $_title;
    protected $_description;
    protected $_keywords;
    protected $_id_status;
    
    
    public function getResourceId() {
        return 'iblock_element';
    }
    
    // getters
    public function getIdElement() {
        return $this->_id_element;
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
    
    public function getDateCreate() {
        return $this->_date_create;
    }
    
    public function getDateChange() {
        return $this->_date_change;
    }
    
    public function getDatePublish() {
        return $this->_date_publish;
    }
    
    public function getIdUser() {
        return $this->_id_user;
    }
    
    public function getShortText() {
        return $this->_short_text;
    }
    
    public function getText() {
        return $this->_text;
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
    public function setIdElement($iIdElement) {
        $this->_id_element = intval($iIdElement);
    }
    
    public function setIdCategory($iIdCategory) {
        $this->_id_category = intval($iIdCategory);
    }
    
    public function setUrl($sUrl) {
        $this->_url = trim($sUrl);
    }
    
    public function setName($sName) {
        $this->_name = trim($sName);
    }
    
    public function setShortText($sShortText) {
        $this->_short_text = $sShortText;
    }
    
    public function setText($sText) {
        $this->_text = $sText;
    }
    
    public function setDateCreate($sDateCreate) {
        $this->_date_create = $sDateCreate;
    }
    
    public function setDateChange($sDateChange) {
        $this->_date_change = $sDateChange;
    }
    
    public function setDatePublish($sDatePublish) {
        $this->_date_publish = Mg_Common_Helper_Date::toDefault($sDatePublish);
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
