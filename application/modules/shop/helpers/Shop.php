<?php

class Mg_Shop_Helper_Shop 
{
    public static function getShop($iIdShop) {
        $oShop = new Mg_Shop_Model_Shop();
        if ( !empty($iIdShop) ) {
            $oShopMapper = new Mg_Shop_Model_Mapper_Shop();
            $oShop = $oShopMapper->getItem($iIdShop);
        }
        return $oShop; 
    }
    
    public static function getShopByCode($sCode) {
        $oShop = new Mg_Shop_Model_Shop();
        if ( !empty($sCode) ) {
            $oShopMapper = new Mg_Shop_Model_Mapper_Shop();
            $aWhere = array(array('code = ?', $sCode));
            $oResult = $oShopMapper->getList($aWhere, null, 1, 1);
            $oShop = $oResult->getItem(0);
        }
        return $oShop;
    }
    
}