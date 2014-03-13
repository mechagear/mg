<?php

class Mg_Common_Helper_Status
{
    
    public static function getStatus($iIdStatus, $sParentTable) {
        $oPage = new Mg_Base_Model_Page();
        if ( !empty($iIdPage) ) {
            $oPageMapper = new Mg_Base_Model_Mapper_Page();
            $oResult = $oPageMapper->getItem($iIdPage);
            $oPage->setParams($oResult->current()->toArray());
        }
        return $oPage;
    }
    
    public static function getStatusByCode($sCode, $sParentTable) {
        $oPage = new Mg_Base_Model_Page();
        if ( !empty($sUrl) ) {
            $oPageMapper = new Mg_Base_Model_Mapper_Page();
            $aWhere = array(array('url = ?', $sUrl));
            $oResult = $oPageMapper->getList($aWhere, null, 1, 1);
            $oPage->setParams($oResult->getItem(0)->toArray());
        }
        return $oPage;
    }
    
    public static function getStatuses($sParentTable) {
        $oStatusMapper = new Mg_Model_Mapper_Status($sParentTable);
        $oResult = $oStatusMapper->getList(null, array('id_status ASC'));
        return $oResult;
    }
    
    public static function getStatusesAsArray($sParentTable) {
        $oResult = self::getStatuses($sParentTable);
        $aStatuses = array();
        foreach ($oResult as $oStatus) {
            $aStatuses[$oStatus->id_status] = $oStatus;
        }
        return $aStatuses;
    }
    
}
