<?php

class Mg_Common_Helper_Identity
{
    public static function getIdentityType($sIdentity) {
        if ( self::isEmail($sIdentity) ) {
            return 'email';
        }
        if ( self::isPhone($sIdentity) ) {
            return 'phone';
        }
        return false;
    }
    
    public static function isEmail($sIdentity) {
        $sIdentity = strtolower($sIdentity);
        $oValidator = new Zend_Validate();
        $oValidator->addValidator(new Zend_Validate_EmailAddress());
        return $oValidator->isValid($sIdentity);
    }
    
    public static function isPhone($sIdentity) {
        $sIdentity = self::formatPhone($sIdentity);
        $oValidator = new Zend_Validate();
        $oValidator->addValidator(new Zend_Validate_Digits());
        $oValidator->addValidator(new Zend_Validate_StringLength(10));
        return $oValidator->isValid($sIdentity);
    }
    
    public static function formatPhone($sPhone) {
        return Mg_Common_Helper_Phone::formatPhone($sPhone);
    }
}
