<?php

class Mg_Shop_Helper_ItemDescription 
{
    public static function getDescription($iIdDescription) {
        $oDescription = new Mg_Shop_Model_ItemDescription();
        if ( !empty($iIdDescription) ) {
            $oItemDescriptionMapper = new Mg_Shop_Model_Mapper_ItemDescription();
            $oDescription = $oItemDescriptionMapper->getItem($iIdDescription);
        }
        return $oDescription;
    }
    
    public static function getDescriptionByCode($sCode) {
        $oDescription = new Mg_Shop_Model_ItemDescription();
        if ( !empty($sCode) ) {
            $oItemDescriptionMapper = new Mg_Shop_Model_Mapper_ItemDescription();
            $aWhere = array(array('code = ?', $sCode));
            $oResult = $oItemDescriptionMapper->getList($aWhere, null, 1, 1);
            if ($oResult->getCurrentItemCount() > 0) {
                $oDescription = $oResult->getItem(0);
            }
        }
        return $oDescription;
    }
    
    public static function getDescriptionByItemId($iIdItem) {
        $aDescriptions = array();
        if ( !empty($iIdItem) ) {
            $oItemDescriptionMapper = new Mg_Shop_Model_Mapper_ItemDescription();
            $aWhere = array(array('id_item = ?', $iIdItem));
            $oResult = $oItemDescriptionMapper->getList($aWhere, null);
            foreach ($oResult->getCurrentItems() as $oDescription) {
                $aDescriptions[$oDescription->code] = $oDescription;
            }
        }
        return $aDescriptions;
    }
}