<?php

class Mg_Helper_TechcentreAddress 
{
    
    public static function getAddressesByTechcentre($iIdTechcentre) {
        $iIdTechcentre = intval($iIdTechcentre);
        $aAddresses = array();
        $oTechcentreAddressMapper = new Mg_Model_Mapper_TechcentreAddress();
        $aWhere = array(array('id_techcentre = ?', $iIdTechcentre));
        $oResult = $oTechcentreAddressMapper->getList($aWhere);
        if ( $oResult->count() > 0 ) {
            foreach ($oResult->getCurrentItems() as $oRow) {
                $aAddresses[] = new Mg_Model_TechcentreAddress($oRow->toArray());
            }
        }
        return $aAddresses;
    }
    
    public static function getAddress($iIdAddress) {
        $oAddress = new Mg_Model_TechcentreAddress();
        if ( !empty($iIdAddress) ) {
            $oTechcentreAddressMapper = new Mg_Model_Mapper_TechcentreAddress();
            $oResult = $oTechcentreAddressMapper->getItem($iIdAddress);
            $oAddress->setParams($oResult->current()->toArray());
        }
        return $oAddress;
    }
    
}
