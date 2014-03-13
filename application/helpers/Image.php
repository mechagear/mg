<?php

class Mg_Common_Helper_Image {
    const OBJ_USER_AVATAR = 2;
    
    protected static $aBounds = array(1, 100);
    protected static $aPreferences = array();
    
    public static function getPreferences() {
        $aConfig = Zend_Registry::get('config');
        $sClass = get_called_class();
        $aPrefs = $sClass::$aPreferences;
        $aPrefs = array_merge($aConfig['staticfile']['image']['preferences'], $aPrefs);
        return $aPrefs;
    }
    
    public static function getImages($iObjId, $iObjType, $sKey = 'origin', $iSorting = 0) {
        $aConfig = Zend_Registry::get('config');
        
        $sClass = get_called_class();
        if ( !$sClass::checkTypeBounds($iObjType) ) {
            throw new Exception('Image type out of bounds.');
        }
        
        
        $aPrefs = $sClass::$aPreferences;
        $aConfig['staticfile']['image']['preferences'] = array_merge($aConfig['staticfile']['image']['preferences'], $aPrefs);
        $oStaticFileImage = new Mg_Addon_StaticFileImage($iObjId, $iObjType, $aConfig['staticfile']);
        
        $aImages = $oStaticFileImage->getFilesByKey($sKey);
        if ( 'origin' != $sKey ) {
            $aOrigins = $oStaticFileImage->getFilesByKey('origin');
            foreach ($aImages as $iSequence => $aImage) {
                $aImages[$iSequence]['order'] = $aOrigins[$iSequence]['order'];
                $aImages[$iSequence]['origin'] = $aOrigins[$iSequence];
            }
        }
        
        if ( $iSorting != 0 ) {
            $callback = function($a, $b) use ($iSorting) {
                if ( $a['order'] == $b['order'] ) {
                    return 0;
                }
                if ($iSorting > 0) {
                    return ($a['order'] > $b['order']) ? 1 : -1;
                } else {
                    return ($a['order'] > $b['order']) ? -1 : 1;
                }
            };
            usort($aImages, $callback);
        }
        return $aImages;
    }
    
    public static function uploadImage($iObjId, $iObjType, $aFile) {
        $aConfig = Zend_Registry::get('config');
        $oStaticFileImage = new Mg_Addon_StaticFileImage($iObjId, $iObjType, $aConfig['staticfile']);
        
        return $oStaticFileImage->upload($aFile);
    }
    
    protected static function checkTypeBounds($iObjType) {
        $sClass = get_called_class();
        $aBounds = $sClass::$aBounds;
        if ( $iObjType > $aBounds[1] || $iObjType < $aBounds[0] ) {
            return false;
        } else {
            return true;
        }
    }
    
}
