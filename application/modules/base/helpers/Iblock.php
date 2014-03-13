<?php

class Mg_Base_Helper_Iblock
{
    public static function getIblock($iIdIblock) {
        $oIblock = new Mg_Base_Model_Iblock();
        if ( !empty($iIdIblock) ) {
            $oIblockMapper = new Mg_Base_Model_Mapper_Iblock();
            $oIblock = $oIblockMapper->getItem($iIdIblock);
        }
        return $oIblock; 
    }
    
    public static function getIblockByCode($sCode) {
        $oIblock = new Mg_Base_Model_Iblock();
        if ( !empty($sCode) ) {
            $oIblockMapper = new Mg_Base_Model_Mapper_Iblock();
            $aWhere = array(array('code = ?', $sCode));
            $oResult = $oIblockMapper->getList($aWhere, null, 1, 1);
            $oIblock = $oResult->getItem(0);
        }
        return $oIblock;
    }
    
}
