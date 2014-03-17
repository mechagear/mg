<?php

class Mg_Common_Helper_Module 
{
    protected static $aModules = array();
    protected static $sCacheKey = 'modules';
    
    public static function getModules() {
        if (empty(self::$aModules)) {
            $oCache = Mg_Common_Helper_Cache::getCache('file');
            if ( !$oCache || !$aModules = $oCache->load(self::$sCacheKey)) {
                $aModules = array();
                $oDir = new DirectoryIterator(APPLICATION_PATH . '/modules');
                foreach ( $oDir->current() as $oEntity ) {
                    $aModule = array();
                    if ( $oEntity->isDot() || !$oEntity->isDir() ) {
                        continue;
                    }
                    $sFilename = $oEntity->getFilename();
                    $aModule['filename'] = $sFilename;
                    // Trying to load module info
                    $sPathToInfo = APPLICATION_PATH . '/modules/' . $sFilename . '/module.xml';
                    if ( file_exists($sPathToInfo) && is_readable($sPathToInfo) ) {
                        $oModuleInfo = new Zend_Config_Xml($sPathToInfo);
                        $sCode = strval($oModuleInfo->code);
                        $aModule['version'] = $oModuleInfo->version;
                    } else {
                        $sCode = strtoupper($sFilename);
                        $aModule['version'] = null;
                    }
                    $aModules[$sCode] = $aModule;
                }
                if ($oCache) {
                    $oCache->save($aModules, self::$sCacheKey);
                }
            }
            self::$aModules = $aModules;
        }
        return self::$aModules;
    }
    
    public static function hasModule($sCode) {
        $sCode = strtoupper($sCode);
        $aModules = self::getModules();
        if ( isset($aModules[$sCode]) ) {
            return true;
        } else {
            return false;
        }
    }
    
}
