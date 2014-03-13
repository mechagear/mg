<?php

class Mg_Base_Model_Page extends Mg_Model_Abstract implements Zend_Acl_Resource_Interface
{
    protected $_id_page;
    protected $_domain_key;
    //protected $_id_category;
    protected $_url;
    protected $_name;
    protected $_date_create;
    protected $_date_change;
    protected $_id_user;
    protected $_short_text;
    protected $_text;
    protected $_title;
    protected $_description;
    protected $_keywords;
    protected $_redirect;
    protected $_redirect_code;
    protected $_id_status;
    
    
    public function getResourceId() {
        return 'page';
    }
    
    // getters
    public function getIdPage() {
        return $this->_id_page;
    }
    
    public function getDomainKey() {
        return $this->_domain_key;
    }
    /*
    public function getIdCategory() {
        return $this->_id_category;
    }
    */
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
    
    public function getRedirect() {
        return $this->_redirect;
    }
    
    public function getRedirectCode() {
        return $this->_redirect_code;
    }
    
    public function getIdStatus() {
        return $this->_id_status;
    }
    
    // setters
    public function setIdPage($iIdPage) {
        $this->_id_page = intval($iIdPage);
    }
    
    public function setDomainKey($sDomainKey) {
        $this->_domain_key = ($sDomainKey);
    }
    /*
    public function setIdCategory($iIdCategory) {
        $iIdCategory = intval($iIdCategory);
        $this->_id_category = ($iIdCategory > 0) ? $iIdCategory : null;
    }
    */
    public function setUrl($sUrl) {
        $this->_url = strtolower(trim($sUrl));
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
    
    public function setRedirect($sRedirect) {
        $this->_redirect = $sRedirect;
    }
    
    public function setRedirectCode($iRedirectCode) {
        $this->_redirect_code = intval($iRedirectCode);
    }
    
    public function setIdStatus($iIdStatus) {
        $this->_id_status = intval($iIdStatus);
    }
    
    // validators
    
    public function validateIdUser($aValues) {
        $iIdUser = $this->id_user;
        if ( !empty($aValues) && is_array($aValues) ) {
            $iIdUser = !empty($aValues['id_user']) ? intval($aValues['id_user']) : $iIdUser;
        }
        $oChain = new Zend_Validate();
        $oChain->addValidator(new Zend_Validate_Digits());
        $oChain->addValidator(new Zend_Validate_GreaterThan(0));
        if (!$oChain->isValid($iIdUser)) {
            $this->aValidationErrors['id_user'] = $oChain->getMessages();
            return false;
        } else {
            return true;
        }
    }
    
    public function validateName($aValues) {
        $sName = strval($this->name);
        if ( !empty($aValues) && is_array($aValues) ) {
            $sName = ( !empty($aValues['name']) ) ? $aValues['name'] : $sName;
        }
        $oChain = new Zend_Validate();
        $oChain->addValidator(new Zend_Validate_StringLength(array('min'=>1,)));
        if (!$oChain->isValid($sName)) {
            $this->aValidationErrors['name'] = $oChain->getMessages();
            return false;
        } else {
            return true;
        }
    }
    
    public function validateUrl($aValues) {
        $sUrl = strval($this->url);
        $sDomainKey = strval($this->domain_key);
        $iIdPage = intval($this->id_page);
        
        $oPageMapper = new Mg_Base_Model_Mapper_Page();
        $sExclude = "domain_key = '{$sDomainKey}' AND id_page != '{$iIdPage}'";
        
        $oChain = new Zend_Validate();
        $oChain->addValidator(new Zend_Validate_StringLength(array('min'=>2, 'max'=>255)));
        $oChain->addValidator(new Zend_Validate_Regex(array('pattern'=>'/^[-_0-9a-z]+$/i')));
        $oChain->addValidator(new Zend_Validate_Db_NoRecordExists(array('table'=>$oPageMapper->getDbTable()->getTable(),'field'=>'url', 'adapter'=>$oPageMapper->getDbTable()->getAdapter(), 'exclude'=>$sExclude)));
        if (!$oChain->isValid($sUrl)) {
            $this->aValidationErrors['url'] = $oChain->getMessages();
            return false;
        } else {
            return true;
        }
    }
    
}
