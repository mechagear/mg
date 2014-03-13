<?php

class Mg_Common_Helper_String 
{
    
    public static function getWords($sString, $iNumWords = 10, $iMaxLength = 0, $sAppendix = '') {
        $sString = trim(strip_tags($sString));
        if (strlen($sString) == 0 ) {
            return '';
        }
        $aWords = array();
        $aString = explode(' ', $sString);
        foreach ( $aString as $sWord ) {
            $sWord = trim($sWord);
            if ( strlen($sWord) == 0 ) {
                continue;
            }
            if ( count($aWords) < $iNumWords ) {
                $aWords[] = $sWord;
            } else {
                break;
            }
        }
        return implode(' ', $aWords) . ' ' . $sAppendix;
    }
    
    /**
     * Returns declination for value (for russian only)
     * @param integer $iNumber
     * @param array $aForms
     * @return string
     */
    public static function getDeclination($iNumber, $aForms = array()) {
        $iNumber = intval($iNumber);
        if ( $iNumber % 100 > 10 && $iNumber % 100 < 15 ) {
            return ( !empty($aForms[2]) ) ? $aForms[2] : '';
        }
        if ( $iNumber % 10 == 1 ) {
            return ( !empty($aForms[0]) ) ? $aForms[0] : '';
        }
        if ( $iNumber % 10 > 1 && $iNumber % 10 < 5 ) {
            return ( !empty($aForms[1]) ) ? $aForms[1] : '';
        }
        return ( !empty($aForms[2]) ) ? $aForms[2] : '';
    }
}
