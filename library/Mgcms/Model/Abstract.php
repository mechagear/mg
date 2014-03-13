<?php

abstract class Mgcms_Model_Abstract
{
    protected $aValidationErrors = array();
    
    public function __construct($aParams = array()) {
        if ( !empty($aParams) && is_array($aParams) ) {
            $this->setParams($aParams);
        }
    }
    
    public function __set($sName, $sValue) {
        $sMethod = 'set' . $this->_constructMethodName($sName);
        if ( !method_exists($this, $sMethod) ) {
            throw new Exception('No method');
        }
        $this->$sMethod($sValue);
    }
    
    public function __get($sName) {
        $sMethod = 'get' . $this->_constructMethodName($sName);
        if ( !method_exists($this, $sMethod) ) {
            throw new Exception('No method');
        }
        return $this->$sMethod();
    }
    
    public function setParams($aParams) {
        $aMethods = get_class_methods($this);
        foreach ($aParams as $sName => $sValue) {
            $sMethod = 'set' . $this->_constructMethodName($sName);//'set' . ucfirst($sName);
            if ( in_array($sMethod, $aMethods) ) {
                $this->$sMethod($sValue);
            }
        }
        return $this;
    }
    
    public function getParams() {
        $aVars = get_object_vars($this);
        $aResult = array();
        foreach ($aVars as $sKey => $sValue) {
            if ( '_' == substr($sKey, 0, 1) ) {
                $aResult[substr($sKey, 1)] = $sValue;
            }
        }
        return $aResult;
    }
    
    protected function _constructMethodName($sName) {
        $aName = explode('_', $sName);
        $sMethod = '';
        foreach ($aName as $sNamePart) {
            if ( empty($sNamePart) ) {
                continue;
            }
            $sNamePart = ucfirst($sNamePart);
            $sMethod .= $sNamePart;
        }
        return $sMethod;
    }
    
    /**
     * Validation
     */
    
    public function validateField($sField, $aValues = array()) {
        $sValidator = 'validate' . $this->_constructMethodName($sField);
        if ( !method_exists($this, $sValidator) ) {
            return true;
        } else {
            return $this->$sValidator($aValues);
        }
    }
    
    public function validateAll() {
        $aParams = $this->getParams();
        $bValid = true;
        foreach ($aParams as $sField => $sValue) {
            $bFieldValid = $this->validateField($sField);
            $bValid = ($bFieldValid) ? $bValid : false;
        }
        return $bValid;
    }
    
    public function getValidationErrors($sField = null) {
        if ( !empty($sField) ) {
            return (!empty($this->aValidationErrors[$sField])) ? $this->aValidationErrors[$sField] : array();
        } else {
            return $this->aValidationErrors;
        }
    }
}