<?php

class Mg_Base_Helper_IblockElement
{
    public static function getElement($iIdElement) {
        $oElement = new Mg_Base_Model_IblockElement();
        if ( !empty($iIdElement) ) {
            $oElementMapper = new Mg_Base_Model_Mapper_IblockElement();
            $oResult = $oElementMapper->getItem($iIdElement);
            $oElement = $oResult;
            //$oElement->setParams($oResult->current()->toArray());
        }
        return $oElement;
    }
    
    public static function getElementByUrl($sUrl, $iCategoryId) {
        $oElement = new Mg_Base_Model_IblockElement();
        if ( !empty($sUrl) ) {
            $oElementMapper = new Mg_Base_Model_Mapper_IblockElement();
            $aWhere = array(array('url = ?', $sUrl));
            if ( !empty($iCategoryId) ) {
                $aWhere[] = array('id_category = ?', $iCategoryId);
            }
            $oResult = $oElementMapper->getList($aWhere, null, 1, 1);
            $oElement = $oResult->getItem(0);
        }
        return $oElement;
    }
    
    /**
     * @todo Think about something more smart, COUNT for example
     * @param type $iCategoryId
     * @return int
     */
    public static function getCountByCategory($iCategoryId) {
        $iCount = 0;
        if ( !empty($iCategoryId) ) {
            $oItemMapper = new Mg_Base_Model_Mapper_IblockElement();
            $aWhere = array(array('id_category = ?', $iCategoryId));
            $oResult = $oItemMapper->getList($aWhere, null, 0, 0);
            $iCount = $oResult->getCurrentItemCount();
        }
        return $iCount;
    }
    
    
}
