<?php

class Mg_Common_Helper_Cache
{
    protected static $oCacheManager = null;
    
    public static function getManager() {
        if ( !self::$oCacheManager ) {
            $oFront = Zend_Controller_Front::getInstance();
            $oCacheManager = $oFront->getParam('bootstrap')->getPluginResource('cachemanager')->getCacheManager();
            self::$oCacheManager = $oCacheManager;
        }
        return self::$oCacheManager;
    }
    
    public static function getCache($sCache) {
        if ( self::getManager()->hasCache($sCache) ) {
            return self::getManager()->getCache($sCache);
        } else {
            return false;
        }
    }
    
}
