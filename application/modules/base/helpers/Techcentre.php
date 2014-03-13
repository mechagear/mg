<?php

class Mg_Helper_Techcentre 
{
    public static function getTechcentresByCreator($iIdUser) {
        $aTechcentres = array();
        $oTechcentreMapper = new Mg_Model_Mapper_Techcentre();
        $aWhere = array(array('id_user = ?', intval($iIdUser)));
        $oResult = $oTechcentreMapper->getList($aWhere);
        if ( $oResult->count() > 0 ) {
            foreach ($oResult->getCurrentItems() as $oRow) {
                $aTechcentres[] = new Mg_Model_Techcentre($oRow->toArray());
            }
        }
        return $aTechcentres;
    }
    
    public static function getTechcentre($iIdTechcentre) {
        $oTechcentre = new Mg_Model_Techcentre();
        if ( !empty($iIdTechcentre) ) {
            $oTechcentreMapper = new Mg_Model_Mapper_Techcentre();
            $oResult = $oTechcentreMapper->getItem($iIdTechcentre);
            $oTechcentre->setParams($oResult->current()->toArray());
        }
        return $oTechcentre;
    }
    
    public static function getTechcentreByUrl($sUrl) {
        $oTechcentre = new Mg_Model_Techcentre();
        if ( !empty($sUrl) ) {
            $oTechcentreMapper = new Mg_Model_Mapper_Techcentre();
            $aWhere = array(array('url = ?', $sUrl));
            $oResult = $oTechcentreMapper->getList($aWhere, null, 1, 1);
            $oTechcentre->setParams($oResult->getItem(0)->toArray());
        }
        return $oTechcentre;
    }
}
