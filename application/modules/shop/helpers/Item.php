<?php

class Mg_Shop_Helper_Item 
{
    public static function getItem($iIdItem) {
        $oItem = new Mg_Shop_Model_Item();
        if ( !empty($iIdItem) ) {
            $oItemMapper = new Mg_Shop_Model_Mapper_Item();
            $oItem = $oItemMapper->getItem($iIdItem);
        }
        return $oItem;
    }
    
    public static function getItemByUrl($sUrl) {
        $oItem = new Mg_Shop_Model_Item();
        if ( !empty($sUrl) ) {
            $oItemMapper = new Mg_Shop_Model_Mapper_Item();
            $aWhere = array(array('url = ?', $sUrl));
            $oResult = $oItemMapper->getList($aWhere, null, 1, 1);
            $oItem = $oResult->getItem(0);
        }
        return $oItem;
    }
    
    /**
     * @todo Think about something more smart, COUNT for example
     * @param type $iCategoryId
     * @return int
     */
    public static function getCountByCategory($iCategoryId) {
        $iCount = 0;
        if ( !empty($iCategoryId) ) {
            $oItemMapper = new Mg_Shop_Model_Mapper_Item();
            $aWhere = array(array('id_category = ?', $iCategoryId));
            $oResult = $oItemMapper->getList($aWhere, null, 0, 0);
            $iCount = $oResult->getCurrentItemCount();
        }
        return $iCount;
    }
}