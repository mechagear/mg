<?php

class Mg_Common_Helper_Domain 
{
    protected static $aDomainKeys = array();
    
    public static function getDomainKeys() {
        self::__load();
        return self::$aDomainKeys;
    }
    
    public static function getDomainNameByKey($sDomainKey = '') {
        self::__load();
        if ( !empty(self::$aDomainKeys[$sDomainKey]) ) {
            return self::$aDomainKeys[$sDomainKey];
        } else {
            return '';
        }
    }
    
    protected static function __load() {
        if ( !empty(self::$aDomainKeys) ) {
            return;
        }
        $sDomainsXmlPath = DOMAINS_PATH . '/domains.xml';
        $oDomainsConfig = new Zend_Config_Xml($sDomainsXmlPath);
        foreach ($oDomainsConfig as $sKey => $oDomain) {
            if (!empty($oDomain->options->hidden)) {
                continue;
            }
            self::$aDomainKeys[$sKey] = ($oDomain->name) ? $oDomain->name : $sKey;
        }
    }
    
}