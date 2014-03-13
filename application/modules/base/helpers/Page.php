<?php

class Mg_Base_Helper_Page
{
    
    public static function getRedirectCodes() {
        $aRedirectCodes = array(
            301 => 'Moved Permanently',
            302 => 'Moved Temporarily',
        );
        return $aRedirectCodes;
    }
    
    public static function getPage($iIdPage) {
        $oPage = new Mg_Base_Model_Page();
        if ( !empty($iIdPage) ) {
            $oPageMapper = new Mg_Base_Model_Mapper_Page();
            $oPage = $oPageMapper->getItem($iIdPage);
        }
        return $oPage;
    }
    
    public static function getPageByUrl($sUrl) {
        $oPage = new Mg_Base_Model_Page();
        if ( !empty($sUrl) ) {
            $oPageMapper = new Mg_Base_Model_Mapper_Page();
            $oPage = $oPageMapper->getItemByField('url', $sUrl);
        }
        return $oPage;
    }
    
    public static function getPages() {
        $oPageMapper = new Mg_Base_Model_Mapper_Page();
        $oResult = $oPageMapper->getList(null, array('name ASC'));
        return $oResult;
    }
    
}
