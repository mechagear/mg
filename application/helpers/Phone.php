<?php

class Mg_Common_Helper_Phone
{
    public static function formatPhone($sPhone) {
        $sPhone = strtolower($sPhone);
        $sPreg = '/[^0-9]/i';
        $sPhone = preg_replace($sPreg, '', $sPhone);
        
        $sCountryCode = substr($sPhone, 0, 1);
        
        if ( ($sCountryCode == 7 || $sCountryCode == 8) && strlen($sPhone) > 10 ) {
            $sPhone = substr($sPhone, 1, 10);
        }
        return $sPhone;
    }
    
    public static function formatPhoneView($sPhone) {
        $aParts = array();
        $aParts[] = substr($sPhone, 0, 3);
        $aParts[] = substr($sPhone, 3, 3);
        $aParts[] = substr($sPhone, 6, 2);
        $aParts[] = substr($sPhone, 8, 2);
        return implode('-', $aParts);
    }
    
    public static function getSMSCode($iMaxLength = 5) {
        $iTime = time();
        $sRawCode = strtoupper(md5($iTime));
        $iMaxBeginIndex = strlen($sRawCode) - $iMaxLength;
        if ( $iMaxBeginIndex < 0 ) {
            $iMaxLength = strlen($sRawCode);
            $iMaxBeginIndex = 0;
        }
        $iRealBeginIndex = rand(0, $iMaxBeginIndex);
        $sCode = substr($sRawCode, $iRealBeginIndex, $iMaxLength);
        return $sCode;
    }
}
