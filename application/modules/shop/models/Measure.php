<?php

class Mg_Shop_Model_Measure extends Mg_Model_Abstract 
{
    protected $_id_measure;
    protected $_id_shop;
    protected $_name_full;
    protected $_name_short;
    protected $_forms;
    
    // getters
    
    public function getIdMeasure() {
        return $this->_id_measure;
    }

    public function getIdShop() {
        return $this->_id_shop;
    }
    
    public function getNameFull() {
        return $this->_name_full;
    }
    
    public function getNameShort() {
        return $this->_name_short;
    }
    
    public function getForms() {
        return $this->_forms;
    }
    
    // setters
    
    public function setIdMeasure($iIdMeasure) {
        $this->_id_measure = intval($iIdMeasure);
    }
    
    public function setIdShop($iIdShop) {
        $this->_id_shop = intval($iIdShop);
    } 

    public function setNameFull($sNameFull) {
        $this->_name_full = trim($sNameFull);
    }
    
    public function setNameShort($sNameShort) {
        $this->_name_short = trim($sNameShort);
    }
    
    public function setForms($sForms) {
        $this->_forms = $sForms;
    }
    
    // validators
    
    public function validateNameShort($aParams) {
        $oValidateChain = new Zend_Validate();
        $oValidateChain->addValidator(new Zend_Validate_StringLength(array('min'=>1,'max'=>50)), true);
        if ( !$oValidateChain->isValid($this->name_short) ) {
            $this->aValidationErrors['name_short'] = $oValidateChain->getMessages();
            return false;
        }
        return true;
    }
    
    public function validateNameFull($aParams) {
        $oValidateChain = new Zend_Validate();
        $oValidateChain->addValidator(new Zend_Validate_StringLength(array('min'=>1,'max'=>50)), true);
        if ( !$oValidateChain->isValid($this->name_full) ) {
            $this->aValidationErrors['name_full'] = $oValidateChain->getMessages();
            return false;
        }
        return true;
    }
}
